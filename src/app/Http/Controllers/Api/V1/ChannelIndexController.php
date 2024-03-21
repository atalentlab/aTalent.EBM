<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\GetChannelIndexRequest;
use App\Models\ChannelIndex;
use App\Http\Resources\ChannelIndex as ChannelIndexResource;

/**
 * @group Channel Indices
 *
 * API for retrieving channel index data
 */
class ChannelIndexController extends Controller
{
    /**
     * Get all channel indices
     *
     * Retrieves a list of all channel indices, filterable by channel, period and organization
     *
     * @queryParam organization_id the ID of the organization
     * @queryParam channel_id the ID of the channel
     * @queryParam period_id the ID of the period
     *
     * @response {
     *   "data": [
     *     {
     *       "id": 100180,
     *       "channel_id": 4,
     *       "period_id": 120,
     *       "organization_id": 353,
     *       "follower_count": 1400,
     *       "post_count": 0,
     *       "like_count": 0,
     *       "comment_count": 0,
     *       "share_count": 0,
     *       "view_count": 0
     *     },
     *     {
     *       "id": 99877,
     *       "channel_id": 3,
     *       "period_id": 120,
     *       "organization_id": 353,
     *       "follower_count": 37,
     *       "post_count": 0,
     *       "like_count": 0,
     *       "comment_count": 0,
     *       "share_count": 0,
     *       "view_count": 0
     *     }
     *   ],
     *   "links": {
     *     "first": "http://localhost/api/v1/channel-index?page=1",
     *     "last": "http://localhost/api/v1/channel-index?page=1",
     *     "prev": null,
     *     "next": null
     *   },
     *   "meta": {
     *     "current_page": 1,
     *     "from": 1,
     *     "last_page": 1,
     *     "path": "http://localhost/api/v1/channel-index",
     *     "per_page": 15,
     *     "to": 2,
     *     "total": 2
     *   }
     * }
     */
    public function list(GetChannelIndexRequest $request)
    {
        $query = ChannelIndex::orderBy('created_at', 'desc')
                    ->whereHas('organization', function ($q) use ($request) {
                        $q->where('is_fetching', true);
                    });

        if ($request->has('channel_id')) {
            $query->where('channel_id', $request->input('channel_id'));
        }
        if ($request->has('organization_id')) {
            $query->where('organization_id', $request->input('organization_id'));
        }
        if ($request->has('period_id')) {
            $query->where('period_id', $request->input('period_id'));
        }

        $posts = $query->paginate();

        return ChannelIndexResource::collection($posts);
    }
}
