<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\GetPeriodRequest;
use App\Models\Period;
use App\Http\Resources\Period as PeriodResource;
use App\Repositories\PeriodRepository;

/**
 * @group Periods
 *
 * API for retrieving period data
 */
class PeriodController extends Controller
{
    protected $periodRepository;

    public function __construct(PeriodRepository $periodRepository)
    {
        $this->periodRepository = $periodRepository;
    }

    /**
     * Get all periods
     *
     * Retrieve all periods
     *
     * @queryParam start_date only retrieve periods that start after this date
     * @queryParam end_date only retrieve periods that end before this date
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 1,
     *      "published": true,
     *      "name": "Week 15",
     *      "start_date": "2019-04-07T16:00:00.000000Z",
     *      "end_date": "2019-04-14T15:59:59.000000Z"
     *    }
     *  ],
     *  "links": {
     *    "first": "http://localhost/api/v1/period?page=1",
     *    "last": "http://localhost/api/v1/period?page=1",
     *    "prev": null,
     *    "next": null
     *  },
     *  "meta": {
     *    "current_page": 1,
     *    "from": 1,
     *    "last_page": 1,
     *    "path": "http://localhost/api/v1/period",
     *    "per_page": 15,
     *    "to": 1,
     *    "total": 1
     *  }
     * }
     */
    public function list(GetPeriodRequest $request)
    {
        $query = Period::orderBy('start_date', 'asc');

        if ($request->has('start_date')) {
            $query->whereDate('start_date', '>=', $request->input('start_date'));
        }

        if ($request->has('end_date')) {
            $query->whereDate('end_date', '<=', $request->input('end_date'));
        }

        $periods = $query->paginate();

        return PeriodResource::collection($periods);
    }

    /**
     * Get the current period
     *
     * Retrieve the current period
     *
     * @response {
     *  "data": {
     *    "id": 7,
     *    "published": true,
     *    "name": "Week 21",
     *    "start_date": "2019-05-19T16:00:00.000000Z",
     *    "end_date": "2019-05-26T15:59:59.000000Z"
     *  }
     * }
     */
    public function getCurrent()
    {
        $period = $this->periodRepository->getCurrentPeriod();

        return new PeriodResource($period);
    }
}
