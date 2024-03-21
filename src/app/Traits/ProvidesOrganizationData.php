<?php

namespace App\Traits;

use App\Enums\Metric;
use App\Repositories\OrganizationRepository;
use App\Models\Organization;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Period;
use App\Models\SrmIndex;
use App\Models\Channel;
use App\Models\Industry;
use Illuminate\Support\Str;

trait ProvidesOrganizationData
{
    protected $organizationRepository;

    public function __construct()
    {
        $this->organizationRepository = new OrganizationRepository();
    }

    protected function getOrganizationGeneralInfo(Organization $organization, Collection $channels): array
    {
        $organizationData = [
            'logo' => $organization->logo ? Storage::url($organization->logo) : null,
            'name' => $organization->name,
            'translated_name' => $organization->getTranslation('name', (app()->getLocale() == 'en' ? 'zh' : 'en')),
            'intro' => $organization->intro,
            'industry' => optional($organization->industry)->name,
            'indexed_since' => null,
            'channels' => [],
            'profile_incomplete' => $organization->isProfileInComplete(),
        ];

        if ($firstSrmIndex = $organization->srmIndices()->orderBy('period_id', 'asc')->first()) {
            $organizationData['indexed_since'] = $firstSrmIndex->created_at->format('Y-m-d');
        }

        foreach($organization->channels->sortBy('order') as $channel) {
            $organizationData['channels'][$channel->order] = [
                'id' => $channel->id,
                'name' => $channel->name,
                'logo' => $channel->logo ? Storage::url($channel->logo) : null,
                'url' => $channel->organization_url_prefix . $channel->pivot->channel_username . $channel->organization_url_suffix,
                'is_external_link' => $channel->organization_url_prefix !== null,
            ];
        }

        $organizationData['can_add_more_channels'] = $channels->count() > $organization->channels->count();

        return $organizationData;
    }

    protected function getCrossChannelStats(Period $period, Organization $organization): array
    {
        $data = [
            'post_count' => [
                'count' => 0,
                'progression' => 0,
            ],
            'view_count' => [
                'count' => 0,
                'progression' => 0,
            ],
            'like_count' => [
                'count' => 0,
                'progression' => 0,
            ],
            'comment_count' => [
                'count' => 0,
                'progression' => 0,
            ],
            'share_count' => [
                'count' => 0,
                'progression' => 0,
            ],
            'follower_count' => [
                'count' => 0,
                'progression' => 0,
            ],
   /*         'follower_growth' => [
                'count' => 0,
                'progression' => 0,
            ],*/
        ];

        if ($this->guard()->user()->can('view my organization.cross channel stats')) {
            if (!$previousPeriod = $this->periodRepository->getPreviousPeriodForOrganization($period, $organization->id)) {
                return $data;
            }

            $totalCounts = $this->organizationRepository->getOrganizationsWithCountsBetweenPeriods($previousPeriod, $period, [$organization->id])->first();

            $previousPeriodTotalCounts = null;

            // get the difference with the previous week's progression
            if ($previousPreviousPeriod = $this->periodRepository->getPreviousPeriodForOrganization($previousPeriod, $organization->id)) {
                $previousPeriodTotalCounts = $this->organizationRepository->getOrganizationsWithCountsBetweenPeriods($previousPreviousPeriod, $previousPeriod, [$organization->id])->first();
            }

            $data = [
                'post_count' => [
                    'count' => $totalCounts->post_count_difference,
                    'progression' => $previousPeriodTotalCounts ? $totalCounts->post_count_difference - $previousPeriodTotalCounts->post_count_difference : null,
                ],
                'view_count' => [
                    'count' => $totalCounts->view_count_difference,
                    'progression' => $previousPeriodTotalCounts ? $totalCounts->view_count_difference - $previousPeriodTotalCounts->view_count_difference : null,
                ],
                'like_count' => [
                    'count' => $totalCounts->like_count_difference,
                    'progression' => $previousPeriodTotalCounts ? $totalCounts->like_count_difference - $previousPeriodTotalCounts->like_count_difference : null,
                ],
                'comment_count' => [
                    'count' => $totalCounts->comment_count_difference,
                    'progression' => $previousPeriodTotalCounts ? $totalCounts->comment_count_difference - $previousPeriodTotalCounts->comment_count_difference : null,
                ],
                'share_count' => [
                    'count' => $totalCounts->share_count_difference,
                    'progression' => $previousPeriodTotalCounts ? $totalCounts->share_count_difference - $previousPeriodTotalCounts->share_count_difference : null,
                ],
                'follower_count' => [
                    'count' => $totalCounts->total_follower_count,
                    'progression' => $previousPeriodTotalCounts ? $totalCounts->total_follower_count - $previousPeriodTotalCounts->total_follower_count : null,
                ],
     /*           'follower_growth' => [
                    'count' => $totalCounts->follower_count_difference,
                    'progression' => $previousPeriodTotalCounts ? $totalCounts->follower_count_difference - $previousPeriodTotalCounts->follower_count_difference : null,
                ],*/
            ];

            foreach($data as $key => $item) {
                $data[$key]['count'] = number_format($data[$key]['count']);
                $data[$key]['progression'] = number_format($data[$key]['progression']);
            }
        }

        return $data;
    }

