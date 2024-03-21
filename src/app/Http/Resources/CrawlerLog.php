<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CrawlerLog extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                        => $this->id,
            'organization_id'           => $this->organization_id,
            'channel_id'                => $this->channel_id,
            'period_id'                 => $this->period_id,
            'api_user_id'               => $this->api_user_id,
            'status'                    => $this->status,
            'posts_crawled_count'       => $this->posts_crawled_count,
            'is_organization_data_sent' => $this->is_organization_data_sent,
            'message'                   => $this->message,
            'crawler_ip'                => $this->crawler_ip,
            'created_at'                => $this->created_at,
            'updated_at'                => $this->updated_at,
        ];
    }
}
