<?php

namespace Modules\Leads\Database\Seeders;

use App\Models\Lead;
use Illuminate\Database\Seeder;

class LeadsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private int $lead_create = 100;
    public function run(): void
    {
        for ($i = 1; $i < $this->lead_create; $i++) {
            Lead::factory(10)->create();
        }
    }
}
