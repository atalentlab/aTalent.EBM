<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\RegisterUserRequest;
use App\Traits\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showForm()
    {
        return view('admin.auth.register.show');
    }

    public function submit(RegisterUserRequest $request)
    {
        $user = $this->registerUser($request);

        return redirect()->route('admin.auth.register.success');
    }

    public function success()
    {
        return view('admin.auth.register.success');
    }
}
