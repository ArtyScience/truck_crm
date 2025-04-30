<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Leads\Entities\Lead;
use Modules\Leads\Entities\LeadAddress;

class LeadAddressFactory extends Factory
{
    protected $model = LeadAddress::class;
    public function definition(): array
    {
        return [
            'lead_id' => $this->getLeadId(),
            'location_name' => fake()->unique()->address(),
            'country' => $this->getCountry(),
            'city' => 'Los Angeles',
            'state' => 'NY',
            'zip' => rand(10000, 100000),
        ];
    }

    private function getCountry(): string
    {
        $country_id = rand(0, 1);
        $countries = ['Canada', 'USA'];
        return $countries[$country_id];
    }

    private function getLeadId(): int
    {
        return Lead::inRandomOrder()->first()->id ?? Lead::factory()->create()->id;
    }
}
