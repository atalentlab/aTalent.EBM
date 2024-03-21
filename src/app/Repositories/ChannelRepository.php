<?php

namespace App\Repositories;

use App\Models\Channel;
use App\Models\Period;

class ChannelRepository
{
    public function getChannelsWithDataForPeriod(Period $period)
    {
        return Channel::whereHas('organizations.organizationData')
        ->with([
        'organizations' => function ($query) use ($period) {
            $query->whereHas('organizationData');
        },
/*        'organizations.organizationData' => function ($query) use ($period) {
            //$query->where('period_id', $period->id);
            $query->whereHas('period', function ($query) use ($period) {
                $query->whereDate('start_date', '<', $period->end_date)
                    ->orderBy('start_date', 'desc');
            });
        },*/
        'organizations.posts' => function ($query) use ($period) {
            $query->whereDate('posted_date', '<=', $period->end_date);
        },
/*        'organizations.posts.postData' => function ($query) use ($period) {
            //$query->where('period_id', $period->id);
            $query->whereHas('period', function ($query) use ($period) {
                $query->whereDate('start_date', '<', $period->end_date)
                    ->orderBy('start_date', 'desc')
                    ->take(1);
            });
        },*/
        ])->get();
    }

    public function getChannelsWithIndicesForPeriod(Period $period)
    {
        return Channel::whereHas('channelIndices', function ($query) use ($period) {
            $query->where('period_id', $period->id);
        })
        ->with(['channelIndices' => function ($query) use ($period) {
            $query->where('period_id', $period->id);
        }])->get();
    }

    public function getActiveChannelsTotalWeightForPeriod(Period $period)
    {
        return Channel::whereHas('channelIndices', function ($query) use ($period) {
            $query->where('period_id', $period->id);
        })->sum('ranking_weight');
    }

    public function getPublishedChannels()
    {
        $locale = app()->getLocale();
        return Channel::where('published', 1)->orderBy('name->'.$locale)->orderBy('order')->get();
    }

    public function getPublishedChannelsNoSort()
    {
        return Channel::where('published', 1)->get();
    }

    public function getChannelsList()
    {
        return Channel::orderBy('order')->pluck('name', 'id');
    }

    public function getChannels()
    {
        return Channel::orderBy('order')->select('name', 'id')->get();
    }
}
