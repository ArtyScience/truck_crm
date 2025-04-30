<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Modules\Core\Logic\Enums\RoleEnum;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (RoleEnum::getValues() as $role) {
            if (!Role::where('name', $role->name)->count())
                Role::create(['name' => $role->name]);
        }
    }
}
