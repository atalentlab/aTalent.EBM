<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreOrganizationDataRequest;
use App\Models\Channel;
use App\Models\OrganizationData;
use App\Models\Organization;
use App\Models\Period;
use App\Repositories\PeriodRepository;
use Illuminate\Http\Request;

class OrganizationDataController extends Controller
{
    protected $organization;

    public function __construct(Request $request)
    {
        $id = $request->route('organization');

        $this->organization = Organization::findOrFail($id);
    }

    public function index()
    {
        return view('admin.organization-data.index')->with([
            'organization'  => $this->organization,
            'channels'      => $this->getChannelList(),
        ]);
    }

    public function create()
    {
        return view('admin.organization-data.create')->with([
            'organization'  => $this->organization,
            'channels'      => $this->getChannelList(),
            'periods'       => $this->getPeriodList(),
        ]);
    }

    public function store(StoreOrganizationDataRequest $request)
    {
        $organizationData = OrganizationData::make($request->all());
        $organizationData->organization_id = $this->organization->id;
        $organizationData->save();

        return redirect()->route('admin.organization.data.index', ['organization' => $this->organization->id])->with('success', 'The data has been created.');
    }

    public function edit($organization, $id)
    {
        return view('admin.organization-data.edit')->with([
            'organization'  => $this->organization,
            'entity'        => $this->getOrganizationData($id),
            'channels'      => $this->getChannelList(),
            'periods'       => $this->getPeriodList(),
        ]);
    }

    public function update(StoreOrganizationDataRequest $request, $organization, $id)
    {
        $organizationData = $this->getOrganizationData($id);
        $organizationData->channel_id = $request->input('channel_id');
        $organizationData->period_id = $request->input('period_id');
        $organizationData->follower_count = $request->input('follower_count');
        $organizationData->save();

        return redirect()->route('admin.organization.data.index', ['organization' => $this->organization->id])->with('success', 'The data has been updated.');
    }

    public function delete($organization, $id)
    {
        $organizationData = $this->getOrganizationData($id);

        $name = $organizationData->log_title;

        $organizationData->delete();

        return redirect()->route('admin.organization.data.index', ['organization' => $this->organization->id])->with('success', 'Data ' . $name . ' has been deleted.');
    }

    public function getDatatableData(Request $request, $organization)
    {
        $query = OrganizationData::select('organization_data.*')
                                    ->where('organization_id', $organization)->with(['channel', 'period']);

        if ($channelId = $request->input('channel_id')) {
            $query->where('channel_id', $channelId);
        }

        return datatables()->of($query)
            ->rawColumns(['actions'])
            ->addColumn('channel', function ($organizationData) {
                return $organizationData->channel->name;
            })
            ->addColumn('actions', function ($organizationData) {
                return '<a href="' . route('admin.organization.data.edit', ['organization' => $organizationData->organization_id, 'id' => $organizationData->id]) . '" class="btn btn-outline-primary btn-sm"><i class="fe fe-edit"></i></a>';
            })
            ->editColumn('period.start_date', function ($organizationData) {
                return $organizationData->period->name_with_year;
            })
            ->make(true);
    }

    private function getChannelList()
    {
        return Channel::orderBy('order')->pluck('name', 'id');
    }

    private function getPeriodList()
    {
        return (new PeriodRepository())->getPeriodsList();
    }

    private function getOrganizationData($organizationDataId)
    {
        return OrganizationData::where('organization_id', $this->organization->id)->where('id', $organizationDataId)->firstOrFail();
    }
}