    protected function getRankInfoData(Period $period, Organization $organization): array
    {
        $data = [
            'srm_score' => [
                'composite' => 'N/A',
                'composite_shift' => 0,
            ],
            'rank' => [
                'own' => 0,
                'total' => 0,
                'shift' => 0,
            ],
        ];

        if ($this->guard()->user()->can('view my organization.rank info')) {
            if ($srmIndex = $organization->srmIndices()->where('period_id', $period->id)->first()) {
                $data['srm_score'] = [
                    'composite' => $srmIndex->composite,
                    'composite_shift' => $srmIndex->composite_shift,
                ];
            }

            $data['rank']['total'] = SrmIndex::whereHas('organization', function ($query) {
                $query->where('published', 1);
            })->where('period_id', $period->id)->count();


            $data['rank']['own'] = $this->organizationRepository->getRankForPeriod($period, $organization->id);
            $data['rank']['shift'] = $this->organizationRepository->getAbsoluteRankShiftForPeriod($period, $organization->id);
        }

        return $data;
    }

    protected function getClosureForMetric(string $metric)
    {
        switch ($metric) {
            case 'follower_count_difference':
                return function($stats, $previousStats) {
                    return $stats->total_follower_count - $previousStats->total_follower_count;
                };
                break;
            case 'post_count_difference':
                return function($stats, $previousStats) {
                    return $stats->total_post_count - $previousStats->total_post_count;
                };
                break;
            case 'view_count_difference':
                return function($stats, $previousStats) {
                    return $stats->total_view_count - $previousStats->total_view_count;
                };
                break;
            case 'avg_view_count':
                return function($stats, $previousStats) {
                    $postCountDifference = $stats->total_post_count - $previousStats->total_post_count;
                    $viewCountDifference = $stats->total_view_count - $previousStats->total_view_count;

                    return round($postCountDifference ? $viewCountDifference / $postCountDifference : 0);
                };
                break;
            case 'reads_vs_fans':
                return function($stats, $previousStats) {
                    return  round($stats->total_post_count && $stats->total_follower_count ? ((($stats->total_view_count / $stats->total_post_count) / $stats->total_follower_count) * 1) : 0, 5);
                };
                break;
            case 'like_count_difference':
                return function($stats, $previousStats) {
                    return $stats->total_like_count - $previousStats->total_like_count;
                };
                break;
            case 'avg_like_count':
                return function($stats, $previousStats) {
                    $postCountDifference = $stats->total_post_count - $previousStats->total_post_count;
                    $likeCountDifference = $stats->total_like_count - $previousStats->total_like_count;

                    return round($postCountDifference ? $likeCountDifference / $postCountDifference : 0);
                };
                break;
            case 'likes_vs_fans':
                return function($stats, $previousStats) {
                    return  round($stats->total_post_count && $stats->total_follower_count ? ((($stats->total_like_count / $stats->total_post_count) / $stats->total_follower_count) * 1) : 0, 5);
                };
                break;
            case 'share_count_difference':
                return function($stats, $previousStats) {
                    return $stats->total_share_count - $previousStats->total_share_count;
                };
                break;
            case 'avg_share_count':
                return function($stats, $previousStats) {
                    $postCountDifference = $stats->total_post_count - $previousStats->total_post_count;
                    $shareCountDifference = $stats->total_share_count - $previousStats->total_share_count;

                    return round($postCountDifference ? $shareCountDifference / $postCountDifference : 0);
                };
                break;
            case 'shares_vs_fans':
                return function($stats, $previousStats) {
                    return  round($stats->total_post_count && $stats->total_follower_count ? ((($stats->total_share_count / $stats->total_post_count) / $stats->total_follower_count) * 1) : 0, 5);
                };
                break;
            case 'avg_engagement_vs_posts':
                return function($stats, $previousStats) {
                   // $rows['avg_engagement_vs_posts'][] = $item->post_count_difference ? number_format(($item->like_count_difference + $item->view_count_difference + $item->comment_count_difference + $item->share_count_difference) / $item->post_count_difference) : 0;
                    $postCountDifference = $stats->total_post_count - $previousStats->total_post_count;
                    $viewCountDifference = $stats->total_view_count - $previousStats->total_view_count;
                    $likeCountDifference = $stats->total_like_count - $previousStats->total_like_count;
                    $commentCountDifference = $stats->total_comment_count - $previousStats->total_comment_count;
                    $shareCountDifference = $stats->total_share_count - $previousStats->total_share_count;

                    return round($postCountDifference ? (($viewCountDifference * config('settings.scores.view')) + ($likeCountDifference * config('settings.scores.like')) + ($commentCountDifference * config('settings.scores.comment')) + ($shareCountDifference * config('settings.scores.share'))) / $postCountDifference : 0, 2);
                };
                break;
            default:
                return null;
                break;
        }
    }

