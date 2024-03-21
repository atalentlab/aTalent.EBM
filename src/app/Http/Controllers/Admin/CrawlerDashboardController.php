<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\GetCrawlerDashboardIndexRequest;
use App\Repositories\CrawlerLogRepository;
use App\Repositories\PeriodRepository;
use App\Repositories\ChannelRepository;
use Illuminate\Http\Request;
use App\Models\CrawlerLog;

class CrawlerDashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dashboard.crawler')->with([
            'channels'  => $this->getChannelsList(),
            'periods'   => $this->getPeriodList(),
        ]);
    }

    public function getIndexData(GetCrawlerDashboardIndexRequest $request)
    {
        $channelsData = (new CrawlerLogRepository())->getCrawlerStatsPerChannelForPeriod($request->get('period_id'), $request->get('channel_id'));

        return response()->json([
            'channelsTableData' => $channelsData,
        ]);
    }

    public function getErrorsData(Request $request)
    {
        $query = CrawlerLog::select('crawler_log.*')
            ->where('period_id', $request->input('period_id'))
            ->where('status', 'error')
            ->with(['channel', 'organization']);

        if ($request->get('channel_id')) {
            $query->where('channel_id', $request->get('channel_id'));
        }

        return datatables()->of($query)
            ->rawColumns(['is_organization_data_sent', 'message', 'actions'])
            ->editColumn('channel_id', function ($crawlerLog) {
                return $crawlerLog->channel->name;
            })
            ->editColumn('organization_id', function ($crawlerLog) {
                return $crawlerLog->organization->name;
            })
            ->editColumn('is_organization_data_sent', function ($crawlerLog) {
                return $crawlerLog->is_organization_data_sent ? '<span class="status-icon bg-success"><span style="display: none;">1</span></span></i>' : '<span class="status-icon bg-danger"><span style="display: none;">0</span></span>';
            })
            ->editColumn('message', function ($crawlerLog) {
                return '<span title="' . htmlentities($crawlerLog->message) . '">' . htmlentities($crawlerLog->getMessage()) . '</span>';
            })
            ->addColumn('actions', function ($crawlerLog) {
                return '<a href="' . route('admin.organization.crawler-log.index', ['id' => $crawlerLog->organization_id]) . '" class="btn btn-secondary btn-sm ml-1" title="View crawler log"><i class="fe fe-code"></i></a>';
            })
            ->make(true);
    }

    private function getPeriodList()
    {
        return (new PeriodRepository())->getPresentPeriodsList();
    }

    private function getChannelsList()
    {
        return (new ChannelRepository())->getChannelsList();
    }
}
