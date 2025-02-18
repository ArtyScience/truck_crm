<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Deal\Entities\Deal;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DealLocationsFactory extends Factory
{
    protected int $deal_id;
    protected string $location_type;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'deal_id' => $this->deal_id,
            'full_location' => $this->faker->address(),
            'type' => $this->location_type,
            'city' => $this->faker->city(),
            'state' => $this->getRandomState(),
            'country' => $this->faker->country(),
            'country_code' => $this->faker->numberBetween(100, 1000),
            'postcode' => $this->faker->postcode(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
        ];
    }

    public function setDealId(int $deal_id): void
    {
        $this->deal_id = $deal_id;
    }

    public function setLocationType(string $location_type): void
    {
        $this->location_type = $location_type;
    }

    protected array $country_states = [
        'MX',
        'SD',
        'US'
    ];

    public function getRandomState()
    {
        return $this->country_states[rand(0, count($this->country_states) - 1)];
    }

}
