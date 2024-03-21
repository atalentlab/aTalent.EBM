<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Models\Period;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\ChannelIndex;
use App\Models\SrmIndex;
use Closure;
use Illuminate\Support\Str;

class OrganizationRepository
{
    public function getOrganizationsWithChannelIndicesForPeriod(Period $period): Collection
    {
        return Organization::whereHas('channelIndices', function ($query) use ($period) {
            $query->where('period_id', $period->id);
        })
        ->with(['channelIndices' => function ($query) use ($period) {
            $query->where('period_id', $period->id);
        }, 'channelIndices.channel'])->get();
    }

    public function getOrganizationsWithTotalFollowerCountForPeriod(Period $period, int $channelId = null, int $industryId = null, int $limit = null): Collection
    {
        $query = Organization::withCount(['channelIndices as total_follower_count'  => function (Builder $query) use ($period, $channelId, $industryId) {
            $query->where('period_id', $period->id)
                ->select(DB::raw('sum(follower_count)'));
            if ($channelId) {
                $query->where('channel_id', $channelId);
            }
            },])
            ->orderBy('total_follower_count', 'desc');

        if ($industryId) {
            $query->where('industry_id', $industryId);
        }

        if ($limit) {
            $query->take($limit);
        }

        return $query->get();
    }

    public function getTopOrganizationWithFollowerCountForPeriod(Period $period, ?int $channelId = null, ?int $industryId = null): ?Organization
    {
        return $this->getOrganizationsWithTotalFollowerCountForPeriod($period, $channelId, $industryId, 1)->first();
    }

    public function getOrganizationsWithPostCountForPeriod(Period $period, ?int $channelId = null, ?int $industryId = null, int $limit = null): Collection
    {
        $query = Organization::withCount(['channelIndices as total_post_count' => function (Builder $query) use ($period, $channelId) {
            $query->where('period_id', $period->id)
                ->select(DB::raw('sum(post_count)'));
            if ($channelId) {
                $query->where('channel_id', $channelId);
            }
            },])
            ->orderBy('total_post_count', 'desc');

        if ($industryId) {
            $query->where('industry_id', $industryId);
        }

        if ($limit) {
            $query->take($limit);
        }

        return $query->get();
    }

    public function getTopOrganizationWithPostCountForPeriod(Period $period, ?int $channelId = null, ?int $industryId = null): ?Organization
    {
        return $this->getOrganizationsWithPostCountForPeriod($period, $channelId, $industryId, 1)->first();
    }

    public function getOrganizationsWithReactionCountForPeriod(Period $period, ?int $channelId = null, ?int $industryId = null, int $limit = null): Collection
    {
        $query = Organization::withCount(['channelIndices as total_reaction_count'  => function (Builder $query) use ($period, $channelId) {
            $query->where('period_id', $period->id)
                ->select(DB::raw('sum(like_count + view_count + share_count + comment_count)'));
            if ($channelId) {
                $query->where('channel_id', $channelId);
            }
            },])
            ->orderBy('total_reaction_count', 'desc');

        if ($industryId) {
            $query->where('industry_id', $industryId);
        }

        if ($limit) {
            $query->take($limit);
        }

        return $query->get();
    }

    public function getOrganizationsListForQuery(string $query, int $limit = 30, ?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();

        $query = strtolower($query);

        return Organization::whereNotNull('name')
            //->where('published', 1)
            ->where(DB::raw('lower(name->"$.' . $locale . '")'), "LIKE", "%" . $query . "%")
            ->take($limit)
            ->select('id', 'name')
            ->orderBy('name->' . $locale)
            ->get()->toArray();
    }

    public function getOrganizationsList(array $idsToExclude = [], ?string $locale = null): Collection
    {
        $locale = $locale ?? app()->getLocale();

        $query = Organization::where('published', 1)
            ->orderBy('name->' . $locale)
            ->select(['id', 'name']);

        if (count($idsToExclude)) {
            $query->whereNotIn('id', $idsToExclude);
        }

        return $query->get();
    }

    public function getTopOrganizationWithReactionCountForPeriod(Period $period, ?int $channelId = null, ?int $industryId = null): ?Organization
    {
        return $this->getOrganizationsWithReactionCountForPeriod($period, $channelId, $industryId, 1)->first();
    }

