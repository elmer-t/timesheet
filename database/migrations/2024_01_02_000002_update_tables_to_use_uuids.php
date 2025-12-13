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
        // We need to recreate tables to change from int to uuid
        // This migration assumes fresh installation or will lose data
        
        Schema::disableForeignKeyConstraints();
        
        // Drop all existing tables
        Schema::dropIfExists('time_registrations');
        Schema::dropIfExists('invitations');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('users');
        Schema::dropIfExists('tenants');
        
        // Recreate tenants with UUID
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->uuid('default_currency_id')->nullable();
            $table->timestamps();
        });
        
        // Recreate users with UUID
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->rememberToken();
            $table->timestamps();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
        
        // Recreate clients with UUID
        Schema::create('clients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->index(['tenant_id', 'name']);
        });
        
        // Recreate projects with UUID and currency
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('client_id');
            $table->uuid('currency_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive', 'completed'])->default('active');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('hourly_rate', 10, 2)->default(0);
            $table->timestamps();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('restrict');
            $table->index(['tenant_id', 'client_id']);
            $table->index('status');
        });
        
        // Recreate time_registrations with UUID
        Schema::create('time_registrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('client_id');
            $table->uuid('project_id');
            $table->date('date');
            $table->decimal('duration', 5, 2);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->index(['user_id', 'date']);
            $table->index(['client_id', 'date']);
            $table->index('project_id');
        });
        
        // Recreate invitations with UUID
        Schema::create('invitations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('invited_by');
            $table->string('email');
            $table->string('token')->unique();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('invited_by')->references('id')->on('users')->onDelete('cascade');
            $table->index(['tenant_id', 'email']);
            $table->index('token');
        });
        
        // Add foreign key for default currency
        Schema::table('tenants', function (Blueprint $table) {
            $table->foreign('default_currency_id')->references('id')->on('currencies')->onDelete('set null');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This would require converting back to integers
        // Not practical for a production system
        throw new Exception('Cannot reverse UUID migration');
    }
};
