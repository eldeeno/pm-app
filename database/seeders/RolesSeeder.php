<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'Admin']);
        $manager = Role::create(['name' => 'Project Manager']);
        $member = Role::create(['name' => 'Team Member']);

        Permission::create(['name' => 'store projects']);
        Permission::create(['name' => 'view projects']);
        Permission::create(['name' => 'edit projects']);
        Permission::create(['name' => 'delete projects']);
        Permission::create(['name' => 'view tasks']);
        Permission::create(['name' => 'store tasks']);
        Permission::create(['name' => 'edit tasks']);
        Permission::create(['name' => 'delete tasks']);

        $admin->givePermissionTo(Permission::all());
        $manager->givePermissionTo(['view projects', 'edit projects', 'view tasks', 'store tasks']);
        $member->givePermissionTo(['view projects', 'view tasks']);
    }
}
