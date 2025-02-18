<?php

namespace Modules\User\Http\Controllers;

use App\Models\CampaignsUserEmail;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\User\Http\Requests\UpdateUserRequest;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Http\Controllers\CoreController;
use Modules\User\Http\Requests\UserStoreRequest;
use function PHPUnit\Framework\isNull;

class UserController extends CoreController
{
    use HasRoles;
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role:ADMIN']);
    }

    public function all(): JsonResponse
    {
        $users = User::select('id as value', 'name as label')->get();
        return response()->json($users);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $users = User::select('users.id', 'users.name', 'users.email',
            'campaigns_user_emails.email as campaign_email', 'roles.name as role')
            ->leftJoin('campaigns_user_emails', 'campaigns_user_emails.user_id', 'users.id')
            ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->orderBy('id', 'desc')->paginate(15);

        $roles = Role::select('id as value', 'name as label')
            ->orderBy('id', 'ASC')
            ->get();

        if ($request->get('page')) return response()->json($users);

        return view('user::index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('user::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(UserStoreRequest $request)
    {
        $user = $request->except('role',
            'password_confirmation', 'campaign_email');
        $user['password'] = bcrypt($user['password']);
        $user = User::create($user);

        CampaignsUserEmail::create([ 'user_id' => $user->id,
            'email' => $request->campaign_email]);

        $role = Role::where('id', $request['role'])->first();

        $user->assignRole($role->name);

        $response = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'campaign_email' => $request->campaign_email,
            'role' => $role->name,
        ];

        return response()->json($response);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('user::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('user::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) return $this->errorValidation($validator);

        $email_exists = User::where('email', $request->get('email'))
                            ->where('id', '!=', $id)->count();

        if ($email_exists) {
            $validator = Validator::make($request->all(), [ 'email' => 'unique:users,email']);
            if ($validator->fails()) return $this->errorValidation($validator);
        } else {
            if (!empty( $request->get('password') )) {
                $validator = Validator::make($request->all(), [
                    'password' => [ 'string', 'min:8', 'confirmed', 'regex:/[a-z]/',
                        'regex:/[A-Z]/', 'regex:/[0-9]/'],
                ]);
                if ($validator->fails()) {
                    return $this->errorValidation($validator);
                } else {
                    $user['password'] = bcrypt($request->get('password'));
                }
            }

            $user['name'] = $request->get('name');
            $user['email'] = $request->get('email');
            User::where('id', $id)->update($user);

            /*TODO: FIX TO GET ONLY ID*/
            $role = Role::where('name', $request->get('role'))
                            ->orWhere('id', $request->get('role'))->first();
            if ($role) {
                DB::table('model_has_roles')->where('model_id', $id)->delete();
                $user = User::where('id', $id)->first();
                $user->assignRole($role->name);
            }
        }

        $campaign_user = CampaignsUserEmail::where('user_id', $id)->first();

        if (!is_null($campaign_user)) {
            CampaignsUserEmail::where('user_id', $id)->update([
                'email' => $request->get('campaign_email'),
            ]);
        } else {
            CampaignsUserEmail::create([
                'user_id' => $id,
                'email' => $request->get('campaign_email'),
            ]);
        }

        $response = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'campaign_email' => $request->get('campaign_email'),
            'role' =>  $role ? $role->name : null,
        ];

        return response()->json($response);
    }

    private function errorValidation($validator): JsonResponse
    {
        return response()->json([
            'message' => 'Validation failed!',
            'errors' => $validator->errors(),
        ], 422);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return string
     */
    public function destroy(int $id): JsonResponse
    {
        User::destroy($id);
        return response()->json(['message' => 'User deleted successfully']);
    }
}
