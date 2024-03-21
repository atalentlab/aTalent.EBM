<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\GetMyOrganizationChartDataRequest;
use App\Models\Activity;
use App\Models\Channel;
use App\Models\Organization;
use App\Http\Requests\Admin\StoreOrganizationRequest;
use App\Http\Requests\Admin\GetRemoteSelectOptionsRequest;
use App\Http\Requests\Admin\GetMyOrganizationDataRequest;
use App\Traits\ProvidesOrganizationData;
use App\Repositories\ChannelRepository;
use App\Repositories\IndexRepository;
use App\Repositories\PeriodRepository;
use App\Repositories\OrganizationRepository;

class OrganizationController extends Controller
{
    use ProvidesOrganizationData;

    protected $organization;

    protected $channelRepository;

    protected $organizationRepository;

    protected $indexRepository;

    protected $periodRepository;

    public function __construct()
    {
        $this->channelRepository = new ChannelRepository();
        $this->organizationRepository = new OrganizationRepository();
        $this->indexRepository = new IndexRepository();
        $this->periodRepository = new PeriodRepository();
    }

    public function index()
    {
        return view('admin.organization.index');
    }

    public function create()
    {
        return view('admin.organization.create')->with([
            'industries'    => $this->getIndustryList(),
            'channels'      => $this->getChannelList(),
        ]);
    }

    public function store(StoreOrganizationRequest $request)
    {
        $organization = new Organization;
        $organization->name = $request->input('name');
        $organization->published = $request->has('published') ? true : false;
        $organization->is_fetching = $request->has('is_fetching') ? true : false;
        $organization->industry_id = $request->input('industry_id');
        $organization->intro = $request->input('intro');
        $organization->website = $request->input('website');

        $organization->uploadFile('logo');

        $organization->save();

        $organization->channels()->sync($request->input('channels'));

        $organization->syncCompetitors($request->input('competitors') ?? []);

        return redirect()->route('admin.organization.index')->with('success', 'The organization has been created.');
    }

    public function edit($id)
    {
        $organization = Organization::findOrFail($id);

        return view('admin.organization.edit')->with([
            'entity'        => $organization,
            'industries'    => $this->getIndustryList(),
            'channels'      => $this->getChannelList($organization),
        ]);
    }

    public function show($id)
    {
        $organization = Organization::findOrFail($id);

        $channels = $this->channelRepository->getPublishedChannelsNoSort()->sortBy('order');

        $organizationData = $this->getOrganizationGeneralInfo($organization, $channels);

        return view('admin.organization.show')->with([
            'organization' => $organization,
            'organizationData' => json_encode($organizationData),
            'periods' => $this->periodRepository->getAllPresentPeriodsWithIndicesForOrganization($organization->id),
            'channels' => $channels->map->only(['id', 'name'])->prepend(['id' => null, 'name' => __('admin.top-performers.all-channels')]),
        ]);
    }

    public function getShowData(GetMyOrganizationDataRequest $request)
    {
        $organization = Organization::findOrFail($request->route('id'));

        $period = (new PeriodRepository())->getPeriodById($request->get('period_id'));

        $data = [
            'rank_info' => $this->getRankInfoData($period, $organization),
            'cross_channel_stats' => $this->getCrossChannelStats($period, $organization),
        ];

        return response()->json($data);
    }

    public function getCompetitionAnalysisData(GetMyOrganizationDataRequest $request)
    {
        $organization = Organization::findOrFail($request->route('id'));

        $period = $this->periodRepository->getPeriodById($request->get('period_id'));
        $channel = Channel::find($request->get('channel_id'));

        if (!$organization) response()->json(['Organization not found'], 404);

        $data = [
            'can_add_competitors' => false,
            'table' => $this->getCompetitionAnalysisTableStats($period, $organization, $channel, false),
        ];

        return response()->json($data);
    }

