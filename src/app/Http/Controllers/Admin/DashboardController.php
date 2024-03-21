<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TopPerformingContentMetric;
use App\Http\Requests\Admin\GetDashboardIndexRequest;
use App\Models\Channel;
use App\Models\ChannelIndex;
use App\Models\Industry;
use App\Models\Period;
use App\Models\Post;
use App\Models\SrmIndex;
use App\Repositories\ChannelRepository;
use App\Repositories\IndexRepository;
use App\Repositories\IndustryRepository;
use App\Repositories\PeriodRepository;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;
use App\Repositories\OrganizationRepository;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    protected $channelRepository;

    protected $organizationRepository;

    protected $indexRepository;

    protected $periodRepository;

    protected $industryRepository;

    protected $postRepository;

    public function __construct()
    {
        $this->channelRepository = new ChannelRepository();
        $this->organizationRepository = new OrganizationRepository();
        $this->indexRepository = new IndexRepository();
        $this->periodRepository = new PeriodRepository();
        $this->industryRepository = new IndustryRepository();
        $this->postRepository = new PostRepository();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->guard()->user()->cannot('view statistics dashboard')) {
            if ($this->guard()->user()->can('view my organization')) {
                return redirect()->route('admin.my-organization.index');
            }

            return redirect()->route('admin.profile.show');
        }

        return view('admin.dashboard.index')->with([
            'channels'  => $this->getChannelsList(),
            'periods'   => $this->getPeriodList(),
            'industries' => $this->getIndustriesList(),
            'topPerformingContentMetrics' => TopPerformingContentMetric::getContentList(),
        ]);
    }

    public function getTopPerformersData(GetDashboardIndexRequest $request)
    {
        if (!$this->guard()->user()->can('view statistics dashboard.top performers')) {
            return response()->json([]);
        }

        $data = [];

        $period = $this->periodRepository->getPeriodById($request->get('period_id'));
        $channelId = $request->get('channel_id');
        $industryId = $request->get('industry_id');


        $topIndex = $this->indexRepository->getTopIndexWithOrganizationForPeriod($period, $channelId, $industryId);

        $data[] = [
            'super-text'    => __('admin.top-performers.index-leader'),
            'title'         => $topIndex->organization->name,
            'data'          => $topIndex->composite,
            'suffix'        => __('admin.top-performers.points')
        ];

        $topIndexShift = $this->indexRepository->getTopIndexShiftWithOrganizationForPeriod($period, $channelId, $industryId);

        $data[] = [
            'super-text'    => __('admin.top-performers.best-relative-index-progression'),
            'title'         => $topIndexShift->organization->name,
            'data'          => '+' . $topIndexShift->composite_shift,
            'suffix'        => '%'
        ];

        $topIndexShiftAbsolute = $this->organizationRepository->getTopOrganizationWithAbsoluteIndexShiftForPeriod($period, $channelId, $industryId);

        $data[] = [
            'super-text'    => __('admin.top-performers.best-absolute-index-progression'),
            'title'         => $topIndexShiftAbsolute->name,
            'data'          => '+' . $topIndexShiftAbsolute->composite_shift_absolute,
            'suffix'        => __('admin.top-performers.points')
        ];

        $topFollowerCount = $this->organizationRepository->getTopOrganizationWithFollowerCountForPeriod($period, $channelId, $industryId);

        $data[] = [
            'super-text'    => __('admin.top-performers.biggest-fan-base'),
            'title'         => $topFollowerCount->name,
            'data'          => number_format($topFollowerCount->total_follower_count),
            'suffix'        => __('admin.top-performers.reach')
        ];

        if ($topFollowerGrowth = $this->organizationRepository->getTopOrganizationWithFollowerGrowthForPeriod($period, $channelId, $industryId)) {
            $data[] = [
                'super-text'    => __('admin.top-performers.best-follower-growth'),
                'title'         => $topFollowerGrowth->name,
                'data'          => '+' . number_format($topFollowerGrowth->follower_growth),
                'suffix'        => __('admin.top-performers.fans')
            ];
        }

        $topPostCount = $this->organizationRepository->getTopOrganizationWithPostCountForPeriod($period, $channelId, $industryId);

        $data[] = [
            'super-text'    => __('admin.top-performers.most-active-employer'),
            'title'         => $topPostCount->name,
            'data'          => number_format($topPostCount->total_post_count),
            'suffix'        => __('admin.top-performers.posts')
        ];

        if ($topPostCountGrowth = $this->organizationRepository->getTopOrganizationWithPostCountGrowthForPeriod($period, $channelId, $industryId)) {
            $data[] = [
                'super-text'    => __('admin.top-performers.best-post-growth'),
                'title'         => $topPostCountGrowth->name,
                'data'          => '+' . number_format($topPostCountGrowth->post_count_growth),
                'suffix'        => __('admin.top-performers.posts')
            ];
        }

        $topReactionCount = $this->organizationRepository->getTopOrganizationWithReactionCountForPeriod($period, $channelId, $industryId);

        $data[] = [
            'super-text'    => __('admin.top-performers.most-engaging-content'),
            'title'         => $topReactionCount->name,
            'data'          => number_format($topReactionCount->total_reaction_count),
            'suffix'        => __('admin.top-performers.reactions')
        ];

        if ($topReactionCountGrowth = $this->organizationRepository->getTopOrganizationWithReactionCountGrowthForPeriod($period, $channelId, $industryId)) {
            $data[] = [
                'super-text'    => __('admin.top-performers.best-engagement-growth'),
                'title'         => $topReactionCountGrowth->name,
                'data'          => '+' . number_format($topReactionCountGrowth->reaction_count_growth),
                'suffix'        => __('admin.top-performers.reactions')
            ];
        }

        $postReactionsRatio = $this->indexRepository->getPostReactionsRatioForPeriod($period, $channelId, $industryId);

        $data[] = [
            'super-text'    => __('admin.top-performers.avg-engagement-vs-posts-ratio'),
            'title'         => __('admin.top-performers.all-employer'),
            'data'          => number_format($postReactionsRatio),
            'suffix'        => ''
        ];

        return response()->json($data);
    }


    public function getTopPerformingContentData(GetDashboardIndexRequest $request)
    {
        if (!$this->guard()->user()->can('view statistics dashboard.top performing content')) {
            return response()->json([]);
        }

        $period = $this->periodRepository->getPeriodById($request->get('period_id'));
        $channelId = $request->get('channel_id');
        $industryId = $request->get('industry_id');
        $metric = $request->get('top_performing_content_metric', 'top_most_reads');

        $metric = TopPerformingContentMetric::getValue($metric)['field_mapping'];

        $data = [];

        $posts = $this->postRepository->getTopPostsByMetricForPeriod($period, $metric, $channelId, $industryId, 5);

        $counter = 1;

        $channels = Channel::all();

        foreach ($posts as $post) {
            $channel = $channels->where('id', $post->channel_id)->first();

            $data[] = [
                'rank' => $counter,
                'organization_name' => json_decode($post->name)->{app()->getLocale()},
                'title' => $post->title,
                'title_trimmed' => Str::limit($post->title, 50),
                'view_count' => $channel->can_collect_views_data ? number_format($post->view_count) : 'N/A',
                'like_count' => $channel->can_collect_likes_data ? number_format($post->like_count) : 'N/A',
                'comment_count' => $channel->can_collect_comments_data ? number_format($post->comment_count) : 'N/A',
                'share_count' => $channel->can_collect_shares_data ? number_format($post->share_count) : 'N/A',
                'link' => (new Post())->fill((array) $post)->getPostUrl(),
            ];

            $counter++;
        }

        return response()->json($data);
    }

    public function getIndicesTableData(Request $request)
    {
        $model = $request->input('channel_id') ? new ChannelIndex() : new SrmIndex();
        $channelId = $request->input('channel_id');

        $with = ['organization', 'period'];

        $query = $model->newQuery();

        $periodId = $request->has('period_id') ? $request->input('period_id') : optional((new PeriodRepository())->getLatestPeriodWithIndices())->id;

        $query->where('period_id', $periodId);

        if ($request->input('industry_id')) {
            $query->whereHas('organization', function ($query) use ($request) {
                $query->where('industry_id', $request->input('industry_id'));
            });
        }

        if ($channelId) {
            $query->where('channel_id', $channelId)->select('channel_indices.*');
            $with[] = 'channel';
        } else {
            $query->select('srm_indices.*');
            $with[] = 'channelIndices.channel';
            $with[] = 'channelIndices.period';
            $with[] = 'channelIndices.organization';
        }

        $query->with($with);

        return datatables()->of($query)
            ->rawColumns(['composite_shift'])
            ->editColumn('composite_shift', function ($index) {
                $cssClass = 'text-info';

                if ($index->composite_shift > 0) {
                    $cssClass = 'text-success';
                } elseif ($index->composite_shift < 0) {
                    $cssClass = 'text-danger';
                }

                return '<span class="' . $cssClass . '">' . $index->composite_shift . '</span>';
            })
            ->editColumn('organization.name', function ($index) {
                return $index->organization->name;
            })
            ->make(true);
    }

    public function getIndicesDetailedTableData(Request $request)
    {
        $model = $request->input('channel_id') ? new ChannelIndex() : new SrmIndex();
        $channelId = $request->input('channel_id');

        $channel = $channelId ? Channel::findOrFail($channelId) : null;

        $with = ['organization', 'period'];

        $query = $model->newQuery();


        if ($request->input('period_id')) {
            $query->where('period_id', $request->input('period_id'));
        }

        if ($request->input('industry_id')) {
            $query->whereHas('organization', function ($query) use ($request) {
                $query->where('industry_id', $request->input('industry_id'));
            });
        }

        if ($channelId) {
            $query->where('channel_id', $channelId)->select('channel_indices.*');
            $with[] = 'channel';
        } else {
            $query->select('srm_indices.*');
            $with[] = 'channelIndices.channel';
            $with[] = 'channelIndices.period';
            $with[] = 'channelIndices.organization';
        }

        $query->with($with);

        return datatables()->of($query)
            ->rawColumns(['actions', 'composite_shift'])
            ->editColumn('composite_shift', function ($index) {
                $cssClass = 'text-info';

                if ($index->composite_shift > 0) {
                    $cssClass = 'text-success';
                } elseif ($index->composite_shift < 0) {
                    $cssClass = 'text-danger';
                }

                return '<span class="' . $cssClass . '">' . $index->composite_shift . '</span>';
            })
            ->addColumn('actions', function ($index) use ($channel) {
                $content = '';

                if ($channel) {
                    $content .= '<strong>Followers:</strong> ' . number_format($index->follower_count) . '<br>';
                    $content .= '<strong>Posts:</strong> ' . number_format($index->post_count) . '<br>';
                    if ($channel->can_collect_views_data) {
                        $content .= '<strong>Views:</strong> ' . number_format($index->view_count) . '<br>';
                    }
                    if ($channel->can_collect_likes_data) {
                        $content .= '<strong>Likes:</strong> ' . number_format($index->like_count) . '<br>';
                    }
                    if ($channel->can_collect_comments_data) {
                        $content .= '<strong>Comments:</strong> ' . number_format($index->comment_count) . '<br>';
                    }
                    if ($channel->can_collect_shares_data) {
                        $content .= '<strong>Shares:</strong> ' . number_format($index->share_count) . '<br>';
                    }
                } else {
                    foreach ($index->channelIndices as $index) {
                        $content .= '<strong>' . $index->channel->name . ':</strong> ' . $index->composite . '<br>';
                    }
                }
                return '<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="popover" data-trigger="focus" title="Data breakdown" data-content="' . $content . '"><i class="fe fe-eye"></i></button>';
            })
            ->editColumn('period.start_date', function ($index) {
                return $index->period->name_with_year;
            })
            ->editColumn('organization.name', function ($index) {
                return $index->organization->name;
            })
            ->make(true);
    }

    protected function getPeriodList()
    {
        return $this->periodRepository->getAllPresentPeriodsWithIndices()->map->only(['id', 'name', 'name_with_year'])->toArray();
    }

    protected function getChannelsList()
    {
        return $this->channelRepository->getChannels()->prepend(['id' => null, 'name' => __('admin.top-performers.all-channels')])->toArray();
    }

    protected function getIndustriesList()
    {
        return $this->industryRepository->getPublishedIndustryList()->prepend(['id' => null, 'industry_name' => __('admin.top-performers.all-industries')])->toArray();;
    }
}
