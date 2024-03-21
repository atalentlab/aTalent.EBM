<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.index');
    }

    public function create()
    {
        return view('admin.user.create')->with([
            'roles' => $this->rolesList(),
            'membershipRoles' => Membership::membershipRolesList(),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->password = bcrypt(Str::random(16)); // set a random password and force the user to change it later
        $user->activated = $request->has('activated') ? true : false;
        $user->verified = false;
        $user->organization_id = $request->input('organization_id');
        $user->receives_competitor_report =  $request->has('receives_competitor_report');
        $user->receives_my_organization_report =  $request->has('receives_my_organization_report');

        $user->save();

        $user->syncMemberships($request->input('memberships') ?? []);

        if (!$user->hasActiveMemberships()) {
            $user->syncRoles($request->input('roles') ?? []);
        }

        $user->sendVerificationNotification();

        return redirect()->route('admin.user.edit', ['id' => $user->id])->with('success', 'The user has been created.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.user.edit')->with([
            'entity' => $user,
            'roles' => $this->rolesList(),
            'membershipRoles' => Membership::membershipRolesList(),
            'activeMemberships' => $user->getActiveMemberships(),
            'inActiveMemberships' => $user->getInActiveMemberships(),
        ]);
    }

    public function update(StoreUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->activated = $request->has('activated') ? true : false;
        $user->verified = $request->has('verified') ? true : false;
        $user->organization_id = $request->input('organization_id');
        $user->receives_competitor_report =  $request->has('receives_competitor_report');
        $user->receives_my_organization_report =  $request->has('receives_my_organization_report');

        $user->save();

        $user->syncMemberships($request->input('memberships') ?? []);

        if (!$user->hasActiveMemberships()) {
            $user->syncRoles($request->input('roles') ?? []);
        }

        return redirect()->route('admin.user.index')->with('success', 'The user has been updated.');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        $name = $user->name;

        if ($this->guard()->user()->id == $id) {
            return redirect()->back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'User ' . $name . ' has been deleted.');
    }

    public function getDatatableData()
    {
        $query = User::select('id', 'name', 'email', 'activated', 'last_login')->with('roles');

        return datatables()->of($query)
            ->rawColumns(['actions', 'activated'])
            ->editColumn('activated', function ($user) {
                return $user->activated ? '<span class="status-icon bg-success"><span style="display: none;">1</span></span></i>' : '<span class="status-icon bg-danger"><span style="display: none;">0</span></span>';
            })
            ->editColumn('last_login', function ($user) {
                return optional($user->last_login)->format('Y-m-d H:i:s');
            })
            ->addColumn('actions', function ($user) {
                return '<a href="' . route('admin.user.edit', ['id' => $user->id]) . '" class="btn btn-outline-primary btn-sm" title="Edit user"><i class="fe fe-edit"></i></a>';
            })
            ->editColumn('roles', function ($user) {
                return $user->roles->implode('name', ', ');
            })
            ->make(true);
    }

    protected function rolesList()
    {
        return Role::orderBy('name', 'asc')->pluck('name', 'name');
    }
}
