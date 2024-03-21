<?php

namespace App\Repositories;

use App\Models\Period;
use App\Models\Post;
use App\Models\PostData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PostRepository
{
    public function getTopPostsByMetricForPeriod(Period $period, string $metric, ?int $channelId = null, ?int $industryId = null, int $limit = 5): Collection
    {
        $query = DB::table('post_data as pd')
            ->select('pd.*', 'p.*', 'o.name')
            ->join('posts as p', 'pd.post_id', '=', 'p.id')
            ->join('organizations as o', 'p.organization_id', '=', 'o.id')
            ->where('p.posted_date', '<=', $period->end_date)
            ->where('p.posted_date', '>=', $period->start_date)
            ->where('pd.id', function($query) {
                $query->select('id')
                    ->from('post_data')
                    ->whereRaw('`post_id` = `p`.`id`')
                    ->orderBy('period_id', 'desc')
                    ->take(1);
            })
            ->orderBy('pd.' . $metric, 'desc')
            ->take($limit);

        if ($channelId) {
            $query->where('p.channel_id', $channelId);
        }

        if ($industryId) {
            $query->where('o.industry_id', $industryId);
        }

        //$sql = str_replace_array('?', $query->getBindings(), $query->toSql());
        //\Log::info($sql);

        return $query->get();
    }
}
