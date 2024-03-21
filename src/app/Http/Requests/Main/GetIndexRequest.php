<?php

namespace App\Http\Requests\Main;

use Illuminate\Foundation\Http\FormRequest;

class GetIndexRequest extends FormRequest
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
            'period_id'             => 'required|integer|exists:periods,id',
            'channel_id'            => 'nullable|integer|exists:channels,id',
            'industry_id'           => 'nullable|integer|exists:industries,id',
            'organization'          => 'nullable|string|min:2|max:100',
            'order_by'              => 'nullable|string|in:composite,activity,popularity,engagement,composite_shift',
            'order_by_direction'    => 'nullable|string|in:asc,desc',
        ];
    }

    public function wantsJson()
    {
        return true;
    }
}