    public function getOrganizationsWithFollowerGrowthForPeriod(Period $period, ?int $channelId = null, ?int $industryId = null, int $limit = null): Collection
    {
        if (!$previousPeriod = (new PeriodRepository())->getPreviousPeriod($period)) {
            return collect();
        }

        $currentPeriodTotalFollowerCountQuery = ChannelIndex::selectRaw('organization_id, sum(follower_count) as total_follower_count')
            ->where('period_id', $period->id)
            ->groupBy('organization_id');


        $previousPeriodTotalFollowerCountQuery = ChannelIndex::selectRaw('organization_id, sum(follower_count) as total_follower_count_previous_period')
            ->where('period_id', $previousPeriod->id)
            ->groupBy('organization_id');

        if ($channelId) {
            $currentPeriodTotalFollowerCountQuery->where('channel_id', $channelId);
            $previousPeriodTotalFollowerCountQuery->where('channel_id', $channelId);
        }

        $query = Organization::query()->fromSub($currentPeriodTotalFollowerCountQuery, 'cp')
            ->joinSub($previousPeriodTotalFollowerCountQuery, 'pp', function ($join) {
                $join->on('cp.organization_id', '=', 'pp.organization_id');
            })
            ->selectRaw('cp.total_follower_count, pp.total_follower_count_previous_period, sum(cp.total_follower_count - pp.total_follower_count_previous_period) as follower_growth, organizations.name')
            ->join('organizations', 'cp.organization_id', '=', 'organizations.id')
            ->groupBy('cp.organization_id')
            ->orderBy('follower_growth', 'desc');

        if ($industryId) {
            $query->where('industry_id', $industryId);
        }

        if ($limit) {
            $query->take($limit);
        }

        return $query->get();
    }

    public function getTopOrganizationWithFollowerGrowthForPeriod(Period $period, ?int $channelId = null, ?int $industryId = null): ?Organization
    {
        return $this->getOrganizationsWithFollowerGrowthForPeriod($period, $channelId, $industryId, 1)->first();
    }

    public function getOrganizationsWithPostCountGrowthForPeriod(Period $period, ?int $channelId = null, ?int $industryId = null, int $limit = null): Collection
    {
        if (!$previousPeriod = (new PeriodRepository())->getPreviousPeriod($period)) {
            return collect();
        }

        $currentPeriodTotalPostCountQuery = ChannelIndex::selectRaw('organization_id, sum(post_count) as total_post_count')
            ->where('period_id', $period->id)
            ->groupBy('organization_id');


        $previousPeriodTotalPostCountQuery = ChannelIndex::selectRaw('organization_id, sum(post_count) as total_post_count_previous_period')
            ->where('period_id', $previousPeriod->id)
            ->groupBy('organization_id');

        if ($channelId) {
            $currentPeriodTotalPostCountQuery->where('channel_id', $channelId);
            $previousPeriodTotalPostCountQuery->where('channel_id', $channelId);
        }

        $query = Organization::query()->fromSub($currentPeriodTotalPostCountQuery, 'cp')
            ->joinSub($previousPeriodTotalPostCountQuery, 'pp', function ($join) {
                $join->on('cp.organization_id', '=', 'pp.organization_id');
            })
            ->selectRaw('cp.total_post_count, pp.total_post_count_previous_period, sum(cp.total_post_count - pp.total_post_count_previous_period) as post_count_growth, organizations.name')
            ->join('organizations', 'cp.organization_id', '=', 'organizations.id')
            ->groupBy('cp.organization_id')
            ->orderBy('post_count_growth', 'desc');

        if ($industryId) {
            $query->where('industry_id', $industryId);
        }

        if ($limit) {
            $query->take($limit);
        }

        return $query->get();
    }

    public function getTopOrganizationWithPostCountGrowthForPeriod(Period $period, ?int $channelId = null, ?int $industryId = null): ?Organization
    {
        return $this->getOrganizationsWithPostCountGrowthForPeriod($period, $channelId, $industryId, 1)->first();
    }

