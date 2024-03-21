<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\GetOrganizationRequest;
use App\Models\Channel;
use App\Models\Organization;
use App\Http\Resources\Organization as OrganizationResource;
use App\Http\Requests\Api\V1\StoreOrganizationDataRequest;
use App\Models\OrganizationData;
use App\Http\Resources\OrganizationData as OrganizationDataResource;
use App\Repositories\PeriodRepository;
use Carbon\Carbon;

/**
 * @group Organizations
 *
 * API for managing organizations and organization data
 */
class OrganizationController extends Controller
{
    /**
     * Get all organizations
     *
     * Receives a list of all organizations with their channel data
     *
     * @queryParam channel_id the ID of the channel
     * @queryParam period_id the ID of the period to be used with has_not_been_crawled_during_current_period and has_crawler_errors_for_current_period. If no period_id is specified, the current period will be used
     * @queryParam has_not_been_crawled_during_current_period only organizations which have no crawler log entries for a given period. Has to be used together with channel_id.
     * @queryParam has_crawler_errors_for_current_period only organizations that have crawler errors for a given period. Has to be used together with channel_id.

     * @response {
     *   "data": [
     *     {
     *       "id": 2,
     *       "name": "Howell-McKenzie",
     *       "channels": [
     *         {
     *           "id": 2,
     *           "name": "WeChat",
     *           "username": "francisco43"
     *         }
     *       ]
     *     },
     *     {
     *       "id": 4,
     *       "name": "Gleichner Inc",
     *       "channels": [
     *         {
     *           "id": 1,
     *           "name": "LinkedIn",
     *           "username": "tabbott"
     *         },
     *         {
     *           "id": 2,
     *           "name": "WeChat",
     *           "username": "eryn.wilderman"
     *         }
     *       ]
     *     }
     *   ],
     *   "links": {
     *     "first": "http://localhost/api/v1/organization?page=1",
     *     "last": "http://localhost/api/v1/organization?page=2",
     *     "prev": null,
     *     "next": "http://localhost/api/v1/organization?page=2"
     *   },
     *   "meta": {
     *     "current_page": 1,
     *     "from": 1,
     *     "last_page": 2,
     *     "path": "http://localhost/api/v1/organization",
     *     "per_page": 15,
     *     "to": 15,
     *     "total": 24
     *   }
     * }
     */
    public function list(GetOrganizationRequest $request)
    {
        $query = Organization::where('is_fetching', true)
                                ->with('channels');


        $channel = null;

        if ($request->has('period_id')) {
            $period = (new PeriodRepository())->getPeriodById($request->input('period_id'));
        }
        else {
            $period = (new PeriodRepository())->getCurrentPeriod();
        }

        if ($request->has('channel_id')) {
            $channel = Channel::findOrFail($request->input('channel_id'));
            $query->whereHas('channels', function ($q) use ($channel) {
                $q->where('channels.id', $channel->id);
            });

            if ($request->input('has_not_been_crawled_during_current_period')) {
                $query->whereDoesntHave('crawlerLogs', function ($query) use ($period, $channel) {
                    $query->where('period_id', $period->id)
                        ->where('channel_id', $channel->id);
                });
            }
            elseif ($request->input('has_crawler_errors_for_current_period')) {
                $query->whereHas('crawlerLogs', function ($query) use ($period, $channel) {
                    $query->where('period_id', $period->id)
                        ->where('channel_id', $channel->id)
                        ->where('status', 'error');
                });
            }
        }


        $organizations = $query->paginate();

        return OrganizationResource::collection($organizations);
    }

    /**
     * Get an organization
     *
     * Retrieves a specific organization and its channel data
     *
     * @response {
     *   "data": {
     *     "id": 2,
     *     "name": "Google",
     *     "channels": [
     *       {
     *         "id": 2,
     *         "name": "WeChat",
     *         "username": "francisco43"
     *       }
     *     ]
     *   }
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [App\\Models\\Organization]."
     * }
     */
    public function show($id)
    {
        $organization = Organization::where('is_fetching', true)->where('id', $id)->firstOrFail();

        return new OrganizationResource($organization);
    }

