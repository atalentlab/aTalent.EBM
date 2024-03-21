<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StoreConfirmedUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password'  => 'required|min:8|confirmed',
            'agreed_to_toc' => 'required|boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $input = $this->all();
        $input['agreed_to_toc'] = isset($input['agreed_to_toc']) ? '1' : '0';

        $this->replace($input);
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('error', 'There were errors while activating your account.');

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
