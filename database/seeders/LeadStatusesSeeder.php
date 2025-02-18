<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Leads\Entities\LeadStatuses;

class LeadStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            if (!LeadStatuses::where('name', $status)->exists()) {
                LeadStatuses::create(['name' => $status]);
            }
        }
    }
}
