<?php

namespace App\Console\Commands;

use App\Models\JobRun;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Exception;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database and upload to FTP server';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startTime = now();
        $jobRun = JobRun::create([
            'job_name' => 'database_backup',
            'status' => 'running',
        ]);

        try {
            $this->info('Starting database backup...');

            // Get database configuration
            $database = config('database.default');
            $config = config("database.connections.{$database}");

            if ($database !== 'sqlite') {
                throw new Exception('Only SQLite databases are supported for backup');
            }

            // Generate backup filename with timestamp
            $filename = 'backup-' . now()->format('Y-m-d-His') . '.sqlite';
            $localPath = storage_path('app/backups/' . $filename);

            // Ensure backup directory exists
            if (!file_exists(dirname($localPath))) {
                mkdir(dirname($localPath), 0755, true);
            }

            // Get SQLite database path
            $dbPath = $config['database'];
            
            if (!file_exists($dbPath)) {
                throw new Exception("Database file not found: {$dbPath}");
            }

            // Copy SQLite database file
            if (!copy($dbPath, $localPath)) {
                throw new Exception('Failed to copy database file');
            }

            $fileSize = filesize($localPath);
            $this->info("Database backup created: {$filename} ({$this->formatBytes($fileSize)})");

            // Upload to FTP
            $this->info('Uploading to FTP server...');
            
            $ftpDisk = Storage::disk('ftp');
            $ftpDisk->put($filename, file_get_contents($localPath));

            $this->info('Backup uploaded successfully to FTP server');

            // Clean up old local backups (keep last 7 days)
            $this->cleanupOldBackups();

            // Update job run status
            $duration = now()->diffInSeconds($startTime);
            $jobRun->update([
                'status' => 'success',
                'duration_seconds' => $duration,
                'metadata' => [
                    'filename' => $filename,
                    'file_size' => $fileSize,
                    'database' => basename($dbPath),
                ],
            ]);

            $this->info("Backup completed successfully in {$duration} seconds");

            return Command::SUCCESS;
        } catch (Exception $e) {
            $duration = now()->diffInSeconds($startTime);
            $jobRun->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'duration_seconds' => $duration,
            ]);

            $this->error('Backup failed: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }

    /**
     * Clean up old backup files
     */
    private function cleanupOldBackups(): void
    {
        $backupPath = storage_path('app/backups');
        $files = glob($backupPath . '/backup-*.sqlite');
        
        foreach ($files as $file) {
            if (filemtime($file) < strtotime('-7 days')) {
                unlink($file);
                $this->info('Deleted old backup: ' . basename($file));
            }
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
