<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
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
            'id'                    => $this->id,
            'title'                 => $this->title,
            'organization_id'       => $this->organization_id,
            'channel_id'            => $this->channel_id,
            'post_id'               => $this->post_id,
            'posted_date'           => $this->posted_date,
            'is_actively_fetching'  => $this->is_actively_fetching,
            'url'                   => $this->url,
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
        ];
    }
}
