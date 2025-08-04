<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['customer','create customer', 'edit customer', 'delete customer'];
        foreach ($roles as $value) {
            Permission::create([
                'name' => $value
            ]);
        }
    }
}
