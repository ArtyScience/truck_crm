<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Deal\Entities\Deal;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DealOptionsFactory extends Factory
{

    protected int $deal_id;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'deal_id' => $this->deal_id,
            'pick_up' => $this->faker->boolean(),
            'delivery' => $this->faker->boolean(),
            'haz' => $this->faker->boolean(),
            'tarp' => $this->faker->boolean(),
            'temp' => $this->faker->boolean(),
        ];
    }

    public function setDealId(int $deal_id): void
    {
        $this->deal_id = $deal_id;
    }
}
