<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StoreProfileUpdateRequest extends FormRequest
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
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email,' . Auth::guard('admin')->user()->id,
            'phone'     => 'nullable|string|max:255',
            'organization_id' => 'nullable|integer|exists:organizations,id',
            'receives_my_organization_report' => 'boolean',
            'receives_competitor_report' => 'boolean',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('error', 'There were errors while updating your profile.');

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
