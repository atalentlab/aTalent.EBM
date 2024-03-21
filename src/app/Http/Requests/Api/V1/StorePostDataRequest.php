<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StorePostDataRequest extends FormRequest
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
            'period_id'         => 'required|integer|exists:periods,id',
            'like_count'        => 'nullable|integer',
            'comment_count'     => 'nullable|integer',
            'share_count'       => 'nullable|integer',
            'view_count'        => 'nullable|integer',
        ];
    }
}
