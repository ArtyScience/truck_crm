<?php

namespace Modules\Settings\Http\Controllers;

use App\Models\Permission;
use App\Models\RoleHasPermision;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Modules\Core\Http\Controllers\CoreController;

use Spatie\Permission\Models\Role;

class SettingsController extends CoreController
{
    public function __construct()
    {
        parent::__construct();
        /*TODO: Implement Role Middleware*/
//        $this->middleware(['role:admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['roles'] = Role::all();
        $data['permissions'] = Permission::all();
        $data['roles_has_permissions'] = RoleHasPermision::getPermissionRole();

        return view('settings::index', compact('data'));
    }

    public function saveTheme(Request $request): void
    {
        $one_year = 525600;
        Cookie::queue('theme',
            $request->post('theme'), $one_year);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('settings::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('settings::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
