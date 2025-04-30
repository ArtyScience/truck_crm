<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleHasPermision;
use Illuminate\Database\Seeder;
use Nwidart\Modules\Collection;

class PermissionsSeeder extends Seeder
{
    private Collection $user;

    protected $roles;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->roles = Role::all();
        $admin = $this->getRole('ADMIN');
        //1. create permission
        $this->createAllPermisions();
        //2. attach permisions to each role
        $this->attachPermisions($admin);
        //attach lets say for admin
    }

    public function createAllPermisions(): void
    {
        $permissions = ['create_user', 'read_user', 'edit_user', 'delete_user'];
        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission, 'guard_name' => 'web']);
            }
        }
    }

    public function attachPermisions($role)
    {
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            if (!RoleHasPermision::where('permission_id', $permission->id)
                    ->where( 'role_id', $role->id)->exists()) {
                RoleHasPermision::create([
                    'permission_id' => $permission->id,
                    'role_id' => $role->id
                    ]
                );
            }
        }
   }

    public function getRole(string $role_check)
    {
        return $this->roles->where('name', $role_check)->first();
    }


    public function subAdminPermissions()
    {

    }
}
