<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreUpgradeAccountRequest;
use App\Recipients\AdminRecipient;
use App\Notifications\UserUpgradeAccountRequest;

class ContactController extends Controller
{
    const UPGRADE_ACCOUNT_RECIPIENT = 'info@ebiglobal.cn';

    public function submitUpgradeAccountForm(StoreUpgradeAccountRequest $request)
    {
        $user = $this->guard()->user();
        $organization = $user->organization;
        $phone = $request->input('phone');
        $reason = 'The main reason for upgrading their account is to add more competitors.';

        if (!$user->phone) {
            $user->phone = $phone;
            $user->save();
        }

        (new AdminRecipient(self::UPGRADE_ACCOUNT_RECIPIENT))->notify(new UserUpgradeAccountRequest($user, $organization, $reason, $phone));

        return response()->json();
    }
}
