<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Leads\Entities\Comodity;

class ComoditySeeder extends Seeder
{
    CONST array DEFAULT_COMODITY = ['steel coils', 'steel sheets',
        'plastics pipes', 'bricks', 'granulated plastic on skids'];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::DEFAULT_COMODITY as $comodity) {
            Comodity::create(['name' => $comodity]);
        }
    }
}
