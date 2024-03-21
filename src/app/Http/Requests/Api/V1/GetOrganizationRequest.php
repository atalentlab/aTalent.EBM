<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class GetOrganizationRequest extends FormRequest
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
            'period_id'                                     => 'nullable|integer|exists:periods,id',
            'channel_id'                                    => 'nullable|required_if:has_not_been_crawled_during_current_period,1|required_if:has_crawler_errors_for_current_period,1|integer|exists:channels,id',
            'has_not_been_crawled_during_current_period'    => 'nullable|boolean',
            'has_crawler_errors_for_current_period'         => 'nullable|boolean',
        ];
    }
}
