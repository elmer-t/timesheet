<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('time_registrations', function (Blueprint $table) {
            // Make project_id nullable
            $table->uuid('project_id')->nullable()->change();
            
            // Add status field with default 'ready_to_invoice'
            $table->string('status', 20)->default('ready_to_invoice')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_registrations', function (Blueprint $table) {
            // Revert project_id to not nullable (may fail if nulls exist)
            $table->uuid('project_id')->nullable(false)->change();
            
            // Drop status column
            $table->dropColumn('status');
        });
    }
};
