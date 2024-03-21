<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class StoreIndustryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('admin')->user()->hasPermissionTo('manage industries');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'      => 'required|string|max:255|unique:industries,name',
            'order'     => 'required|integer',
            'published' => 'boolean',
        ];

        if ($this->routeIs('admin.*.update')) {
            $rules['name'] = $rules['name'] . ',' . $this->route('id');
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('error', 'There were errors while saving the industry.');

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
