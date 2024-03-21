<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class StoreChannelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('admin')->user()->hasPermissionTo('manage channels');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'                      => 'required|string|max:255|unique:channels,name',
            'order'                     => 'required|integer',
            'published'                 => 'boolean',
            'logo'                      => 'required|mimetypes:image/svg+xml,image/svg|max:50',
            'organization_url_prefix'   => 'nullable|string|max:255',
            'organization_url_suffix'   => 'nullable|string|max:255',
            'post_max_fetch_age'        => 'required|integer|max:365|min:0',
            'ranking_weight'            => 'required|integer|max:100|min:0',
            'weight_activity'           => 'required|integer|max:100|min:0',
            'weight_popularity'         => 'required|integer|max:100|min:0',
            'weight_engagement'         => 'required|integer|max:100|min:0',
            'can_collect_views_data'    => 'boolean',
            'can_collect_likes_data'    => 'boolean',
            'can_collect_comments_data' => 'boolean',
            'can_collect_shares_data'   => 'boolean',
        ];

        if ($this->routeIs('admin.*.update')) {
            $rules['name'] = $rules['name'] . ',' . $this->route('id');
            $rules['logo'] = str_replace('required', 'nullable', $rules['logo']);
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('error', 'There were errors while saving the channel.');

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
