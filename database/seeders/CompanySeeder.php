<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Leads\Entities\Lead;
use Modules\Leads\Entities\LeadCompany;

class CompanySeeder extends Seeder
{
    private array $companies = ['Western', 'BlackRock', 'NewBalance', 'Apple',
        'Google', 'Twitter', 'Amazon', 'Transimex', 'Nokia'];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        $leads = Lead::all();
//        foreach ($leads as $lead) {
//            $company_id = rand(0, count($this->companies) - 1);
//            LeadCompany::create([
//                'lead_id' => $lead->id,
//                'name' => $this->companies[$company_id],
//            ]);
//        }
    }
}