    public function getOrganizationsWithReactionCountGrowthForPeriod(Period $period, ?int $channelId = null, ?int $industryId = null, int $limit = null): Collection
    {
        if (!$previousPeriod = (new PeriodRepository())->getPreviousPeriod($period)) {
            return collect();
        }

        $currentPeriodTotalReactionCountQuery = ChannelIndex::selectRaw('organization_id, sum(like_count + view_count + share_count + comment_count) as total_reaction_count')
            ->where('period_id', $period->id)
            ->groupBy('organization_id');


        $previousPeriodTotalReactionCountQuery = ChannelIndex::selectRaw('organization_id, sum(like_count + view_count + share_count + comment_count) as total_reaction_count_previous_period')
            ->where('period_id', $previousPeriod->id)
            ->groupBy('organization_id');

        if ($channelId) {
            $currentPeriodTotalReactionCountQuery->where('channel_id', $channelId);
            $previousPeriodTotalReactionCountQuery->where('channel_id', $channelId);
        }

        $query = Organization::query()->fromSub($currentPeriodTotalReactionCountQuery, 'cp')
            ->joinSub($previousPeriodTotalReactionCountQuery, 'pp', function ($join) {
                $join->on('cp.organization_id', '=', 'pp.organization_id');
            })
            ->selectRaw('cp.total_reaction_count, pp.total_reaction_count_previous_period, sum(cp.total_reaction_count - pp.total_reaction_count_previous_period) as reaction_count_growth, organizations.name')
            ->join('organizations', 'cp.organization_id', '=', 'organizations.id')
            ->groupBy('cp.organization_id')
            ->orderBy('reaction_count_growth', 'desc');

        if ($industryId) {
            $query->where('industry_id', $industryId);
        }

        if ($limit) {
            $query->take($limit);
        }

        return $query->get();
    }

    public function getTopOrganizationWithReactionCountGrowthForPeriod(Period $period, ?int $channelId = null, ?int $industryId = null): ?Organization
    {
        return $this->getOrganizationsWithReactionCountGrowthForPeriod($period, $channelId, $industryId, 1)->first();
    }

    public function getAbsoluteRankShiftForPeriod(Period $period, int $organizationId, int $channelId = null): int
    {
        if (!$previousPeriod = (new PeriodRepository())->getPreviousPeriod($period)) {
            return 0;
        }

        $currentRank = $this->getRankForPeriod($period, $organizationId, $channelId);

        $previousRank = $this->getRankForPeriod($previousPeriod, $organizationId, $channelId);

        if ($currentRank > 0 && $previousRank > 0) {
            return $previousRank - $currentRank;
        }

        return 0;
    }

    public function getRankForPeriod(Period $period, int $organizationId, int $channelId = null): int
    {
        $model = new SrmIndex();

        if ($channelId) {
            $model = new ChannelIndex();
        }

/*
        $organizationIndex = $model::query()
            ->join('periods', 'srm_indices.period_id', '=', 'periods.id')
            ->where('organization_id', $organizationId)
            ->orderBy('periods.start_date', 'desc')
            ->first();*/


        if ($organizationIndex = $model::query()
            ->where('period_id', $period->id)
            ->where('organization_id', $organizationId)
            ->first()) {
            return $model::query()
                ->orderBy('composite', 'desc')
                ->where('period_id', $period->id)
                ->where('composite', '>=', $organizationIndex->composite)
                ->count();
        }

        return 0;
    }

