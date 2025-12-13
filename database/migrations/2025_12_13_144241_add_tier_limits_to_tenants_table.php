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
            $table->integer('client_limit')->default(3);
            $table->integer('project_limit')->default(3);
            $table->integer('user_limit')->default(1);
            $table->boolean('custom_templates')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['client_limit', 'project_limit', 'user_limit', 'custom_templates']);
        });
    }
};
