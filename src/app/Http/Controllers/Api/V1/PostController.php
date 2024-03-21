<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\GetPostDataRequest;
use App\Http\Requests\Api\V1\GetPostRequest;
use App\Http\Requests\Api\V1\StorePostDataRequest;
use App\Http\Requests\Api\V1\StorePostRequest;
use App\Models\Post;
use App\Http\Resources\Post as PostResource;
use App\Http\Resources\PostData as PostDataResource;
use App\Models\PostData;
use Carbon\Carbon;

/**
 * @group Posts
 *
 * API for managing posts and post data
 */
class PostController extends Controller
{
    /**
     * Get all posts
     *
     * Retrieves a list of all posts, filterable by channel and organization
     *
     * @queryParam organization_id the ID of the organization
     * @queryParam channel_id the ID of the channel
     * @queryParam post_id the original ID of the post (as found on its channel)
     * @queryParam is_actively_fetching only posts for which data has to be collected
     * @queryParam max_age only include posts of which the posted date is less than the max age (in days)
     *
     * @response {
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Ut id vel est delectus odio.",
     *       "organization_id": 20,
     *       "channel_id": 2,
     *       "post_id": "tillman.vicente",
     *       "posted_date": "2019-05-16T02:58:48.000000Z",
     *       "is_actively_fetching": true,
     *       "url": "https://www.example.com",
     *       "created_at": "2019-05-24T08:06:46.000000Z",
     *       "updated_at": "2019-05-24T08:06:46.000000Z"
     *     },
     *     {
     *       "id": 3,
     *       "title": "In eaque earum rem et dolorem.",
     *       "organization_id": 23,
     *       "channel_id": 3,
     *       "post_id": "trolfson",
     *       "posted_date": "2019-03-31T14:10:11.000000Z",
     *       "is_actively_fetching": true,
     *       "url": "https://www.example.com",
     *       "created_at": "2019-05-24T08:06:46.000000Z",
     *       "updated_at": "2019-05-24T08:06:46.000000Z"
     *     }
     *   ],
     *   "links": {
     *     "first": "http://localhost/api/v1/post?page=1",
     *     "last": "http://localhost/api/v1/post?page=2",
     *     "prev": null,
     *     "next": "http://localhost/api/v1/post?page=2"
     *   },
     *   "meta": {
     *     "current_page": 1,
     *     "from": 1,
     *     "last_page": 2,
     *     "path": "http://localhost/api/v1/post",
     *     "per_page": 15,
     *     "to": 15,
     *     "total": 24
     *   }
     * }
     */
    public function list(GetPostRequest $request)
    {
        $query = Post::orderBy('created_at', 'desc')
                    ->whereHas('organization', function ($q) use ($request) {
                        $q->where('is_fetching', true);
                    });

        if ($request->has('channel_id')) {
            $query->where('channel_id', $request->input('channel_id'));
        }
        if ($request->has('organization_id')) {
            $query->where('organization_id', $request->input('organization_id'));
        }
        if ($request->has('post_id')) {
            $query->where('post_id', $request->input('post_id'));
        }
        if ($request->has('is_actively_fetching')) {
            $query->where('is_actively_fetching', $request->input('is_actively_fetching'));
        }

        if ($request->has('max_age')) {
            $date = new Carbon('-' . $request->input('max_age') . ' days');

            $query->whereDate('posted_date', '>=', $date);
        }

        $posts = $query->paginate();

        return PostResource::collection($posts);
    }

    /**
     * Get a post
     *
     * Retrieves a specific post
     *
     * @response {
     *   "data": {
     *     "id": 17,
     *     "title": "Tempora in ipsum aut voluptas accusamus et eum eligendi.",
     *     "organization_id": 18,
     *     "channel_id": 3,
     *     "post_id": "karli89",
     *     "posted_date": "2019-05-12T03:51:53.000000Z",
     *     "is_actively_fetching": true,
     *     "url": "https://www.example.com",
     *     "created_at": "2019-05-24T10:14:44.000000Z",
     *     "updated_at": "2019-05-24T10:14:44.000000Z"
     *   }
     * }
     */
    public function show($id)
    {
        return new PostResource($this->getPost($id));
    }

