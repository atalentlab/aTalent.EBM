<?php

namespace App\Repositories;

use App\Models\Period;
use App\Models\ChannelIndex;
use App\Models\SrmIndex;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Organization;

class IndexRepository
{
    /**
     * Get the amount of posts vs amount of reactions ratio for all organizations for a given period
     *
     * @param Period $period
     * @param int|null $channelId
     * @param int|null $industryId
     * @return mixed
     */
    public function getPostReactionsRatioForPeriod(Period $period, ?int $channelId = null, ?int $industryId = null)
    {
        $query = ChannelIndex::where('period_id', $period->id);

        if ($channelId) {
            $query->where('channel_id', $channelId);
        }

        if ($industryId) {
            $query->whereHas('organization', function ($q) use ($industryId) {
                $q->where('industry_id', $industryId);
            });
        }

        return $query->value(DB::raw('sum(like_count + view_count + share_count + comment_count) / sum(post_count)'));


 /*       ->select([
        DB::raw('sum(like_count + view_count + share_count + comment_count) as total_reaction_count'),
        DB::raw('sum(post_count) as total_post_count'),
        DB::raw('sum(like_count + view_count + share_count + comment_count) / sum(post_count) as ratio')
        ])*/
    }


    /**
     * Get the top index with organization for a given period
     *
     * @param Period $period
     * @param int|null $channelId
     * @param int|null $industryId
     * @return mixed
     */
    public function getTopIndexWithOrganizationForPeriod(Period $period, ?int $channelId = null, ?int $industryId = null)
    {
        if ($channelId) {
            $query = ChannelIndex::where('period_id', $period->id)
                ->where('channel_id', $channelId)
                ->orderBy('composite', 'desc')
                ->with('organization');
        }
        else {
            $query = SrmIndex::where('period_id', $period->id)
                ->orderBy('composite', 'desc')
                ->with('organization');
        }

        if ($industryId) {
            $query->whereHas('organization', function ($q) use ($industryId) {
                $q->where('industry_id', $industryId);
            });
        }

        return $query->take(1)->first();
    }

    /**
     * Get the top index shift with organization for a given period
     *
     * @param Period $period
     * @param int|null $channelId
     * @param int|null $industryId
     * @return mixed
     */
    public function getTopIndexShiftWithOrganizationForPeriod(Period $period, ?int $channelId = null, ?int $industryId = null)
    {
        if ($channelId) {
            $query = ChannelIndex::where('period_id', $period->id)
                ->where('channel_id', $channelId)
                ->orderBy('composite_shift', 'desc')
                ->with('organization');
        }
        else {
            $query = SrmIndex::where('period_id', $period->id)
                ->orderBy('composite_shift', 'desc')
                ->with('organization');
        }

        if ($industryId) {
            $query->whereHas('organization', function ($q) use ($industryId) {
                $q->where('industry_id', $industryId);
            });
        }

        return $query->take(1)->first();
    }
}
