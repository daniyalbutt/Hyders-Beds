<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['order','create order', 'edit order', 'delete order'];
        foreach ($roles as $value) {
            Permission::create([
                'name' => $value
            ]);
        }
    }
}
