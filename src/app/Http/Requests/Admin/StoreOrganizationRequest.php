<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class StoreOrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('admin')->user()->hasPermissionTo('manage organizations');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'published'     => 'boolean',
            'is_fetching'   => 'boolean',
            'name'          => 'required|max:255|unique:organizations,name',
            'logo'          => 'required|mimetypes:image/svg+xml,image/svg|max:50',
            'industry_id'   => 'required|integer|exists:industries,id',
            'intro'         => 'nullable|string|max:500',
            'website'       => 'nullable|url|max:255',
            'channels'      => 'array',
            'competitors'   => 'nullable|array|max:5',
            'competitors.*' => 'nullable|integer|exists:organizations,id',
            'channels.*.channel_username' => 'string|max:255',
        ];

        if ($this->routeIs('admin.*.update')) {
            $rules['name'] = $rules['name'] . ',' . $this->route('id');
            $rules['logo'] = str_replace('required', 'nullable', $rules['logo']);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'channels.*.channel_username.max' => 'The ID may not be greater than :max characters.',
        ];
    }

    protected function prepareForValidation()
    {
        $input = $this->all();
        $input['channels'] = array_filter($this->input('channels'), function ($item) {
            return $item['channel_username'] !== null;
        });

        $this->replace($input);
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('error', 'There were errors while saving the organization.');

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
