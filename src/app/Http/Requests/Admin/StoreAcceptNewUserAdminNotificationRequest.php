<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StoreAcceptNewUserAdminNotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('admin')->user()->hasPermissionTo('manage notifications');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'organization' => 'nullable|max:255|unique:organizations,name',
            'organization_id' => 'required_without:organization|nullable|integer|exists:organizations,id'
        ];
    }

    protected function prepareForValidation()
    {
        $input = $this->all();

        $this->replace($input);
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('error', 'There were errors while saving the notification.');

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
