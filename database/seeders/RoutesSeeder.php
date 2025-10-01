<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RoutesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['routes','create routes', 'edit routes', 'delete routes'];
        foreach ($roles as $value) {
            Permission::create([
                'name' => $value
            ]);
        }
    }
}
