<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StorePostDataRequest;
use App\Models\PostData;
use App\Models\Post;
use App\Models\Period;
use App\Repositories\PeriodRepository;
use Illuminate\Http\Request;

class PostDataController extends Controller
{
    protected $post;

    public function __construct(Request $request)
    {
        $id = $request->route('post');

        $this->post = Post::findOrFail($id);
    }

    public function index()
    {
        return view('admin.post-data.index')->with([
            'post' => $this->post,
        ]);
    }

    public function create()
    {
        return view('admin.post-data.create')->with([
            'post'      => $this->post,
            'periods'   => $this->getPeriodList(),
        ]);
    }

    public function store(StorePostDataRequest $request)
    {
        $postData = PostData::make($request->all());
        $postData->post_id = $this->post->id;
        $postData->save();

        return redirect()->route('admin.post.data.index', ['post' => $this->post->id])->with('success', 'The data has been created.');
    }

    public function edit($post, $id)
    {
        return view('admin.post-data.edit')->with([
            'post'      => $this->post,
            'entity'    => $this->getPostData($id),
            'periods'   => $this->getPeriodList(),
        ]);
    }

    public function update(StorePostDataRequest $request, $post, $id)
    {
        $postData = $this->getPostData($id);
        $postData->period_id = $request->input('period_id');
        $postData->post_id = $this->post->id;
        $postData->like_count = $request->input('like_count');
        $postData->view_count = $request->input('view_count');
        $postData->comment_count = $request->input('comment_count');
        $postData->share_count = $request->input('share_count');

        $postData->save();

        return redirect()->route('admin.post.data.index', ['post' => $this->post->id])->with('success', 'The data has been updated.');
    }

    public function delete($post, $id)
    {
        $postData = $this->getPostData($id);

        $name = $postData->log_title;

        $postData->delete();

        return redirect()->route('admin.post.data.index', ['post' => $this->post->id])->with('success', 'Data ' . $name . ' has been deleted.');
    }

    public function getDatatableData(Request $request, $post)
    {
        $query = PostData::select('post_data.*')->where('post_id', $post)->with('period');

        return datatables()->of($query)
            ->rawColumns(['actions'])
            ->addColumn('actions', function ($postData) {
                return '<a href="' . route('admin.post.data.edit', ['post' => $postData->post_id, 'id' => $postData->id]) . '" class="btn btn-outline-primary btn-sm"><i class="fe fe-edit"></i></a>';
            })
            ->editColumn('period.name', function ($postData) {
                return $postData->period->name_with_year;
            })
            ->make(true);
    }

    private function getPeriodList()
    {
        return (new PeriodRepository())->getPeriodsList();
    }

    private function getPostData($postDataId)
    {
        return PostData::where('post_id', $this->post->id)->where('id', $postDataId)->firstOrFail();
    }
}
