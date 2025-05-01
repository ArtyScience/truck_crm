<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Leads\Entities\Lead;

class LeadSeeder extends Seeder
{
    private int $leads_create = 100000;

    public function run(): void
    {
        Lead::factory($this->leads_create)->create();
    }
}
