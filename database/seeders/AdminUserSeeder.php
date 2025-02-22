<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@pmapp.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password123')
            ]
        );

        if (!$admin->hasRole('Admin')) {
            $admin->assignRole($adminRole);
        }

        echo "Admin user created successfully.\n";
    }
}
