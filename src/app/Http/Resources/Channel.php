<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Channel extends JsonResource
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
            'published'                 => $this->published,
            'name'                      => $this->name,
            'organization_url_prefix'   => $this->organization_url_prefix,
            'organization_url_suffix'   => $this->organization_url_suffix,
            'post_max_fetch_age'        => $this->post_max_fetch_age,
        ];
    }
}
