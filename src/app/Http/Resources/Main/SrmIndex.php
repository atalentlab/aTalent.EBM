<?php

namespace App\Http\Resources\Main;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SrmIndex extends JsonResource
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
            'activity'          => [
                'total' => $this->activity,
                'channels' => $this->channelIndices->mapWithKeys(function ($channelIndex) {
                    return [$channelIndex->channel->id => $channelIndex->activity];
                }),
            ],
            'engagement'        => [
                'total' => $this->engagement,
                'channels' => $this->channelIndices->mapWithKeys(function ($channelIndex) {
                    return [$channelIndex->channel->id => $channelIndex->engagement];
                }),
            ],
            'popularity'        => [
                'total' => $this->popularity,
                'channels' => $this->channelIndices->mapWithKeys(function ($channelIndex) {
                    return [$channelIndex->channel->id => $channelIndex->popularity];
                }),
            ],
        ];
    }
}
