<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TaskName;

class TaskNamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [
            ['name' => 'Fabric Cutting', 'allow_next_step' => 1],
            ['name' => 'Fabric Borders', 'allow_next_step' => 1],
            ['name' => 'Hand Side Stitch', 'allow_next_step' => 1],
            ['name' => 'Mattress Building', 'allow_next_step' => 1],
            ['name' => 'Tape Edge', 'allow_next_step' => 1],
            ['name' => 'Mattress Bagging', 'allow_next_step' => 1],
            ['name' => 'Base Build', 'allow_next_step' => 1],
            ['name' => 'Base Bagging', 'allow_next_step' => 1],
            ['name' => 'Headboard Bagging', 'allow_next_step' => 1],
            ['name' => 'Loading', 'allow_next_step' => 1],
        ];
        foreach ($tasks as $index => $task) {
            TaskName::create([
                'name' => $task['name'],
                'order' => $index + 1,
                'allow_next_step' => $task['allow_next_step'],
            ]);
        }
    }
}
