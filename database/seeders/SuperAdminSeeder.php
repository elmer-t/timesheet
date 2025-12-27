<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get system tenant
        $systemTenant = \App\Models\Tenant::firstOrCreate(
            ['slug' => 'system'],
            [
                'name' => 'System',
                'company_name' => 'System Administration',
                'is_system' => true,
                'currency' => 'EUR',
                'distance_unit' => 'km',
            ]
        );
        
        // Create or get super admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'elmer.torensma+sa@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'is_super_admin' => true,
                'is_admin' => false,
                'tenant_id' => $systemTenant->id,
            ]
        );
        
        $this->command->info('System tenant created successfully!');
        $this->command->info('Super admin user created successfully!');
        $this->command->info('Email: elmer.torensma+sa@gmail.com');
        $this->command->info('Password: password123');
    }
}
