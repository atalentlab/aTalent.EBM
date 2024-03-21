<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('admin')->user()->hasPermissionTo('manage posts');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'channel_id'            => 'required|integer|exists:channels,id',
            'is_actively_fetching'  => 'boolean',
            'title'                 => 'nullable|string|max:255',
            'posted_date'           => 'required|date',
            'url'                   => 'nullable|url|max:2048',
            'post_id' => [
             'required',
             'string',
             'max:400',
             $this->getPostUniqueRule(),
            ],
         ];
    }

    /**
     * post_id is unique for a specific organization and channel
     *
     * @return \Illuminate\Validation\Rules\Unique
     */
    private function getPostUniqueRule()
    {
        $organizationId = $this->route('organization');
        $channelId = $this->input('channel_id');

        $rule = Rule::unique('posts')->where(function ($query) use ($channelId, $organizationId) {
            return $query->where(function ($query) use ($channelId, $organizationId) {
                $query->where('channel_id', $channelId)
                    ->where('organization_id', $organizationId);
            });
        });

        if ($id = $this->route('id')) {
            $rule->ignore($id);
        }

        return $rule;
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('error', 'There were errors while saving the post.');

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