    /**
     * Get an organization's data
     *
     * Retrieves a specific organization's follower data
     *
     * @queryParam channel_id the ID of the channel
     * @queryParam period_id the ID of the period
     *
     * @response {
     *   "data": [
     *     {
     *       "id": 19,
     *       "organization_id": 2,
     *       "channel_id": 3,
     *       "period_id": 1,
     *       "follower_count": 5467582,
     *       "created_at": "2019-05-23T06:49:21.000000Z",
     *       "updated_at": "2019-05-23T06:49:21.000000Z"
     *     },
     *     {
     *       "id": 46,
     *       "organization_id": 2,
     *       "channel_id": 2,
     *       "period_id": 1,
     *       "follower_count": 6858148,
     *       "created_at": "2019-05-23T06:49:21.000000Z",
     *       "updated_at": "2019-05-23T06:49:21.000000Z"
     *     }
     *   ],
     *   "links": {
     *     "first": "http://localhost/api/v1/organization/2/data?page=1",
     *     "last": "http://localhost/api/v1/organization/2/data?page=1",
     *     "prev": null,
     *     "next": null
     *   },
     *   "meta": {
     *     "current_page": 1,
     *     "from": 1,
     *     "last_page": 1,
     *     "path": "http://localhost/api/v1/organization/2/data",
     *     "per_page": 15,
     *     "to": 13,
     *     "total": 13
     *   }
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [App\\Models\\Organization]."
     * }
     */
    public function listData(StoreOrganizationDataRequest $request, $id)
    {
        $organization = Organization::where('is_fetching', true)
            ->where('id', $id)
            ->firstOrFail();

        $query = $organization->organizationData();

        if ($request->has('channel_id')) {
            $query->where('channel_id', $request->input('channel_id'));
        }

        if ($request->has('period_id')) {
            $query->where('period_id', $request->input('period_id'));
        }

        return OrganizationDataResource::collection($query->orderBy('period_id')->paginate());
    }

    /**
     * Post an organization's data
     *
     * Updates or stores organization data for a specific organization.
     * Note: if you post data for a channel the organization doesn't have, you will get a 404 error.
     *
     * @bodyParam channel_id int required the ID of the channel
     * @bodyParam period_id int required the ID of the period
     * @bodyParam follower_count int required the amount of followers
     *
     * @response {
     *   "data": {
     *     "id": 452,
     *     "organization_id": 2,
     *     "channel_id": 4,
     *     "period_id": 2,
     *     "follower_count": "27778",
     *     "created_at": "2019-05-22T11:42:24.000000Z",
     *     "updated_at": "2019-05-23T02:54:56.000000Z"
     *   }
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [App\\Models\\Organization]."
     * }
     */
    public function postData(StoreOrganizationDataRequest $request, $id)
    {
        // check if organization exists and has the specified channel
        Organization::where('is_fetching', true)
            ->where('id', $id)
            ->whereHas('channels', function ($q) use ($request) {
                $q->where('channels.id', $request->input('channel_id'));
            })
            ->firstOrFail();

        $organizationData = OrganizationData::updateOrCreate([
            'organization_id'   => $id,
            'channel_id'        => $request->input('channel_id'),
            'period_id'         => $request->input('period_id'),
        ], [
            'follower_count'    => $request->input('follower_count'),
        ]);

        return new OrganizationDataResource($organizationData);
    }

    /**
     * Get organization data
     *
     * Get specific organization data.
     *
     * @response {
     *   "data": {
     *     "id": 452,
     *     "organization_id": 2,
     *     "channel_id": 4,
     *     "period_id": 2,
     *     "follower_count": "27778",
     *     "created_at": "2019-05-22T11:42:24.000000Z",
     *     "updated_at": "2019-05-23T02:54:56.000000Z"
     *   }
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [App\\Models\\OrganizationData]."
     * }
     */
    public function showData($id)
    {
        $organizationData = OrganizationData::whereHas('organization', function ($q) {
            $q->where('is_fetching', true);
        })
                            ->where('id', $id)
                            ->firstOrFail();

        return new OrganizationDataResource($organizationData);
    }

    /**
     * Delete organization data
     *
     * Delete a specific organization data
     *
     * @response {
     *   "message": "Successfully deleted."
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [App\\Models\\OrganizationData]."
     * }
     */
    public function deleteData($id)
    {
        $organizationData = OrganizationData::whereHas('organization', function ($q) {
            $q->where('is_fetching', true);
        })
            ->where('id', $id)
            ->firstOrFail();

        $organizationData->delete();

        return response()->json(['message' => 'Successfully deleted.']);
    }
}
