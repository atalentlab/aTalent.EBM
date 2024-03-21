<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class GetPostRequest extends FormRequest
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
            'channel_id'            => 'nullable|integer|exists:channels,id',
            'organization_id'       => 'nullable|integer|exists:organizations,id',
            'post_id'               => 'nullable|string|max:400',
            'is_actively_fetching'  => 'boolean',
            'max_age'               => 'nullable|integer|min:1|max:9999',
        ];
    }
}
