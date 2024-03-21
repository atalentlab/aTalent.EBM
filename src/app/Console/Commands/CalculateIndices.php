<?php

namespace App\Console\Commands;

use App\Models\ChannelIndex;
use App\Models\Period;
use App\Models\SrmIndex;
use App\Repositories\ChannelRepository;
use App\Repositories\OrganizationRepository;
use App\Repositories\PeriodRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CalculateIndices extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'srm:calculate-indices {--period= : Pass \'all\' to generate the indices for all periods. Pass a specific period_id to generate the indices for that period. If no value is passed, indices will be generated for the current period.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate indices for the current period';

    protected $periodRepository;

    protected $channelRepository;

    protected $organizationRepository;

    protected $config;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->init();

        Validator::make(['period' => $this->option('period')], [
            'period' => 'nullable|string',
        ])->validate();

        if ($this->option('period')) {
            if ($this->option('period') === 'all') {
                $periods = $this->periodRepository->getAllPresentPeriodsWithData();
            }
            elseif ($period = $this->periodRepository->getPeriodById($this->option('period'))) {
                $periods = collect([$period]);
            }
            else {
                return $this->error('Specified period not found');
            }

        } else {
            $periods = collect([$this->periodRepository->getCurrentPeriod()]);
        }

        foreach ($periods as $period) {
            $this->output('Generating indices for ' . $period->name_with_year);

            $start = now();
            $this->calculateChannelIndicesCounts($period);
            $this->calculateChannelIndices($period);
            $this->calculateSrmIndices($period);

            $this->output('Processed in ' . $start->diffInSeconds(now()) . ' seconds');
        }

        $this->output('All indices successfully generated!');
    }

    private function init(): void
    {
        $this->periodRepository = new PeriodRepository();
        $this->channelRepository = new ChannelRepository();
        $this->organizationRepository = new OrganizationRepository();
        $this->config = config('settings.scores');
    }

    /**
     * Calculate all the follower/post/view/likes/comment/share counts for each channel
     *
     * @param Period $period
     */
    private function calculateChannelIndicesCounts(Period $period): void
    {
        $channels = $this->channelRepository->getChannelsWithDataForPeriod($period);

        foreach ($channels as $channel) {
            foreach ($channel->organizations as $organization) {
                // do a query directly instead of eager loading (as eager loading cannot use limit)
                if ($organizationData = $organization->organizationData()
                                            ->where('channel_id', $channel->id)
                                            ->join('periods', 'organization_data.period_id', '=', 'periods.id')
                                            ->whereDate('periods.start_date', '<', $period->end_date)
                                            ->orderBy('periods.start_date', 'desc')
                                            ->take(1)
                                            ->first()) {

                    // use the latest organizationData follower count (that means we fallback to a previous period organizationData if none is present for this period)
                    $followerCount = $organizationData->follower_count;

                    $postCount = 0;
                    $viewCount = 0;
                    $likeCount = 0;
                    $commentCount = 0;
                    $shareCount = 0;

                    foreach ($organization->posts->where('channel_id', $channel->id) as $post) {
                        $postCount++;

                        // do a query directly instead of eager loading (as eager loading cannot use limit)
                        // only count the latest postData for this post
                        if ($postData = $post->postData()
                            ->join('periods', 'post_data.period_id', '=', 'periods.id')
                            ->whereDate('periods.start_date', '<', $period->end_date)
                            ->orderBy('periods.start_date', 'desc')
                            ->take(1)
                            ->first()) {


                            $viewCount += $postData->view_count;
                            $likeCount += $postData->like_count;
                            $commentCount += $postData->comment_count;
                            $shareCount += $postData->share_count;
                        }
                    }

                    ChannelIndex::updateOrCreate([
                        'organization_id'   => $organization->id,
                        'period_id'         => $period->id,
                        'channel_id'        => $channel->id,
                    ], [
                        'follower_count'    => $followerCount,
                        'post_count'        => $postCount,
                        'view_count'        => $viewCount,
                        'like_count'        => $likeCount,
                        'comment_count'     => $commentCount,
                        'share_count'       => $shareCount,
                    ]);
                }
            }
        }
    }

    /**
     * Calculate the actual indices for each channel (based on the counts calculated before)
     *
     * @param Period $period
     */
    private function calculateChannelIndices(Period $period): void
    {
        $channels = $this->channelRepository->getChannelsWithIndicesForPeriod($period);

        $previousPeriod = $period->getPreviousPeriod();

        foreach ($channels as $channel) {
            $totals = ChannelIndex::where('period_id', $period->id)->where('channel_id', $channel->id)->first([
                DB::raw('COUNT(id) as count'),
                DB::raw('MAX(follower_count) as follower_count'),
                DB::raw('MAX(post_count) as post_count'),
                DB::raw('MAX(view_count) as view_count'),
                DB::raw('MAX(like_count) as like_count'),
                DB::raw('MAX(comment_count) as comment_count'),
                DB::raw('MAX(share_count) as share_count'),
            ]);

            $totalEngagement = 0;

            if ($channel->can_collect_views_data) {
                $totalEngagement += ($totals->view_count * $this->config['view']);
            }

            if ($channel->can_collect_likes_data) {
                $totalEngagement += ($totals->like_count * $this->config['like']);
            }

            if ($channel->can_collect_comments_data) {
                $totalEngagement += ($totals->comment_count * $this->config['comment']);
            }

            if ($channel->can_collect_shares_data) {
                $totalEngagement += ($totals->share_count * $this->config['share']);
            }

            $totalEngagement = $totalEngagement > 0 && $totals->post_count > 0 ? $totalEngagement + ($totalEngagement / $totals->post_count) : 0;

            $totalChannelWeight = $channel->weight_activity + $channel->weight_popularity + $channel->weight_engagement;

            $channelIndices = $channel->channelIndices->where('channel_id', $channel->id);

            foreach ($channelIndices as $index) {
                // Popularity: followers / max followers * 100
                $index->popularity = $totals->follower_count > 0 ? ($index->follower_count / $totals->follower_count) * 100 : 0;
                // Activity: post count / max post count * 100
                $index->activity = $totals->post_count > 0 ? ($index->post_count / $totals->post_count) * 100 : 0;

                // Engagement: engagement_score ((views * view score) + (likes * likes score) + (comments * comment score) + (shares * shares score))
                $engagement = 0;

                if ($channel->can_collect_views_data) {
                    $engagement += ($index->view_count  * $this->config['view']);
                }

                if ($channel->can_collect_likes_data) {
                    $engagement += ($index->like_count * $this->config['like']);
                }

                if ($channel->can_collect_comments_data) {
                    $engagement += ($index->comment_count * $this->config['comment']);
                }

                if ($channel->can_collect_shares_data) {
                    $engagement += ($index->share_count * $this->config['share']);
                }

                // Engagement: (engagement_score + (engagement_score / post count)) / max engagement score * 100
                $index->engagement = $totalEngagement > 0 && $index->post_count > 0 ? (($engagement + ($engagement / $index->post_count)) / $totalEngagement) * 100 : 0;

                $index->composite = $totalChannelWeight > 0 ? (($index->popularity * $channel->weight_popularity) + ($index->activity * $channel->weight_activity) + ($index->engagement * $channel->weight_engagement)) / $totalChannelWeight : 0;
                $index->composite = round($index->composite, 2);

                if ($previousPeriod) {
                    $previousIndex = ChannelIndex::where('period_id', $previousPeriod->id)
                                            ->where('channel_id', $channel->id)
                                            ->where('organization_id', $index->organization->id)
                                            ->first();
                    if ($previousIndex && $previousIndex->composite > 0) {
                        $index->composite_shift = ($index->composite - $previousIndex->composite) / $previousIndex->composite * 100;
                    }
                }

                $index->save();
            }
        }
    }

    /**
     * Calculate combined indices based on the channel indices
     *
     * @param Period $period
     * @throws \Exception
     */
    private function calculateSrmIndices(Period $period): void
    {
        $totalChannelWeight = $this->channelRepository->getActiveChannelsTotalWeightForPeriod($period);

        if ($totalChannelWeight <= 0) {
            throw new \Exception('Combination of all channel weights for period "' . $period->name . '" cannot be 0 or negative');
        }

        $previousPeriod = $period->getPreviousPeriod();

        foreach ($this->organizationRepository->getOrganizationsWithChannelIndicesForPeriod($period) as $organization) {
            $engagement = 0;
            $activity = 0;
            $popularity = 0;
            $composite = 0;
            $compositeShift = 0;

            foreach ($organization->channelIndices as $index) {
                $channelWeight = $index->channel->ranking_weight / $totalChannelWeight;
                $engagement += $index->engagement * $channelWeight;
                $activity += $index->activity * $channelWeight;
                $popularity += $index->popularity * $channelWeight;
                $composite += $index->composite * $channelWeight;
            }

            $composite = round($composite, 2);

            if ($previousPeriod) {
                $previousIndex = SrmIndex::where('period_id', $previousPeriod->id)
                    ->where('organization_id', $organization->id)
                    ->first();

                if ($previousIndex && $previousIndex->composite > 0) {
                    $compositeShift = ($composite - $previousIndex->composite) / $previousIndex->composite * 100;
                }
            }

            $srmIndex = SrmIndex::updateOrCreate([
                'organization_id'   => $organization->id,
                'period_id'         => $period->id,
            ], [
                'engagement'        => $engagement,
                'activity'          => $activity,
                'popularity'        => $popularity,
                'composite'         => $composite,
                'composite_shift'   => $compositeShift,
            ]);

            foreach ($organization->channelIndices as $index) {
                $index->srm_index_id = $srmIndex->id;
                $index->save();
            }
        }
    }
}
