<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\GetChannelRequest;
use App\Models\Channel;
use App\Http\Resources\Channel as ChannelResource;

/**
 * @group Channels
 *
 * API for retrieving channel data
 */
class ChannelController extends Controller
{
    /**
     * Get all channels
     *
     * Retrieve all channels
     *
     * @queryParam channel_id the database ID of the channel
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 1,
     *      "published": true,
     *      "name": "LinkedIn",
     *      "organization_url_prefix": "https://www.linkedin.com/company/",
     *      "organization_url_suffix": null,
     *      "post_max_fetch_age": 30
     *    }
     *  ],
     *  "links": {
     *    "first": "http://localhost/api/v1/channel?page=1",
     *    "last": "http://localhost/api/v1/channel?page=1",
     *    "prev": null,
     *    "next": null
     *  },
     *  "meta": {
     *    "current_page": 1,
     *    "from": 1,
     *    "last_page": 1,
     *    "path": "http://localhost/api/v1/channel",
     *    "per_page": 15,
     *    "to": 1,
     *    "total": 1
     *  }
     * }
     */
    public function list(GetChannelRequest $request)
    {
        $query = Channel::orderBy('order', 'asc');

        if ($request->has('channel_id')) {
            $query->where('id', $request->input('channel_id'));
        }

        $channels = $query->paginate();

        return ChannelResource::collection($channels);
    }
}
