<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table
        if (DB::getDriverName() === 'sqlite') {
            // Rename existing table
            Schema::rename('time_registrations', 'time_registrations_old');
            
            // Create new table with integer distance
            Schema::create('time_registrations', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('user_id');
                $table->uuid('client_id');
                $table->uuid('project_id')->nullable();
                $table->date('date');
                $table->decimal('duration', 8, 2);
                $table->text('description')->nullable();
                $table->string('status')->default('ready_to_invoice');
                $table->string('location')->nullable();
                $table->unsignedInteger('distance')->nullable();
                $table->timestamps();
            });
            
            // Copy data, rounding distance values
            DB::statement('
                INSERT INTO time_registrations (id, user_id, client_id, project_id, date, duration, description, status, location, distance, created_at, updated_at)
                SELECT id, user_id, client_id, project_id, date, duration, description, status, location, CAST(ROUND(distance) AS INTEGER), created_at, updated_at
                FROM time_registrations_old
            ');
            
            // Drop old table
            Schema::drop('time_registrations_old');
        } else {
            Schema::table('time_registrations', function (Blueprint $table) {
                $table->unsignedInteger('distance')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::rename('time_registrations', 'time_registrations_old');
            
            Schema::create('time_registrations', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('user_id');
                $table->uuid('client_id');
                $table->uuid('project_id')->nullable();
                $table->date('date');
                $table->decimal('duration', 8, 2);
                $table->text('description')->nullable();
                $table->string('status')->default('ready_to_invoice');
                $table->string('location')->nullable();
                $table->decimal('distance', 8, 2)->nullable();
                $table->timestamps();
            });
            
            DB::statement('
                INSERT INTO time_registrations 
                SELECT * FROM time_registrations_old
            ');
            
            Schema::drop('time_registrations_old');
        } else {
            Schema::table('time_registrations', function (Blueprint $table) {
                $table->decimal('distance', 8, 2)->nullable()->change();
            });
        }
    }
};
