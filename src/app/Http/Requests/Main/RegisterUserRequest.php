<?php

namespace App\Http\Requests\Main;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('admin')->check() === false; // only guest users are allowed to register
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'      => 'required|max:255',
            'email'     => 'required|email|max:255|unique:users,email',
            'organization' => 'required|string|max:255',
            //'phone'     => 'required|string|max:15|min:8',
        ];
    }

    public function wantsJson()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $input = $this->all();
        $input['email'] = strtolower($input['email']);

        $this->replace($input);
    }
}
