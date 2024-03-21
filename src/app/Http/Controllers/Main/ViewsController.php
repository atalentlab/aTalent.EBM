<?php

namespace App\Http\Controllers\Main;

use App\Http\Requests\Main\GetIndexRequest;
use App\Models\ChannelIndex;
use App\Models\SrmIndex;
use App\Http\Resources\Main\SrmIndex as SrmIndexResource;
use App\Http\Resources\Main\ChannelIndex as ChannelIndexResource;
use App\Repositories\ChannelRepository;
use App\Repositories\IndustryRepository;
use App\Repositories\PeriodRepository;

class ViewsController extends Controller
{
    const ITEMS_PER_PAGE = 25;

    /**
     * Show the homepage
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $industries = (new IndustryRepository())->getPublishedIndustryList();
        $periods = (new PeriodRepository())->getPastPeriodListWithIndices();
        $channels = (new ChannelRepository())->getPublishedChannelsNoSort();
        $channelList = $channels->map(function ($item, $key) {
            return $item->only(['id', 'name']);
        });

        return view('main.home')->with([
            'channels'      => $channelList,
            'industries'    => $industries,
            'periods'       => $periods,
        ]);
    }

    /**
     * Fetch EBM table data (JSON)
     *
     * @param GetIndexRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getIndexData(GetIndexRequest $request)
    {
        $periodId = $request->input('period_id');
        $industryId = $request->input('industry_id');
        $channelId = $request->input('channel_id');
        $organizationName = strtolower($request->input('organization'));
        $orderBy = $request->has('order_by')? $request->input('order_by') : 'composite';
        $orderByDirection = $request->has('order_by_direction') ? $request->input('order_by_direction') : 'desc';


        if ($channelId) {
            // return channel indices
            $query = ChannelIndex::where('period_id', $periodId)
                ->where('channel_id', $channelId)
                ->with(['organization'])
                ->orderBy($orderBy, $orderByDirection)
                ->whereHas('organization', function ($query) use ($industryId, $organizationName) {
                    $query->where('published', 1);
                    if ($industryId) {
                        $query->where('industry_id', $industryId);
                    }
                    if ($organizationName) {
                        $query->whereRaw('lower(json_extract(name , "$.zh")) like (?)', ["%{$organizationName}%"])
                            ->orwhereRaw('lower(json_extract(name , "$.en")) like (?)', ["%{$organizationName}%"]);
                    }
                });

            $indices = $query->paginate(self::ITEMS_PER_PAGE);

            return ChannelIndexResource::collection($indices);
        } else {
            // return general indices
            $query = SrmIndex::where('period_id', $periodId)
                ->with(['channelIndices','channelIndices.channel', 'organization'])
                ->orderBy($orderBy, $orderByDirection)
                ->whereHas('organization', function ($query) use ($industryId, $organizationName) {
                    $query->where('published', 1);
                    if ($industryId) {
                        $query->where('industry_id', $industryId);
                    }
                    if ($organizationName) {
                        $query->whereRaw('lower(json_extract(name , "$.zh")) like (?)', ["%{$organizationName}%"])
                        ->orwhereRaw('lower(json_extract(name , "$.en")) like (?)', ["%{$organizationName}%"]);
                    }
                });

            $indices = $query->paginate(self::ITEMS_PER_PAGE);

            return SrmIndexResource::collection($indices);
        }
    }
}
