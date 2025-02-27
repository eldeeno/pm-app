<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $managerRole = Role::firstOrCreate(['name' => 'Project Manager']);
        $memberRole = Role::firstOrCreate(['name' => 'Team Member']);

        // Create 3 Admins
        for ($i = 1; $i <= 3; $i++) {
            $admin = User::firstOrCreate(
                ['email' => "admin{$i}@pmapp.com"],
                [
                    'name' => "Admin User {$i}",
                    'password' => Hash::make('password123')
                ]
            );
            $admin->assignRole($adminRole);
        }

        // Create 3 Project Managers
        for ($i = 1; $i <= 3; $i++) {
            $manager = User::firstOrCreate(
                ['email' => "manager{$i}@pmapp.com"],
                [
                    'name' => "Project Manager {$i}",
                    'password' => Hash::make('password123')
                ]
            );
            $manager->assignRole($managerRole);
        }

        // Create 3 Team Members
        for ($i = 1; $i <= 3; $i++) {
            $member = User::firstOrCreate(
                ['email' => "member{$i}@pmapp.com"],
                [
                    'name' => "Team Member {$i}",
                    'password' => Hash::make('password123')
                ]
            );
            $member->assignRole($memberRole);
        }

        $this->command->info('Users for all roles created successfully.');
    }
}
