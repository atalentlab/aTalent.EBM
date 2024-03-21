<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
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
            'organization_id' => [
                'required',
                'integer',
                Rule::exists('organizations', 'id')->where(function ($query) {
                    $query->where('is_fetching', true);
                }),
            ],
            'channel_id'            => 'required|integer|exists:channels,id',
            'post_id'               => 'required|string|max:400',
            'is_actively_fetching'  => 'boolean',
            'title'                 => 'nullable|string|max:255',
            'posted_date'           => 'required|date',
            'url'                   => 'nullable|url|max:2048',
        ];
    }
}
