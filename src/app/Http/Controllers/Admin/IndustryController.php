<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreIndustryRequest;
use App\Models\Industry;
use Illuminate\Support\Facades\DB;

class IndustryController extends Controller
{
    public function index()
    {
        return view('admin.industry.index');
    }

    public function create()
    {
        $defaultOrder = 0;

        if ($industry = Industry::orderBy('order', 'desc')->first()) {
            $defaultOrder = $industry->order + 1;
        }

        return view('admin.industry.create')->with(['defaultOrder' => $defaultOrder]);
    }

    public function store(StoreIndustryRequest $request)
    {
        Industry::create($request->all());

        return redirect()->route('admin.industry.index')->with('success', 'The industry has been created.');
    }

    public function edit($id)
    {
        $industry = Industry::findOrFail($id);

        return view('admin.industry.edit')->with([
            'entity' => $industry,
        ]);
    }

    public function update(StoreIndustryRequest $request, $id)
    {
        $industry = Industry::findOrFail($id);
        $industry->name = $request->input('name');
        $industry->order = $request->input('order');
        $industry->published = $request->has('published') ? true : false;
        $industry->save();

        return redirect()->route('admin.industry.index')->with('success', 'The industry has been updated.');
    }

    public function delete($id)
    {
        $industry = Industry::findOrFail($id);

        $name = $industry->name;

        $industry->delete();

        return redirect()->route('admin.industry.index')->with('success', 'Industry ' . $name . ' has been deleted.');
    }

    public function getDatatableData()
    {
        $query = Industry::select('id', 'name', 'order', 'published');

//        TODO:- 'raw query test'
//      $query = Industry::select(DB::raw('id, published, `order`, name->"$.en" as name'));


        return datatables()->of($query)
            ->rawColumns(['actions', 'published'])
            ->orderColumn('published', 'published $1, id $1')
            ->addColumn('industry_name', function ($model) {
                return $model->name;
            })
            ->editColumn('published', '{!! $published ? \'<span class="status-icon bg-success"><span style="display: none;">1</span></span></i>\' : \'<span class="status-icon bg-danger"><span style="display: none;">0</span></span>\' !!}')
            ->addColumn('actions', '{!! \'<a href="\'.route(\'admin.industry.edit\', $id).\'" class="btn btn-outline-primary btn-sm"><i class="fe fe-edit"></i></a>\' !!}')
            ->make(true);
    }
}
