<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Leads\Entities\LeadAddress;

class LeadAddressSeeder extends Seeder
{
    private int $address_create = 50;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i < $this->address_create; $i++) {
            LeadAddress::factory(10)->create();
        }
    }
}
