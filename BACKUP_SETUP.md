# Database Backup System

This application includes an automated database backup system that runs daily and uploads backups to a remote FTP server.

## Features

- **Automated Daily Backups**: Database is backed up automatically at 2:00 AM every day
- **FTP Upload**: Backups are automatically uploaded to a remote FTP server
- **Job Monitoring**: Admin interface to monitor backup jobs and check for errors
- **Local Cleanup**: Old local backups (> 7 days) are automatically deleted
- **Detailed Logging**: Each job run is logged with status, duration, and metadata

## Setup

### 1. Install FTP Support (if using FTP)

If you want to use FTP for backups, install the Flysystem FTP adapter:

```bash
composer require league/flysystem-ftp "^3.0"
```

### 2. Configure FTP Settings

Add the following to your `.env` file:

```env
FTP_HOST=ftp.example.com
FTP_USERNAME=your_ftp_username
FTP_PASSWORD=your_ftp_password
FTP_PORT=21
FTP_ROOT=/backups
FTP_PASSIVE=true
FTP_SSL=false
FTP_TIMEOUT=30
```

### 2. Run Database Migration

The migration has already been run, but for reference:

```bash
php artisan migrate
```

### 3. Configure Scheduler

Ensure Laravel's scheduler is running. Add this to your crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

On macOS with Herd, you can also use Laravel Scheduler in Herd's dashboard.

## Usage

### Manual Backup

To run a backup manually:

```bash
php artisan backup:database
```

### Monitor Backups

Admins can view the job monitor dashboard at:

```
/app/jobs
```

This page shows:
- Statistics (total runs, successful, failed, running)
- Detailed job history with status and error messages
- Auto-refresh every 10 seconds (optional)
- Filter by status (all, success, failed, running)

## How It Works

1. **Schedule**: The backup runs daily at 2:00 AM via Laravel's scheduler
2. **Database Copy**: Creates a copy of the SQLite database file
3. **Upload**: Uploads the backup to the configured FTP server
4. **Logging**: Records the job execution in the `job_runs` table
5. **Cleanup**: Deletes local backups older than 7 days

## Backup Files

- **Local Storage**: `storage/app/backups/backup-YYYY-MM-DD-HHMMSS.sqlite`
- **FTP Storage**: Configured in `FTP_ROOT` environment variable
- **Filename Format**: `backup-YYYY-MM-DD-HHMMSS.sqlite`
- **Database Type**: SQLite only

## Job Monitoring

The `JobRun` model tracks:
- Job name
- Status (success, failed, running)
- Duration in seconds
- Error messages (if any)
- Metadata (filename, file size, database name)
- Timestamps

## Troubleshooting

### Common Issues
Database file not found**: Verify SQLite database path in config
1. **mysqldump not found**: Ensure MySQL client tools are installed
2. **FTP connection failed**: Verify FTP credentials and firewall settings
3. **Permission denied**: Check storage directory permissions
4. **Job not running**: Verify Laravel scheduler is configured in crontab

### Testing FTP Connection

First ensure your FTP credentials are configured in `.env`, then test:

```bash
php artisan tinker
Storage::disk('ftp')->put('test.txt', 'Hello World');
```

Note: The backup command will fail if FTP credentials are not properly configured.

### Check Job History

```bash
php artisan tinker
App\Models\JobRun::latest()->first();
```

## Security Notes

- Store FTP credentials securely in `.env` file
- Never commit `.env` to version control
- Consider using FTPS (FTP_SSL=true) for encrypted transfers
- Regularly test backup restoration
- Monitor job runs for failures

## Admin Access

Only users with admin privileges can access the job monitor dashboard. This is controlled by the `tenant.admin` middleware.