    protected function getCompetitionAnalysisTableStats(Period $period, Organization $organization, ?Channel $channel = null, bool $isMyOrganizationPage = false): array
    {
        $data = [
            'headers' => [],
            'rows' => [],
        ];

        if ($this->guard()->user()->can('view my organization.competition analysis')) {
            if (!$previousPeriod = $this->periodRepository->getPreviousPeriodForOrganization($period, $organization->id)) {
                return $data;
            }

            $headers = ['Metric'];
            $rows = [];

            $rawData = $this->organizationRepository->getOrganizationWithCompetitorsWithCountsBetweenPeriods($previousPeriod, $period, $organization, optional($channel)->id);

            foreach($rawData as $item) {
                if (count($rawData) > 2) { // counts the number of organizations (competitors)
                    // if we have more than 2, we crop the organization names to prevent spacing issues in the table
                    $headers[] = [
                        'value' => Str::limit($item->name, 12),
                        'help' => $item->name,
                    ];
                }
                else {
                    $headers[] = $item->name;
                }
            }

            $rows['follower_count_difference'][] = [
                'value' => Metric::getValue('follower_count_difference')['name'],
                'help' => Metric::getValue('follower_count_difference')['help'],
            ];

            foreach($rawData as $item) {
                $rows['follower_count_difference'][] = ['value' => number_format($item->follower_count_difference)];
            }


            $rows['post_count_difference'][] = [
                'value' => Metric::getValue('post_count_difference')['name'],
                'help' => Metric::getValue('post_count_difference')['help'],
            ];

            foreach($rawData as $item) {
                $rows['post_count_difference'][] = ['value' => number_format($item->post_count_difference)];
            }

            // Reads

            $rows['view_count_difference'][] = [
                'value' => Metric::getValue('view_count_difference')['name'],
                'help' => Metric::getValue('view_count_difference')['help'],
            ];

            foreach($rawData as $item) {
                $rows['view_count_difference'][] = ['value' => number_format($item->view_count_difference)];
            }

            $rows['avg_view_count'][] = [
                'value' => Metric::getValue('avg_view_count')['name'],
                'help' => Metric::getValue('avg_view_count')['help'],
            ];

            foreach($rawData as $item) {
                $rows['avg_view_count'][] = ['value' => $item->post_count_difference ? number_format($item->view_count_difference / $item->post_count_difference) : 0];
            }

            $rows['reads_vs_fans'][] = [
                'value' => Metric::getValue('reads_vs_fans')['name'],
                'help' => Metric::getValue('reads_vs_fans')['help'],
            ];

            foreach($rawData as $item) {
                $rows['reads_vs_fans'][] = ['value' => $item->total_post_count && $item->total_follower_count ? number_format(((($item->total_view_count / $item->total_post_count) / $item->total_follower_count) * 1), 5) : 0];
            }

            // Likes

            $rows['like_count_difference'][] = [
                'value' => Metric::getValue('like_count_difference')['name'],
                'help' => Metric::getValue('like_count_difference')['help'],
            ];

            foreach($rawData as $item) {
                $rows['like_count_difference'][] = ['value' => number_format($item->like_count_difference)];
            }

            $rows['avg_like_count'][] = [
                'value' => Metric::getValue('avg_like_count')['name'],
                'help' => Metric::getValue('avg_like_count')['help'],
            ];

            foreach($rawData as $item) {
                $rows['avg_like_count'][] = ['value' => $item->post_count_difference ? number_format($item->like_count_difference / $item->post_count_difference) : 0];
            }

            $rows['likes_vs_fans'][] = [
                'value' => Metric::getValue('likes_vs_fans')['name'],
                'help' => Metric::getValue('likes_vs_fans')['help'],
            ];

            foreach($rawData as $item) {
                $rows['likes_vs_fans'][] = ['value' => $item->total_post_count && $item->total_follower_count ? number_format(((($item->total_like_count / $item->total_post_count) / $item->total_follower_count) * 1), 5) : 0];
            }

            // Shares

            $rows['share_count_difference'][] = [
                'value' => Metric::getValue('share_count_difference')['name'],
                'help' => Metric::getValue('share_count_difference')['help'],
            ];

            foreach($rawData as $item) {
                $rows['share_count_difference'][] = ['value' => number_format($item->share_count_difference)];
            }

            $rows['avg_share_count'][] = [
                'value' => Metric::getValue('avg_share_count')['name'],
                'help' => Metric::getValue('avg_share_count')['help'],
            ];

            foreach($rawData as $item) {
                $rows['avg_share_count'][] = ['value' => $item->post_count_difference ? number_format($item->share_count_difference / $item->post_count_difference) : 0];
            }

            $rows['shares_vs_fans'][] = [
                'value' => Metric::getValue('shares_vs_fans')['name'],
                'help' => Metric::getValue('shares_vs_fans')['help'],
            ];

            foreach($rawData as $item) {
                $rows['shares_vs_fans'][] = ['value' => $item->total_post_count && $item->total_follower_count ? number_format(((($item->total_share_count / $item->total_post_count) / $item->total_follower_count) * 1), 5) : 0];
            }

            // Engagement

            $rows['avg_engagement_vs_posts'][] = [
                'value' => Metric::getValue('avg_engagement_vs_posts')['name'],
                'help' => Metric::getValue('avg_engagement_vs_posts')['help'],
            ];

            foreach($rawData as $item) {
                $rows['avg_engagement_vs_posts'][] = ['value' => $item->post_count_difference ? number_format((($item->like_count_difference * config('settings.scores.like')) + ($item->view_count_difference * config('settings.scores.view')) + ($item->comment_count_difference * config('settings.scores.comment')) + ($item->share_count_difference * config('settings.scores.share'))) / $item->post_count_difference, 2) : 0];
            }

            $data = [
                'headers' => $headers,
                'rows' => $rows,
            ];
        }

        return $data;
    }

