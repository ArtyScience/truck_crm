<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private int $user_create = 10;
    public function run(): void
    {
        for ($i = 1; $i < $this->user_create; $i++) {
             \App\Models\User::factory(10)->create();
        }
        $role_seeder = new RolesTableSeeder();
        $role_seeder->run();
        $permissions_seeder = new PermissionsSeeder();
        $permissions_seeder->run();
        $lead_statuses_seeder = new LeadStatusesSeeder();
        $lead_statuses_seeder->run();
        $lead_seeder = new LeadSeeder();
        $lead_seeder->run();
        $lead_address_seeder = new LeadAddressSeeder();
        $lead_address_seeder->run();
        $comodity_seeder = new ComoditySeeder();
        $comodity_seeder->run();
        $company_seeder = new CompanySeeder();
        $company_seeder->run();
        $statuses_seeder = new StatusesSeeder();
        $statuses_seeder->run();
        $deal_seeder = new DealSeeder();
        $deal_seeder->run();
        $task_seeder = new TaskSeeder();
        $task_seeder->run();
    }
}
