<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Admin\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin');

        $this->redirectTo = $this->guard()->user() ? $this->guard()->user()->getHomepage() : route('admin.dashboard.index');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('admin.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    protected function credentials(Request $request)
    {
        $credentials = $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        );

        // only verified and activated users can login
        $credentials['activated'] = '1';
        $credentials['verified'] = '1';

        return $credentials;
    }

    protected function sendResetResponse(Request $request, $response)
    {
        $user = $this->guard()->user();

        activity('users')->causedBy($user)
            ->performedOn($user)
            ->log('reset password');

        return redirect($this->redirectPath())
            ->with('status', trans($response));
    }
}
