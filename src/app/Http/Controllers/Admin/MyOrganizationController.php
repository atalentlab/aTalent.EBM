<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\GetMyOrganizationChartDataRequest;
use App\Http\Requests\Admin\GetMyOrganizationDataRequest;
use App\Http\Requests\Admin\StoreMyOrganizationRequest;
use App\Models\Organization;
use App\Models\Period;
use App\Models\SrmIndex;
use App\Repositories\ChannelRepository;
use App\Repositories\IndexRepository;
use App\Repositories\PeriodRepository;
use App\Repositories\OrganizationRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use App\Models\Industry;
use App\Models\Channel;
use Illuminate\Support\Facades\DB;
use App\Traits\ProvidesOrganizationData;

class MyOrganizationController extends Controller
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
        $organization = $this->guard()->user()->organization;

        if (!$organization) {
            return view('admin.my-organization.index')->with([
                'organization' => $organization,
            ]);
        }

        $channels = $this->channelRepository->getPublishedChannelsNoSort()->sortBy('order');

        $organizationData = $this->getOrganizationGeneralInfo($organization, $channels);

        $userInfo = $this->guard()->user()->only(['name', 'phone', 'email']);
        $userInfo['organization'] = [
            'id' => $organization->id,
            'name' => $organization->name,
        ];

        return view('admin.my-organization.index')->with([
            'organization' => $organization,
            'organizationData' => json_encode($organizationData),
            'periods' => $this->periodRepository->getAllPresentPeriodsWithIndicesForOrganization($organization->id),
            'channels' => $channels->map->only(['id', 'name'])->prepend(['id' => null, 'name' => __('admin.top-performers.all-channels')]),
            'userInfo' => json_encode($userInfo),
        ]);
    }

    public function settings()
    {
        $organization = $this->guard()->user()->organization;

        return view('admin.my-organization.settings')->with([
            'entity' => $organization,
            'industries' => $this->getIndustryList(),
            'channels' => $this->getChannelList($organization),
        ]);
    }

    public function update(StoreMyOrganizationRequest $request)
    {
        $organization = $this->guard()->user()->organization;

        $organization->name = $request->input('name');
        $organization->industry_id = $request->input('industry_id');
        $organization->intro = $request->input('intro');
        $organization->website = $request->input('website');

        $organization->uploadFile('logo');

        $organization->save();

        $organization->channels()->sync($request->input('channels'));

        return redirect()->route('admin.my-organization.settings')->with('success', 'Your organization settings have been updated.');
    }

    public function getIndexData(GetMyOrganizationDataRequest $request)
    {
        $organization = $this->guard()->user()->organization;

        $period = $this->periodRepository->getPeriodById($request->get('period_id'));

        if (!$organization) response()->json(['Organization not found'], 404);

        $data = [
            'rank_info' => $this->getRankInfoData($period, $organization),
            'cross_channel_stats' => $this->getCrossChannelStats($period, $organization),
        ];

        return response()->json($data);
    }

    public function getCompetitionAnalysisData(GetMyOrganizationDataRequest $request)
    {
        $organization = $this->guard()->user()->organization;

        $period = $this->periodRepository->getPeriodById($request->get('period_id'));
        $channel = Channel::find($request->get('channel_id'));

        if (!$organization) response()->json(['Organization not found'], 404);

        $addCompetitorSuccess = $this->addCompetitor($organization, $request->get('competitor_id'));

        $data = [
            'can_add_competitors' => $this->guard()->user()->canAddCompetitor($organization),
            'table' => $this->getCompetitionAnalysisTableStats($period, $organization, $channel, true),
            'add_competitor_success' => $addCompetitorSuccess,
        ];

        return response()->json($data);
    }

    public function getCompetitionAnalysisChartData(GetMyOrganizationChartDataRequest $request)
    {
        $organization = $this->guard()->user()->organization;

        $period = $this->periodRepository->getPeriodById($request->get('period_id'));
        $channel = Channel::find($request->get('channel_id'));
        $metric = $request->get('metric');

        if (!$organization) response()->json(['Organization not found'], 404);

        $data = $this->getCompetitionAnalysisChartStats($period, $organization, $metric, $channel);

        return response()->json($data);
    }
}
