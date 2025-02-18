<?php

namespace Database\Seeders;

use Database\Factories\DealDetailsFactory;
use Database\Factories\DealFactory;
use Database\Factories\DealLocationsFactory;
use Database\Factories\DealOptionsFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Deal\Entities\Deal;

class DealSeeder extends Seeder
{
    protected $deals = 21;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 0; $i < $this->deals; $i++) {
            $deal_factory = new DealFactory();
            $created_deal = Deal::create($deal_factory->definition());

            $deal_details_factory = new DealDetailsFactory();
            $deal_details_factory->setDealId($created_deal->id);
            DB::table('deals_details')->insert($deal_details_factory->definition());

            $deal_options_factory = new DealOptionsFactory();
            $deal_options_factory->setDealId($created_deal->id);
            DB::table('deals_options')->insert($deal_options_factory->definition());

            $deal_locations_factory = new DealLocationsFactory();
            $deal_locations_factory->setDealId($created_deal->id);
            $location_types = ['to', 'from'];
            array_map(function ($type) use ($deal_locations_factory) {
                $deal_locations_factory->setLocationType($type);
                DB::table('deal_locations')->insert($deal_locations_factory->definition());
            }, $location_types);

            unset($deal_factory, $deal_details_factory, $deal_options_factory, $deal_locations_factory);
        }
    }
}
