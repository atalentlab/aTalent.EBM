<?php

namespace App\Http\Controllers\Admin;

use App\Models\Channel;
use App\Models\CrawlerLog;
use App\Models\Organization;
use App\Repositories\PeriodRepository;
use Illuminate\Http\Request;

class CrawlerLogController extends Controller
{
    protected $organization;

    public function __construct(Request $request)
    {
        $id = $request->route('organization');

        $this->organization = Organization::findOrFail($id);
    }

    public function index()
    {
        return view('admin.organization.crawler-log')->with([
            'organization'  => $this->organization,
            'channels'      => $this->getChannelList(),
            'periods'       => $this->getPeriodList(),
        ]);
    }

    public function delete($organization, $id)
    {
        $crawlerLog = $this->getCrawlerLog($id);

        $name = $crawlerLog->log_title;

        $crawlerLog->delete();

        return redirect()->route('admin.organization.crawler-log.index', ['organization' => $this->organization->id])->with('success', $name . ' has been deleted.');
    }

    public function getDatatableData(Request $request, $organization)
    {
        $query = CrawlerLog::select('crawler_log.*')
                             ->where('organization_id', $organization)->with(['channel', 'period']);

        if ($channelId = $request->input('channel_id')) {
            $query->where('channel_id', $channelId);
        }

        if ($periodId = $request->input('period_id')) {
            $query->where('period_id', $periodId);
        }

        return datatables()->of($query)
            ->rawColumns(['is_organization_data_sent', 'message', 'actions'])
            ->addColumn('channel', function ($crawlerLog) {
                return $crawlerLog->channel->name;
            })
            ->editColumn('status', function ($crawlerLog) {
                return $crawlerLog->getStatus();
            })
            ->editColumn('is_organization_data_sent', function ($crawlerLog) {
                return $crawlerLog->is_organization_data_sent ? '<span class="status-icon bg-success"><span style="display: none;">1</span></span></i>' : '<span class="status-icon bg-danger"><span style="display: none;">0</span></span>';
            })
            ->editColumn('message', function ($crawlerLog) {
                return '<span title="' . htmlentities($crawlerLog->message) . '">' . htmlentities($crawlerLog->getMessage()) . '</span>';
            })
            ->editColumn('period.start_date', function ($crawlerLog) {
                return $crawlerLog->period->name_with_year;
            })
            ->addColumn('actions', function ($crawlerLog) {
                return '<button type="button" class="btn btn-outline-danger btn-sm js-delete-modal-trigger" title="Delete crawler log entry" data-toggle="modal" data-target="#delete-modal" data-delete-url="' . route('admin.organization.crawler-log.delete', ['organization' => $crawlerLog->organization_id, 'id' => $crawlerLog->id]) . '"><i class="fe fe-trash"></i></button>';
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

    private function getCrawlerLog($crawlerLogId)
    {
        return CrawlerLog::where('organization_id', $this->organization->id)->where('id', $crawlerLogId)->firstOrFail();
    }
}
