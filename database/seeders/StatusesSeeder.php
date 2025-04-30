<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*TODO: CHANGE DEALS STATUSES*/
        $available_statuses = [
            'Database',
            'Calls',
            'Unanswered_1',
            'Unanswered_2',
            'Contacted',
            'Got_e-mail',
            'Interested',
            'Completed'
        ];

        foreach ($available_statuses as $status) {
            if (!Status::where('name', $status)->exists()) {
                Status::create(['name' => $status]);
            }
        }
    }
}
