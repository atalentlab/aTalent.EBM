<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChannelIndex extends JsonResource
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
            'id'                => $this->id,
            'channel_id'        => $this->channel_id,
            'period_id'         => $this->period_id,
            'organization_id'   => $this->organization_id,
            'follower_count'    => $this->follower_count,
            'post_count'        => $this->post_count,
            'like_count'        => $this->like_count,
            'comment_count'     => $this->comment_count,
            'share_count'       => $this->share_count,
            'view_count'        => $this->view_count,
        ];
    }
}