    public function getOrganizationsWithAbsoluteIndexShiftForPeriod(Period $period, int $channelId = null, int $industryId = null, int $limit = null): Collection
    {
        if (!$previousPeriod = (new PeriodRepository())->getPreviousPeriod($period)) {
            return collect();
        }

        $model = new SrmIndex();

        if ($channelId) {
            $model = new ChannelIndex();
        }

        $currentPeriodQuery = $model->newQuery()->selectRaw('organization_id, composite as composite_current_period')
            ->where('period_id', $period->id)
            ->groupBy('organization_id', 'composite');


        $previousPeriodQuery = $model->newQuery()->selectRaw('organization_id, composite as composite_previous_period')
            ->where('period_id', $previousPeriod->id)
            ->groupBy('organization_id', 'composite');

        if ($channelId) {
            $currentPeriodQuery->where('channel_id', $channelId);
            $previousPeriodQuery->where('channel_id', $channelId);
        }

        $query = Organization::query()->fromSub($currentPeriodQuery, 'cp')
            ->joinSub($previousPeriodQuery, 'pp', function ($join) {
                $join->on('cp.organization_id', '=', 'pp.organization_id');
            })
            ->selectRaw('cp.composite_current_period, pp.composite_previous_period, sum(cp.composite_current_period - pp.composite_previous_period) as composite_shift_absolute, organizations.name')
            ->join('organizations', 'cp.organization_id', '=', 'organizations.id')
            ->groupBy('cp.organization_id', 'composite_current_period', 'composite_previous_period')
            ->orderBy('composite_shift_absolute', 'desc');

        if ($industryId) {
            $query->where('industry_id', $industryId);
        }

        if ($limit) {
            $query->take($limit);
        }

        return $query->get();
    }

    public function getTopOrganizationWithAbsoluteIndexShiftForPeriod(Period $period, ?int $channelId = null, ?int $industryId = null): ?Organization
    {
        return $this->getOrganizationsWithAbsoluteIndexShiftForPeriod($period, $channelId, $industryId, 1)->first();
    }

    public function getOrganizationWithCompetitorsWithCountsForPastPeriods(Period $period, Organization $organization, ?int $channelId = null, int $numberOfPeriods = 5, ?Closure $closure = null): Collection
    {
        $numberOfPeriods += 1;

        $organizations = $organization->competitors->prepend($organization);

        $periods = Period::whereDate('end_date', '<=', $period->start_date)
            ->whereHas('channelIndices')
            ->orWhere('id', $period->id)
            ->orderBy('end_date', 'desc')
            ->take($numberOfPeriods)
            ->get();

        $data = $this->getOrganizationsWithCountsForPeriods($periods->pluck('id')->toArray(), $organizations->pluck('id')->toArray(), $channelId);

        if (!$closure) {
            return $data;
        }

        $periods = $periods->sortBy('end_date');

        $outputData = collect([
            'series' => [],
            'labels' => [],
            'title' => '',
        ]);

        $combinedSeries = [];

        foreach ($organizations as $organization) {

            $previousPeriod = null;

            $series = [];

            foreach($periods as $period) {
                $stats = $data->where('organization_id', $organization->id)->where('period_id', $period->id)->first();

                if ($previousPeriod) {
                    $previousStats = $data->where('organization_id', $organization->id)->where('period_id', $previousPeriod->id)->first();

                    if ($stats && $previousStats) {
                        // calculate here
                        $series[] = $closure($stats, $previousStats);
                    }
                    else {
                        $series[] = null;
                    }
                }

                $previousPeriod = $period;
            }

            $combinedSeries[] = [
                'name' => $organization->name,
                'data' => $series,
            ];
        }

        $outputData['series'] = $combinedSeries;

        $periods->shift();

        $outputData['labels'] = $periods->pluck('name_with_year')->toArray();

        return $outputData;
    }

