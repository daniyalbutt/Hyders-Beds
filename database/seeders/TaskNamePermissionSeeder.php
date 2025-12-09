<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TaskNamePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $roles = ['admin'];
        // $permissions = ['task_names', 'create task_names', 'edit task_names', 'delete task_names'];
        // foreach ($permissions as $permission) {
        //     Permission::firstOrCreate(['name' => $permission]);
        // }
        // $adminRole = Role::firstOrCreate(['name' => 'admin']);
        // $adminRole->syncPermissions($permissions);
    }
}
