<?php

namespace Database\Seeders;

use Database\Factories\TaskFactory;
use Illuminate\Database\Seeder;
use Modules\Task\Entities\TaskModel;

class TaskSeeder extends Seeder
{

    private int $tasc_create = 500;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $task_factory = new TaskFactory();
        for ($i = 0; $i < $this->tasc_create; $i++) {
            TaskModel::create($task_factory->definition());
        }
    }
}
