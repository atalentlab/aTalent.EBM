<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationDataRequest extends FormRequest
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
        $rules = [
            'period_id'         => 'integer|exists:periods,id',
            'channel_id'        => 'integer|exists:channels,id',
        ];

        if ($this->routeIs('api.*.post')) {
            $rules['period_id'] = 'required|' . $rules['period_id'];
            $rules['channel_id'] = 'required|' . $rules['channel_id'];
            $rules['follower_count'] = 'required|integer';
        } else {
            $rules['period_id'] = 'nullable|' . $rules['period_id'];
            $rules['channel_id'] = 'nullable|' . $rules['channel_id'];
        }

        return $rules;
    }
}
