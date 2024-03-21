<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Admin\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest:admin')->except('logout');

        $this->redirectTo = $this->guard()->user() ? $this->guard()->user()->getHomepage() : route('admin.dashboard.index');
    }

    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');

        // only verified and activated users can login
        $credentials['activated'] = '1';
        $credentials['verified'] = '1';

        return $credentials;
    }

    protected function authenticated(Request $request, $user)
    {
        activity('users')->causedBy($user)
            ->performedOn($user)
            ->log('logged in');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function logout(Request $request)
    {
        $user = $this->guard()->user();

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request, $user) ?: redirect('/');
    }

    protected function loggedOut(Request $request, $user)
    {
        if ($user) {
            activity('users')->causedBy($user)
                ->performedOn($user)
                ->log('logged out');
        }
    }
}
