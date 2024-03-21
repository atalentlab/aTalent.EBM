<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StorePostRequest;
use App\Models\Channel;
use App\Models\Post;
use App\Models\Organization;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $organization;

    public function __construct(Request $request)
    {
        $id = $request->route('organization');

        $this->organization = Organization::findOrFail($id);
    }

    public function index()
    {
        return view('admin.post.index')->with([
            'organization'  => $this->organization,
            'channels'      => $this->getChannelList(),
        ]);
    }

    public function create()
    {
        return view('admin.post.create')->with([
            'organization'  => $this->organization,
            'channels'      => $this->getChannelList(),
        ]);
    }

    public function store(StorePostRequest $request)
    {
        $post = Post::make($request->all());
        $post->organization_id = $this->organization->id;
        $post->save();

        return redirect()->route('admin.organization.post.index', ['organization' => $this->organization->id])->with('success', 'The post has been created.');
    }

    public function edit($organization, $id)
    {
        return view('admin.post.edit')->with([
            'organization'  => $this->organization,
            'entity'        => $this->getPost($id),
            'channels'      => $this->getChannelList(),
        ]);
    }

    public function update(StorePostRequest $request, $organization, $id)
    {
        $post = $this->getPost($id);
        $post->title = $request->input('title');
        $post->is_actively_fetching = $request->has('is_actively_fetching') ? true : false;
        $post->channel_id = $request->input('channel_id');
        $post->posted_date = $request->input('posted_date');
        $post->post_id = $request->input('post_id');
        $post->url = $request->input('url');
        $post->save();

        return redirect()->route('admin.organization.post.index', ['organization' => $this->organization->id])->with('success', 'The post has been updated.');
    }

    public function delete($organization, $id)
    {
        $post = $this->getPost($id);

        $name = $post->log_title;

        $post->delete();

        return redirect()->route('admin.organization.post.index', ['organization' => $this->organization->id])->with('success', 'Post ' . $name . ' has been deleted.');
    }

    public function getDatatableData(Request $request, $organization)
    {
        $query = Post::where('organization_id', $organization)->with('channel')->withCount('postData');

        if ($channelId = $request->input('channel_id')) {
            $query->where('channel_id', $channelId);
        }

        return datatables()->of($query)
            ->rawColumns(['actions', 'is_actively_fetching', 'title'])
            ->addColumn('channel', function ($post) {
                return $post->channel->name;
            })
            ->editColumn('title', function ($post) {
                return '<span title="' . $post->title . '">' . $post->log_title . '</span>';
            })
            ->editColumn('is_actively_fetching', '{!! $is_actively_fetching ? \'<span class="status-icon bg-success"><span style="display: none;">1</span></span></i>\' : \'<span class="status-icon bg-danger"><span style="display: none;">0</span></span>\' !!}')
            ->addColumn('actions', function ($post) {
                return '<a href="' . route('admin.organization.post.edit', ['organization' => $post->organization_id, 'id' => $post->id]) . '" class="btn btn-outline-primary btn-sm"><i class="fe fe-edit"></i></a>' .
                    '<a href="' . route('admin.post.data.index', ['id' => $post->id]) . '" class="btn btn-secondary btn-sm ml-1" title="View data"><i class="fe fe-database"></i></a>';
            })
            ->make(true);
    }

    private function getChannelList()
    {
        return Channel::orderBy('order')->pluck('name', 'id');
    }

    private function getPost($postId)
    {
        return Post::where('organization_id', $this->organization->id)->where('id', $postId)->firstOrFail();
    }
}
