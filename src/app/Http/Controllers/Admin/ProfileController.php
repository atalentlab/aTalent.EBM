<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreChangePasswordRequest;
use App\Http\Requests\Admin\StoreProfileUpdateRequest;

class ProfileController extends Controller
{
    public function show()
    {
        $user = $this->guard()->user();

        return view('admin.profile.show')->with([
            'entity' => $user,
            'activeMemberships' => $user->getActiveMemberships(),
        ]);
    }

    public function update(StoreProfileUpdateRequest $request)
    {
        $user = $this->guard()->user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');

        if ($user->can('change my organization')) {
            $user->organization_id = $request->input('organization_id');
        }

        if ($user->can('receive organization report')) {
            $user->receives_my_organization_report =  $request->has('receives_my_organization_report');
        }

        if ($user->can('receive competitor report')) {
            $user->receives_competitor_report =  $request->has('receives_competitor_report');
        }

        $user->save();

        return redirect()->route('admin.profile.show')->with('success', 'Your profile was successfully updated.');
    }

    public function editPassword()
    {
        return view('admin.profile.password.edit')->with([
            'user' => $this->guard()->user(),
        ]);
    }

    public function updatePassword(StoreChangePasswordRequest $request)
    {
        $user = $this->guard()->user();
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return redirect()->route('admin.profile.show')->with('success', 'Your password was successfully updated.');
    }
}