    /**
     * Post a post
     *
     * Updates or stores a post
     *
     * @bodyParam organization_id int required the ID of the organization
     * @bodyParam channel_id int required the ID of the channel
     * @bodyParam post_id string required the original ID of the post (as found on its channel)
     * @bodyParam posted_date date required the original post date (as found on its channel)
     * @bodyParam is_actively_fetching boolean flag indicating whether or not the post keep being crawled
     * @bodyParam title string the title of the post
     * @bodyParam url string the original url of the post (as found on its channel)
     *
     * @response {
     *   "data": {
     *     "id": 31,
     *     "title": "Testing...",
     *     "organization_id": "18",
     *     "channel_id": "3",
     *     "post_id": "23423423423423",
     *     "posted_date": "2019-01-01T16:00:00.000000Z",
     *     "is_actively_fetching": true,
     *     "url": "https://www.example.com",
     *     "created_at": "2019-05-24T10:19:07.000000Z",
     *     "updated_at": "2019-05-24T10:19:07.000000Z"
     *   }
     * }
     */
    public function post(StorePostRequest $request)
    {
        $data = [
            'title'                 => $request->input('title'),
            'posted_date'           => $request->input('posted_date'),
            'url'                   => $request->input('url'),
        ];

        if ($request->has('is_actively_fetching')) {
            $data['is_actively_fetching'] = $request->input('is_actively_fetching');
        }


        $post = Post::firstOrNew([
            'organization_id'   => $request->input('organization_id'),
            'channel_id'        => $request->input('channel_id'),
            'post_id'           => $request->input('post_id'),
        ], $data);

        if ($post->exists) {
            // if the post already exists, update it with the new data except for the posted_date
            // posted_date should be unchangeable
            unset($data['posted_date']);
            $post->fill($data);
        }

        $post->save();

/*
        $post = Post::updateOrCreate([
            'organization_id'   => $request->input('organization_id'),
            'channel_id'        => $request->input('channel_id'),
            'post_id'           => $request->input('post_id'),
        ], $data);*/

        // return fresh result from DB to get default value set by DB
        return new PostResource(Post::find($post->id));
    }

    /**
     * Get a post's data
     *
     * Retrieves a specific post's data
     *
     * @queryParam period_id the ID of the period
     *
     * @response {
     *   "data": [
     *     {
     *       "id": 11,
     *       "post_id": 3,
     *       "period_id": 1,
     *       "like_count": 15082,
     *       "comment_count": 7336,
     *       "share_count": 6609,
     *       "view_count": 632583,
     *       "created_at": "2019-05-24T10:14:45.000000Z",
     *       "updated_at": "2019-05-24T10:14:45.000000Z"
     *     },
     *     {
     *       "id": 12,
     *       "post_id": 3,
     *       "period_id": 1,
     *       "like_count": 55026,
     *       "comment_count": 9218,
     *       "share_count": 3076,
     *       "view_count": 558556,
     *       "created_at": "2019-05-24T10:14:45.000000Z",
     *       "updated_at": "2019-05-24T10:14:45.000000Z"
     *     }
     *   ],
     *   "links": {
     *     "first": "http://localhost/api/v1/post/3/data?page=1",
     *     "last": "http://localhost/api/v1/post/3/data?page=1",
     *     "prev": null,
     *     "next": null
     *   },
     *   "meta": {
     *     "current_page": 1,
     *     "from": 1,
     *     "last_page": 1,
     *     "path": "http://localhost/api/v1/post/3/data",
     *     "per_page": 15,
     *     "to": 12,
     *     "total": 12
     *   }
     * }
     */
    public function listData(GetPostDataRequest $request, $id)
    {
        $post = $this->getPost($id);

        $query = $post->postData();

        if ($request->has('period_id')) {
            $query->where('period_id', $request->input('period_id'));
        }

        return PostDataResource::collection($query->paginate());
    }

    /**
     * Post data for a post
     *
     * Updates or stores data for a specific post
     *
     * @bodyParam period_id int required the ID of the period
     * @bodyParam like_count int number of likes
     * @bodyParam comment_count int number of comments
     * @bodyParam share_count int number of shares
     * @bodyParam view_count int number of views
     *
     * @response {
     *   "data": {
     *     "id": 72,
     *     "post_id": 3,
     *     "period_id": 2,
     *     "like_count": "3333",
     *     "comment_count": "222",
     *     "share_count": null,
     *     "view_count": null,
     *     "created_at": "2019-05-24T10:14:45.000000Z",
     *     "updated_at": "2019-05-27T03:51:55.000000Z"
     *   }
     * }
     */
    public function postData(StorePostDataRequest $request, $id)
    {
        $this->getPost($id);

        $postData = PostData::updateOrCreate([
            'period_id'         => $request->input('period_id'),
            'post_id'           => $id,
        ], [
            'like_count'        =>  $request->input('like_count'),
            'comment_count'     =>  $request->input('comment_count'),
            'share_count'       =>  $request->input('share_count'),
            'view_count'        =>  $request->input('view_count'),
        ]);

        return new PostDataResource($postData);
    }

    /**
     * Delete post data
     *
     * Delete a specific post data
     *
     * @response {
     *   "message": "Successfully deleted."
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [App\\Models\\PostData]."
     * }
     */
    public function deleteData($id)
    {
        $postData = PostData::where('id', $id)
            ->whereHas('post', function ($q) {
                $q->whereHas('organization', function ($q) {
                    $q->where('is_fetching', true);
                });
            })->firstOrFail();

        $postData->delete();

        return response()->json(['message' => 'Successfully deleted.']);
    }

    private function getPost($id)
    {
        return Post::where('id', $id)->whereHas('organization', function ($q) {
            $q->where('is_fetching', true);
        })->firstOrFail();
    }
}
