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
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('project_number_format')->default('PR-yyyy-nnnn');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('project_number')->nullable();
            $table->unique(['tenant_id', 'project_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('project_number_format');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropUnique(['tenant_id', 'project_number']);
            $table->dropColumn('project_number');
        });
    }
};