    protected function getCompetitionAnalysisChartStats(Period $period, Organization $organization, string $metric, ?Channel $channel = null): array
    {
        $numberOfPeriods = 8;

        $data = [
            'labels' => [],
            'series' => [],
            'title' => '',
        ];

        if ($this->guard()->user()->can('view my organization.competition analysis')) {
            $closure = $this->getClosureForMetric($metric);

            $data = $this->organizationRepository->getOrganizationWithCompetitorsWithCountsForPastPeriods($period, $organization, optional($channel)->id, $numberOfPeriods, $closure)->toArray();
        }

        $data['title'] = Metric::getValue($metric)['name'];

        return $data;
    }

    protected function addCompetitor(Organization $organization, ?int $competitorId = null): bool
    {
        if (!$competitorId || $organization->id == $competitorId) {
            return false;
        }

        if (!$this->guard()->user()->canAddCompetitor($organization)) {
            return false;
        }

        $organization->competitors()->syncWithoutDetaching([$competitorId]);
        $organization->load('competitors');

        return true;
    }

    protected function getChannelList($organization = null)
    {
        $channels = Channel::orderBy('order', 'asc')->get();

        $organizationChannels = $organization ? $organization->channels->pluck('pivot.channel_username', 'id') : [];

        return $channels->map(function ($item) use ($organizationChannels) {
            return [
                'id'                => $item->id,
                'name'              => $item->name,
                'url_prefix'        => $item->organization_url_prefix,
                'url_suffix'        => $item->organization_url_suffix,
                'channel_username'  => $organizationChannels[$item->id] ?? null,
            ];
        });
    }

    protected function getIndustryList()
    {
        return Industry::orderBy('name', 'asc')->pluck('name', 'id');
    }
}
