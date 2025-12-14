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
            $table->string('location')->nullable()->after('description');
            $table->decimal('distance', 8, 2)->nullable()->after('location');
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->string('distance_unit', 10)->default('km')->after('project_number_format');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_registrations', function (Blueprint $table) {
            $table->dropColumn(['location', 'distance']);
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('distance_unit');
        });
    }
};
