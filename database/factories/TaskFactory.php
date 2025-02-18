<?php

namespace Database\Factories;

use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Deal\Entities\Deal;
use Modules\Leads\Entities\Lead;
use Modules\Leads\Entities\LeadCompany;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TaskFactory extends Factory
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
            'title' => substr($this->faker->name, 0, 30),
            'description' => $this->faker->text,
            'user_id' => $this->getUserId(),
            'deal_id' => $this->getDealId(),
            'lead_id' => $this->getLeadId(),
            'status' => rand(0, 1),
            'deadline' => $this->faker->dateTime,
            'priority' => rand(0, 2),
        ];
    }

    private function getUserId(): int
    {
        $user = User::inRandomOrder()->first();
        return $user->id;
    }

    private function getDealId(): int
    {
        $deal = Deal::inRandomOrder()->first();
        return $deal->id;
    }

    private function getLeadId(): int
    {
        $lead = Lead::inRandomOrder()->first();
        return $lead->id;
    }
}
