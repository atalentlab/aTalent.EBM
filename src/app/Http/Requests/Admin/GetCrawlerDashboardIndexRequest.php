<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GetCrawlerDashboardIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('admin')->user()->hasPermissionTo('view crawler dashboard');
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
        ];
    }

    public function wantsJson()
    {
        return true;
    }
}
