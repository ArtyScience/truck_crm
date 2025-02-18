<?php

namespace Database\Factories;

use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Leads\Entities\Lead;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DealFactory extends Factory
{
    protected $model = Deal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->getUserId(),
            'lead_id' => $this->getLeadId(),
            'status_id' => $this->getStatusId(),
            'income' => rand(100, 100000),
            'outcome' => rand(100, 100000),
        ];
    }

    private function getLeadId()
    {
        return Lead::inRandomOrder()->first()->id;
    }

    private function getUserId()
    {
        return User::inRandomOrder()->first()->id;
    }

    private function getStatusId()
    {
        return Status::inRandomOrder()->first()->id;
    }
}
