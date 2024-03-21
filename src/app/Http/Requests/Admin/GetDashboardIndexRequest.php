<?php

namespace App\Http\Requests\Admin;

use App\Enums\TopPerformingContentMetric;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GetDashboardIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('admin')->user()->hasPermissionTo('view statistics dashboard');
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
            'industry_id'    => 'nullable|integer|exists:industries,id',
            'top_performing_content_metric' => 'nullable|string|in:' . implode(',', TopPerformingContentMetric::getKeys()),
        ];
    }

    public function wantsJson()
    {
        return true;
    }
}
