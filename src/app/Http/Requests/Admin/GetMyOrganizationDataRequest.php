<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GetMyOrganizationDataRequest extends FormRequest
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
            'competitor_id' => 'nullable|integer|exists:organizations,id',
        ];
    }

    public function wantsJson()
    {
        return true;
    }
}
