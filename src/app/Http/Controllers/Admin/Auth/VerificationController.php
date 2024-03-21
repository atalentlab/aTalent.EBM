<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Exceptions\UserVerificationTokenExpiredException;
use App\Http\Controllers\Admin\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserVerificationService;
use App\Http\Requests\Admin\StoreConfirmedUserRequest;
use App\Models\AdminNotification;

class VerificationController extends Controller
{
    protected $userVerificationService;

    public function __construct(UserVerificationService $userVerificationService)
    {
        $this->middleware('guest:admin', ['except' => 'logout']);
        $this->userVerificationService = $userVerificationService;
    }

    public function verifyUser(Request $request, $token)
    {
        try {
            if ($user = $this->userVerificationService->getUserToVerify($token)) {
                return view('admin.auth.verification-confirmation')->with([
                    'token' => $token,
                ]);
            }
        }
        catch (UserVerificationTokenExpiredException $e) {
            return view('admin.auth.verification-expired');
        }

        abort(404);
    }

    public function confirm(StoreConfirmedUserRequest $request, $token)
    {
        try {
            if ($user = $this->userVerificationService->getUserToVerify($token)) {
                $user->password = bcrypt($request->input('password'));
                $user->agreed_to_toc = true;
                $user->verified = true;
                $user->save();

                $this->userVerificationService->deleteVerification($token);

                $welcomeMessage = __('admin.auth.verification.welcome', ['app' => config('app.name')]);

                // if a user has the Pending User role, an admin will have to be notified to review them
                if ($user->hasRole('Pending User')) {
                    $welcomeMessage = __('admin.auth.verification.welcome-awaiting-approval', ['app' => config('app.name')]);

                    $this->sendNewUserNotification($user);
                }

                $this->guard()->login($user);

                return redirect($user->getHomepage())->with('success', $welcomeMessage);
            }
        }
        catch (UserVerificationTokenExpiredException $e) {
            return view('admin.auth.verification-expired');
        }

        abort(404);
    }

    protected function sendNewUserNotification(User $user)
    {
        AdminNotification::create([
            'type' => 'new_user',
            'title' => __('admin.header.user.new-user-reg-for') . $user->name . ' (' . $user->email . ')',
            'content' => [
                'user_id' => $user->id,
            ],
        ]);

    }
}