    public function getCompetitionAnalysisChartData(GetMyOrganizationChartDataRequest $request)
    {
        $organization = Organization::findOrFail($request->route('id'));

        $period = $this->periodRepository->getPeriodById($request->get('period_id'));
        $channel = Channel::find($request->get('channel_id'));
        $metric = $request->get('metric');

        $data = $this->getCompetitionAnalysisChartStats($period, $organization, $metric, $channel);

        return response()->json($data);
    }

    public function update(StoreOrganizationRequest $request, $id)
    {
        $organization = Organization::findOrFail($id);
        $organization->name = $request->input('name');
        $organization->published = $request->has('published') ? true : false;
        $organization->is_fetching = $request->has('is_fetching') ? true : false;
        $organization->industry_id = $request->input('industry_id');
        $organization->intro = $request->input('intro');
        $organization->website = $request->input('website');

        $organization->uploadFile('logo');

        $organization->save();

        $organization->channels()->sync($request->input('channels'));

        $organization->syncCompetitors($request->input('competitors') ?? []);

        return redirect()->route('admin.organization.index')->with('success', 'The organization has been updated.');
    }

    public function delete($id)
    {
        $organization = Organization::findOrFail($id);

        $name = $organization->name;

        $organization->delete();

        return redirect()->route('admin.organization.index')->with('success', 'Organization ' . $name . ' has been deleted.');
    }

    public function getDatatableData()
    {
        $query = Organization::select('id', 'name', 'is_fetching', 'published')->withCount(['posts', 'organizationData']);

        return datatables()->of($query)
            ->rawColumns(['actions', 'published', 'is_fetching'])
            ->orderColumn('published', 'published $1, id $1')
            ->addColumn('organization_name', function ($organization) {
                return $organization->name;
            })
            ->editColumn('published', '{!! $published ? \'<span class="status-icon bg-success"><span style="display: none;">1</span></span></i>\' : \'<span class="status-icon bg-danger"><span style="display: none;">0</span></span>\' !!}')
            ->editColumn('is_fetching', '{!! $is_fetching ? \'<span class="status-icon bg-success"><span style="display: none;">1</span></span></i>\' : \'<span class="status-icon bg-danger"><span style="display: none;">0</span></span>\' !!}')
            ->addColumn('actions', function ($organization) {
                return '<a href="' . route('admin.organization.show', ['id' => $organization->id]) . '" class="btn btn-outline-primary btn-sm" title="View organization"><i class="fe fe-eye"></i></a>' .
                        '<a href="' . route('admin.organization.edit', ['id' => $organization->id]) . '" class="btn btn-outline-primary btn-sm ml-1" title="Edit organization"><i class="fe fe-edit"></i></a>' .
                        '<a href="' . route('admin.organization.post.index', ['id' => $organization->id]) . '" class="btn btn-secondary btn-sm ml-1" title="View posts"><i class="fe fe-list"></i></a>' .
                        '<a href="' . route('admin.organization.data.index', ['id' => $organization->id]) . '" class="btn btn-secondary btn-sm ml-1" title="View data"><i class="fe fe-database"></i></a>';
            })
            ->make(true);
    }

    public function activity($id)
    {
        $organization = Organization::findOrFail($id);

        return view('admin.organization.activity')->with([
            'entity' => $organization,
        ]);
    }

    public function getActivityData($id)
    {
        $query = Activity::where('subject_id', $id)->where('subject_type', 'App\Models\Organization')->with('causer');

        return datatables()->of($query)
            ->rawColumns(['actions'])
            ->addColumn('user', function ($activity) {
                return $activity->getCauser();
            })
            ->addColumn('changes', function ($activity) {
                return $activity->changes;
            })
            ->addColumn('actions', '{!! \'<a href="\'.route(\'admin.activity.show\', $id).\'" class="btn btn-outline-primary btn-sm"><i class="fe fe-eye"></i></a>\' !!}')
            ->make(true);
    }

    public function getOrganizationsSelectListOptions(GetRemoteSelectOptionsRequest $request)
    {
        $query = $request->input('q');

        $limit = 30;

        $organizations = (new OrganizationRepository())->getOrganizationsListForQuery($query, $limit);

        return response()->json([
            'data' => $organizations,
        ]);
    }
}
