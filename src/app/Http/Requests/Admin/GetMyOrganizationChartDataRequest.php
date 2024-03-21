<?php

namespace App\Http\Requests\Admin;

use App\Enums\Metric;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GetMyOrganizationChartDataRequest extends FormRequest
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
            'period_id'     => 'required|integer|exists:periods,id',
            'channel_id'    => 'nullable|integer|exists:channels,id',
            'metric'        => 'required|string|in:' . implode(',', Metric::getKeys()),
        ];
    }

    public function wantsJson()
    {
        return true;
    }
}
