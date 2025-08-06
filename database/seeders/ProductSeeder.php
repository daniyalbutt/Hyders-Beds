<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['product','create product', 'edit product', 'delete product'];
        foreach ($roles as $value) {
            Permission::create([
                'name' => $value
            ]);
        }
    }
}
