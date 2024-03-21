<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('admin')->user()->hasPermissionTo('manage users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email',
            'phone'     => 'nullable|string|max:255',
            'activated' => 'boolean',
            'verified' => 'boolean',
            'organization_id' => 'nullable|integer|exists:organizations,id',
            'roles'     => 'nullable|array|max:20',
            'roles.*'   => 'string|exists:roles,name',
            'memberships' => 'nullable|array|max:2',
            'memberships.*.id' => 'required|numeric',
            'memberships.*.role_id' => 'required|integer|exists:roles,id',
            'memberships.*.order' => 'required|integer',
            'memberships.*.expires_at' => 'required|date|after:today',
            'memberships.*.is_trial' => 'nullable|boolean',
            'receives_my_organization_report' => 'boolean',
            'receives_competitor_report' => 'boolean',
        ];

        if ($this->routeIs('admin.*.update')) {
            $rules['email'] = $rules['email'] . ',' . $this->route('id');
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        $input = $this->all();
        $input['email'] = strtolower($input['email']);

        $this->replace($input);
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('error', 'There were errors while saving the user.');

        //dd($validator->errors());

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
