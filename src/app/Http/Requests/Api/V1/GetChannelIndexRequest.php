<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class GetChannelIndexRequest extends FormRequest
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
            'period_id'             => 'nullable|integer|exists:periods,id',
        ];
    }
}
