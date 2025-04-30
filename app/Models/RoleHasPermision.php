<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class RoleHasPermision extends Model
{
    use HasFactory;
    protected $table = 'role_has_permissions';
    protected $fillable = ['permission_id', 'role_id'];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null;

    public static function getPermissionRole(): Collection
    {
        return Permission::select('permissions.name as permission',
            'permissions.id as permission_id',
            'roles.id as role_id', 'roles.name as role')
                ->leftJoin('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->rightJoin('roles', 'role_has_permissions.role_id', '=', 'roles.id')->get();
    }
}
