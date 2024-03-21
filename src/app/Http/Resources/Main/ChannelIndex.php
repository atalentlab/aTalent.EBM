<?php

namespace App\Http\Resources\Main;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'organization'      => $this->organization->name,
            'logo'              => Storage::url($this->organization->logo),
            'composite'         => $this->composite,
            'composite_shift'   => $this->composite_shift,
            'activity'          => $this->activity,
            'popularity'        => $this->popularity,
            'engagement'        => $this->engagement,
        ];
    }
}
