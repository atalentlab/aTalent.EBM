<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Organization extends JsonResource
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
            'id'        => $this->id,
            'name'      => $this->name,
            'channels'  => $this->channels->map(function ($channel) {
                return [
                    'id'        => $channel->id,
                    'name'      => $channel->name,
                    'username'  => $channel->pivot->channel_username,
                ];
            }),
        ];
    }
}
