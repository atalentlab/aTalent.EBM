<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\CrawlerLogStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCrawlerLogRequest extends FormRequest
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
            'organization_id' => [
                'required',
                'integer',
                Rule::exists('organizations', 'id')->where(function ($query) {
                    $query->where('is_fetching', true);
                }),
            ],
            'channel_id'                    => 'required|integer|exists:channels,id',
            'period_id'                     => 'required|integer|exists:periods,id',
            'is_organization_data_sent'     => 'boolean',
            'status'                        => 'required|string|in:' . implode(',', CrawlerLogStatus::getKeys()),
            'posts_crawled_count'           => 'nullable|integer|min:0',
            'message'                       => 'nullable|string|max:2000',
        ];
    }
}

