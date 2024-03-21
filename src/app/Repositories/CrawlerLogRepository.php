<?php

namespace App\Repositories;

use App\Models\CrawlerLog;
use App\Models\Channel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CrawlerLogRepository
{
    public function getCrawlerStatsPerChannelForCurrentPeriod(string $channelId = null): array
    {
        $currentPeriodId = (new PeriodRepository())->getCurrentPeriod()->id;

        return $this->getCrawlerStatsPerChannelForPeriod($currentPeriodId, $channelId);
    }

    public function getCrawlerStatsPerChannelForPeriod(string $periodId, string $channelId = null): array
    {
        $period = (new PeriodRepository())->getPeriodById($periodId);

        $channels = Channel::orderBy('order', 'asc')
            ->withCount(['organizations as success_crawled_count'  => function (Builder $query) use ($period) {
                $query->whereHas('crawlerLogs', function (Builder $query) use ($period) {
                    $query->where('status', 'success')
                        ->where('period_id', $period->id)
                        ->where('channel_id', '=', DB::raw('channels.id'));
                })->where('is_fetching', true);
            },
                'organizations as total_count' => function(Builder $query) use ($period) {
                    $query->where('is_fetching', true)
                        ->whereDate('created_at', '<', $period->end_date);
                },
                'crawlerLogs as error_count' => function(Builder $query) use ($period) {
                    $query->where('status', 'error')
                        ->where('period_id', $period->id)
                        ->where('channel_id', '=', DB::raw('channels.id'));
                },
                'posts as posts_count' => function(Builder $query) use ($period) {
                    $query->whereDate('posted_date', '<=', $period->end_date)
                        ->whereDate('posted_date', '>=', $period->start_date);
                },])
            ->with(['crawlerLogs' => function($query) use ($period) {
                $query->where('period_id', $period->id)
                    ->orderBy('updated_at', 'desc');
            }]);

        if ($channelId) {
            $channels->where('id', $channelId);
        }

        $channels = $channels->get();

        $channelsData = [];

        foreach($channels as $channel) {

            $crawledCompletedPercentage = $channel->total_count > 0 ? round(($channel->success_crawled_count / $channel->total_count) * 100, 2) : 0;
            $channelsData[$channel->id] = [
                'name' => $channel->name,
                'success_crawled_count' => $channel->success_crawled_count,
                'total_count' => $channel->total_count,
                'crawled_completed_percentage' => $crawledCompletedPercentage,
                'color' => $crawledCompletedPercentage <= 30 ? 'red' : ($crawledCompletedPercentage <= 75 ? 'yellow' : 'green'),
                'error_count' => $channel->error_count,
                'posts_count' => $channel->posts_count,
                'last_crawled_at' => $channel->crawlerLogs->first() ? $channel->crawlerLogs->first()->created_at->format('Y-m-d H:i:s') : 'N/A',
            ];
        }

        return $channelsData;
    }

    public function getErrorsForCurrentPeriod(string $channelId = null): Collection
    {
        $currentPeriodId = (new PeriodRepository())->getCurrentPeriod()->id;

        return $this->getErrorsForPeriod($currentPeriodId, $channelId);
    }

    public function getErrorsForPeriod(string $periodId, string $channelId = null): Collection
    {
        $logs = CrawlerLog::where('period_id', $periodId)
            ->where('status', 'error')
            ->orderBy('updated_at', 'desc')
            ->with(['channel', 'organization']);

        if ($channelId) {
            $logs->where('channel_id', $channelId);
        }

        return $logs->get();
    }
}
