<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreCrawlerLogRequest;
use App\Models\CrawlerLog;
use App\Http\Resources\CrawlerLog as CrawlerLogResource;

/**
 * @group Log
 *
 * API for managing crawler log entries
 */
class LogController extends Controller
{
    /**
     * Post a a crawler log entry
     *
     * Creates a crawler log entry
     *
     * @bodyParam organization_id int required the ID of the organization
     * @bodyParam channel_id int required the ID of the channel
     * @bodyParam period_id int required the ID of the period
     * @bodyParam is_organization_data_sent boolean required flag indicating whether or not the organization data was successfully sent
     * @bodyParam posts_crawled_count int required the number of posts crawled
     * @bodyParam status string required the crawling status (allowed values: success or error)
     * @bodyParam message string error message to indicate what went wrong during the crawling
     *
     * @response {
     *   "data": {
     *     "id": 31,
     *     "organization_id": "18",
     *     "period_id": "60",
     *     "api_user_id": "1",
     *     "status": "success",
     *     "posts_crawled_count": "5",
     *     "is_organization_data_sent": true,
     *     "message": null,
     *     "crawler_ip": "172.31.0.1",
     *     "created_at": "2019-05-24T10:19:07.000000Z",
     *     "updated_at": "2019-05-24T10:19:07.000000Z"
     *   }
     * }
     */
    public function post(StoreCrawlerLogRequest $request)
    {
        $data = [
            'status'                    => $request->input('status'),
            'message'                   => $request->input('message'),
            'posts_crawled_count'       => $request->input('posts_crawled_count', 0),
            'is_organization_data_sent' => $request->input('is_organization_data_sent', 0),
            'api_user_id'               => auth()->guard('api')->user()->id,
            'crawler_ip'                => $request->ip(),
        ];

        $crawlerLogEntry = CrawlerLog::firstOrNew([
            'organization_id'           => $request->input('organization_id'),
            'channel_id'                => $request->input('channel_id'),
            'period_id'                 => $request->input('period_id'),
        ], $data);

        if ($crawlerLogEntry->exists) {
            $data['crawled_count'] = $crawlerLogEntry->crawled_count + 1;
            $crawlerLogEntry->fill($data);
        }

        $crawlerLogEntry->save();

        // return fresh result from DB to get default value set by DB
        return new CrawlerLogResource(CrawlerLog::find($crawlerLogEntry->id));
    }
}
