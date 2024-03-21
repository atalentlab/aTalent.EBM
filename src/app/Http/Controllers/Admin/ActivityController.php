<?php

namespace App\Http\Controllers\Admin;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $logNames = Activity::groupBy('log_name')->orderBy('log_name')->pluck('log_name');

        return view('admin.activity.index')->with(['logNames' => $logNames]);
    }

    public function show($id)
    {
        $activity = Activity::findOrFail($id);

        return view('admin.activity.show')->with(['activity' => $activity]);
    }

    public function getDatatableData(Request $request)
    {
        $query = Activity::with('causer', 'subject');

        if ($log = $request->input('log')) {
            $query->where('log_name', $log);
        }

        return datatables()->of($query)
            ->rawColumns(['actions'])
            ->addColumn('user', function ($activity) {
                return $activity->getCauser();
            })
            ->addColumn('subject', function ($activity) {
                return $activity->getSubject();
            })
            ->addColumn('changes', function ($activity) {
                return $activity->changes;
            })
            ->addColumn('actions', '{!! \'<a href="\'.route(\'admin.activity.show\', $id).\'" class="btn btn-outline-primary btn-sm"><i class="fe fe-eye"></i></a>\' !!}')
            ->make(true);
    }
}
