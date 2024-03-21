<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreChannelRequest;
use App\Models\Channel;
use Illuminate\Support\Facades\DB;

class ChannelController extends Controller
{
    public function index()
    {
        return view('admin.channel.index');
    }

    public function create()
    {
        $defaultOrder = 0;

        if ($channel = Channel::orderBy('order', 'desc')->first()) {
            $defaultOrder = $channel->order + 1;
        }

        return view('admin.channel.create')->with(['defaultOrder' => $defaultOrder]);
    }

    public function store(StoreChannelRequest $request)
    {
        $channel = Channel::make($request->all());
        $channel->uploadFile('logo');
        $channel->save();

        return redirect()->route('admin.channel.index')->with('success', 'The channel has been created.');
    }

    public function edit($id)
    {
        $channel = Channel::findOrFail($id);

        return view('admin.channel.edit')->with([
            'entity'    => $channel,
        ]);
    }

    public function update(StoreChannelRequest $request, $id)
    {
        $channel = Channel::findOrFail($id);
        $channel->name = $request->input('name');
        $channel->order = $request->input('order');
        $channel->published = $request->has('published') ? true : false;

        $channel->organization_url_prefix = $request->input('organization_url_prefix');
        $channel->organization_url_suffix = $request->input('organization_url_suffix');
        $channel->ranking_weight = $request->input('ranking_weight');
        $channel->weight_activity = $request->input('weight_activity');
        $channel->weight_popularity = $request->input('weight_popularity');
        $channel->weight_engagement = $request->input('weight_engagement');
        $channel->post_max_fetch_age = $request->input('post_max_fetch_age');

        $channel->can_collect_views_data = $request->has('can_collect_views_data') ? true : false;
        $channel->can_collect_likes_data = $request->has('can_collect_likes_data') ? true : false;
        $channel->can_collect_comments_data = $request->has('can_collect_comments_data') ? true : false;
        $channel->can_collect_shares_data = $request->has('can_collect_shares_data') ? true : false;

        $channel->uploadFile('logo');

        $channel->save();

        return redirect()->route('admin.channel.index')->with('success', 'The channel has been updated.');
    }

    public function delete($id)
    {
        $channel = Channel::findOrFail($id);

        $name = $channel->name;

        $channel->delete();

        return redirect()->route('admin.channel.index')->with('success', 'Channel ' . $name . ' has been deleted.');
    }

    public function getDatatableData()
    {
        //$query = Channel::withCount('posts');

        $query = Channel::query();

        $query->select('*', 'name');

        $query->withCount('posts');

        return datatables()->of($query)
            ->rawColumns(['actions', 'published'])
            ->addColumn('channel_name', function ($model) {
                return $model->name;
            })
            ->orderColumn('published', 'published $1, id $1')
            ->editColumn('published', '{!! $published ? \'<span class="status-icon bg-success"><span style="display: none;">1</span></span></i>\' : \'<span class="status-icon bg-danger"><span style="display: none;">0</span></span>\' !!}')
            ->addColumn('actions', function ($channel) {
                return '<a href="' . route('admin.channel.edit', ['id' => $channel->id]) . '" class="btn btn-outline-primary btn-sm"><i class="fe fe-edit"></i></a>';
            })
            ->make(true);
    }
}
