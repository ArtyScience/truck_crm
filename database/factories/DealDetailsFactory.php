<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Modules\Deal\Entities\Deal;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DealDetailsFactory extends Factory
{
    protected int $deal_id;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $check = DB::table('deals_details')
            ->where('deal_id', $this->deal_id)->count();

        if (!$check) {
            return [
                'deal_id' => $this->deal_id,
                'pick_up_date' => $this->faker->date(),
                'delivery_date' => $this->faker->date(),
                'equipment_type' => $this->getRandomEquipment(),
                'shipment_type' => $this->getRandomShipment(),
                'deal_type' => $this->faker->word,
                'comment' => $this->faker->text,
            ];
        } else {
            return [];
        }
    }

    protected array $equipments = [
        'VAN',
        'Flatbed',
        'ETc'
    ];

    private function getRandomEquipment() {
        return array_rand($this->equipments);
    }

    protected array $shipments = [
        'NL',
        'PL'
    ];

    private function getRandomShipment() {
        return array_rand($this->shipments);
    }


    public function setDealId(int $id): void
    {
        $this->deal_id = $id;
    }
}