    public function getOrganizationAndCompetitorWithCountsAndProgressionForPeriod(Period $period, Organization $organization, ?Organization $competitor = null): array
    {
        $data = [
            'headers' => [__('email.reports.competitor.data.metric')],
            'post_count' => [__('email.reports.competitor.data.posts')],
            'view_count' => [__('email.reports.competitor.data.views')],
            'like_count' => [__('email.reports.competitor.data.likes')],
            'comment_count' => [__('email.reports.competitor.data.comments')],
            'share_count' => [__('email.reports.competitor.data.shares')],
            'follower_count' => [__('email.reports.competitor.data.followers')],
        ];

        $competitorId = optional($competitor)->id ?? null;

        if (!$previousPeriod = (new PeriodRepository())->getPreviousPeriodForOrganization($period, $organization->id)) {
            return [];
        }

        $totalCounts = $this->getOrganizationsWithCountsBetweenPeriods($previousPeriod, $period, [$organization->id, $competitorId]);
        $totalCounts = $totalCounts->sortBy(function($item) use ($organization) { return $item->id !== $organization->id; });

        $previousPeriodTotalCounts = null;

        // get the difference with the previous week's progression
        if ($previousPreviousPeriod = (new PeriodRepository())->getPreviousPeriodForOrganization($previousPeriod, $organization->id)) {
            $previousPeriodTotalCounts = $this->getOrganizationsWithCountsBetweenPeriods($previousPreviousPeriod, $previousPeriod, [$organization->id, $competitorId]);
        }

        foreach($totalCounts as $totalCount) {
            $data['headers'][] = Str::limit($totalCount->name, 12);
            $data['headers'][] = __('email.reports.competitor.data.progress');

            $previousPeriodTotalCount = $previousPeriodTotalCounts ? $previousPeriodTotalCounts->where('id', $totalCount->id)->first() : null;

            $data['post_count'][] = number_format($totalCount->post_count_difference);
            $data['post_count'][] = $previousPeriodTotalCount ? number_format($totalCount->post_count_difference - $previousPeriodTotalCount->post_count_difference) : __('email.reports.competitor.data.na');

            $data['view_count'][] = number_format($totalCount->view_count_difference);
            $data['view_count'][] = $previousPeriodTotalCount ? number_format($totalCount->view_count_difference - $previousPeriodTotalCount->view_count_difference) : __('email.reports.competitor.data.na');

            $data['like_count'][] = number_format($totalCount->like_count_difference);
            $data['like_count'][] = $previousPeriodTotalCount ? number_format($totalCount->like_count_difference - $previousPeriodTotalCount->like_count_difference) : __('email.reports.competitor.data.na');

            $data['comment_count'][] = number_format($totalCount->comment_count_difference);
            $data['comment_count'][] = $previousPeriodTotalCount ? number_format($totalCount->comment_count_difference - $previousPeriodTotalCount->comment_count_difference) : __('email.reports.competitor.data.na');


            $data['share_count'][] = number_format($totalCount->share_count_difference);
            $data['share_count'][] = $previousPeriodTotalCount ? number_format($totalCount->share_count_difference - $previousPeriodTotalCount->share_count_difference) : __('email.reports.competitor.data.na');

            $data['follower_count'][] = number_format($totalCount->total_follower_count);
            $data['follower_count'][] = $previousPeriodTotalCount ? number_format($totalCount->total_follower_count - $previousPeriodTotalCount->total_follower_count) : __('email.reports.competitor.data.na');
        }

        return $data;
    }

    public function getOrganizationWithCountsForPastPeriods(Period $period, Organization $organization, ?int $channelId = null, int $numberOfPeriods = 5): Collection
    {
        $periods = Period::whereDate('end_date', '<=', $period->start_date)
            ->whereHas('channelIndices')
            ->orWhere('id', $period->id)
            ->orderBy('end_date', 'desc')
            ->take($numberOfPeriods)
            ->get();

        $data = $this->getOrganizationsWithCountsForPeriods($periods->pluck('id')->toArray(), [$organization->id], $channelId);

        $periods = $periods->sortBy('end_date');

        $previousPeriod = null;

        $combinedStats = collect();

        foreach($periods as $period) {
            $stats = $data->where('period_id', $period->id)->first();

            if ($stats && $previousPeriod) {
                $previousStats = $data->where('period_id', $previousPeriod->id)->first();

                if ($previousStats) {
                    $combinedStats->push([
                        'period_id' => $period->id,
                        'period_name' => $period->name,
                        'period_name_with_year' => $period->name_with_year,
                        'post_count_difference' => $stats->total_post_count - $previousStats->total_post_count,
                        'view_count_difference' => $stats->total_view_count - $previousStats->total_view_count,
                        'like_count_difference' => $stats->total_like_count - $previousStats->total_like_count,
                        'comment_count_difference' => $stats->total_comment_count - $previousStats->total_comment_count,
                        'share_count_difference' => $stats->total_share_count - $previousStats->total_share_count,
                        'total_follower_count' => (int) $stats->total_follower_count,
                        'follower_count_difference' => $stats->total_follower_count - $previousStats->total_follower_count,
                    ]);
                }
            }

            $previousPeriod = $period;
        }

        return $combinedStats;

    }

