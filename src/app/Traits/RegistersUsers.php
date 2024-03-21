<?php

namespace App\Traits;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Str;

trait RegistersUsers
{
    protected function registerUser($request): User
    {
        $user = new User;

        if ($organization = Organization::find($request->input('organization'))) {
            $user->organization_id = $organization->id;
        }
        else {
            $user->additional_data = [
                'organization' => $request->input('organization'),
            ];
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->password = bcrypt(Str::random(16)); // set a random password and force the user to change it later
        $user->verified = false;
        $user->save();

        $user->assignRole('Pending User');

        $user->sendVerificationNotification();

        return $user;
    }
}
