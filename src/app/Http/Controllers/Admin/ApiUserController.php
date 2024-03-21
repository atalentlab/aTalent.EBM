<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreApiUserRequest;
use App\Models\ApiUser;
use Illuminate\Support\Str;

class ApiUserController extends Controller
{
    public function index()
    {
        return view('admin.api-user.index');
    }

    public function create()
    {
        return view('admin.api-user.create');
    }

    public function store(StoreApiUserRequest $request)
    {
        $input = $request->all();

        $input['api_token'] = $this->generateToken();

        ApiUser::create($input);

        return redirect()->route('admin.api-user.index')->with('success', 'The API user has been created.');
    }

    public function edit($id)
    {
        $apiUser = ApiUser::findOrFail($id);

        return view('admin.api-user.edit')->with([
            'entity' => $apiUser,
        ]);
    }

    public function update(StoreApiUserRequest $request, $id)
    {
        $apiUser = ApiUser::findOrFail($id);

        if ($request->regenerate_api_token) {
            $apiUser->api_token = $this->generateToken();
        }
        $apiUser->name = $request->input('name');
        $apiUser->activated = $request->has('activated') ? true : false;
        $apiUser->save();

        return redirect()->route('admin.api-user.index')->with('success', 'The API user has been updated.');
    }

    public function delete($id)
    {
        $apiUser = ApiUser::findOrFail($id);

        $name = $apiUser->name;

        $apiUser->delete();

        return redirect()->route('admin.api-user.index')->with('success', 'API user ' . $name . ' has been deleted.');
    }

    public function getDatatableData()
    {
        $query = ApiUser::select('id', 'name', 'api_token', 'activated', 'last_login');

        return datatables()->of($query)
            ->rawColumns(['actions', 'activated'])
            ->orderColumn('activated', 'activated $1, id $1')
            ->editColumn('activated', '{!! $activated ? \'<span class="status-icon bg-success"><span style="display: none;">1</span></span></i>\' : \'<span class="status-icon bg-danger"><span style="display: none;">0</span></span>\' !!}')
            ->addColumn('actions', '{!! \'<a href="\'.route(\'admin.api-user.edit\', $id).\'" class="btn btn-outline-primary btn-sm"><i class="fe fe-edit"></i></a>\' !!}')
            ->make(true);
    }

    private function generateToken()
    {
        return Str::random(60);
    }
}
