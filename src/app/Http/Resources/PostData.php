<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostData extends JsonResource
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
            'post_id'           => $this->post_id,
            'period_id'         => $this->period_id,
            'like_count'        => $this->like_count,
            'comment_count'     => $this->comment_count,
            'share_count'       => $this->share_count,
            'view_count'        => $this->view_count,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