    public function getOrganizationsWithCountsForPeriods(array $periodIds, array $organizationIds, ?int $channelId = null): Collection
    {
        $query = ChannelIndex::selectRaw('organization_id, period_id, sum(post_count) as total_post_count, sum(like_count) as total_like_count, sum(view_count) as total_view_count, sum(comment_count) as total_comment_count, sum(share_count) as total_share_count, sum(follower_count) as total_follower_count')
            ->whereIn('period_id', $periodIds)
            ->whereIn('organization_id', $organizationIds)
            ->groupBy('organization_id', 'period_id')
            ->orderBy('period_id');

        if ($channelId) {
            $query->where('channel_id', $channelId);
        }

        return $query->get();
    }

    public function getOrganizationWithCompetitorsWithCountsBetweenPeriods(Period $startPeriod, Period $endPeriod, Organization $organization, ?int $channelId = null): Collection
    {
        $organizationIds = array_merge([$organization->id], $organization->competitors->pluck('id')->toArray());

        $data = $this->getOrganizationsWithCountsBetweenPeriods($startPeriod, $endPeriod, $organizationIds, $channelId);

        return $data->sortBy(function ($val) use ($organization) { return $val['id'] !== $organization->id; });
    }

    public function getOrganizationsWithCountsBetweenPeriods(Period $startPeriod, Period $endPeriod, array $organizationIds = [], ?int $channelId = null, int $limit = null): Collection
    {
        $startPeriodTotalCountQuery = ChannelIndex::selectRaw('organization_id, sum(post_count) as total_post_count, sum(like_count) as total_like_count, sum(view_count) as total_view_count, sum(comment_count) as total_comment_count, sum(share_count) as total_share_count, sum(follower_count) as total_follower_count')
            ->where('period_id', $startPeriod->id)
            ->groupBy('organization_id');


        $endPeriodTotalCountQuery = ChannelIndex::selectRaw('organization_id, sum(post_count) as total_post_count, sum(like_count) as total_like_count, sum(view_count) as total_view_count, sum(comment_count) as total_comment_count, sum(share_count) as total_share_count, sum(follower_count) as total_follower_count')
            ->where('period_id', $endPeriod->id)
            ->groupBy('organization_id');

        if ($channelId) {
            $startPeriodTotalCountQuery->where('channel_id', $channelId);
            $endPeriodTotalCountQuery->where('channel_id', $channelId);
        }

        $query = Organization::query()->fromSub($endPeriodTotalCountQuery, 'ep')
            ->joinSub($startPeriodTotalCountQuery, 'sp', function ($join) {
                $join->on('ep.organization_id', '=', 'sp.organization_id');
            })
            ->select([
                'organizations.id',
                'organizations.name',
                DB::raw('ep.total_post_count'),
                DB::raw('ep.total_like_count'),
                DB::raw('ep.total_view_count'),
                DB::raw('ep.total_comment_count'),
                DB::raw('ep.total_share_count'),
                DB::raw('ep.total_follower_count'),

/*                DB::raw('sp.total_post_count'),
                DB::raw('sp.total_like_count'),
                DB::raw('sp.total_view_count'),
                DB::raw('sp.total_comment_count'),
                DB::raw('sp.total_share_count'),
                DB::raw('sp.total_follower_count'),*/

                DB::raw('sum(ep.total_post_count - sp.total_post_count) as post_count_difference'),
                DB::raw('sum(ep.total_like_count - sp.total_like_count) as like_count_difference'),
                DB::raw('sum(ep.total_view_count - sp.total_view_count) as view_count_difference'),
                DB::raw('sum(ep.total_comment_count - sp.total_comment_count) as comment_count_difference'),
                DB::raw('sum(ep.total_share_count - sp.total_share_count) as share_count_difference'),
                DB::raw('sum(ep.total_follower_count - sp.total_follower_count) as follower_count_difference')
            ])
            ->join('organizations', 'ep.organization_id', '=', 'organizations.id')
            ->groupBy('ep.organization_id')
            ->orderBy('post_count_difference', 'desc');

        if (count($organizationIds)) {
            $query->whereIn('organizations.id', $organizationIds);
        }

        if ($limit) {
            $query->take($limit);
        }

        return $query->get();
    }
}
