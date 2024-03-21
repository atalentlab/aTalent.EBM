<?php

namespace App\Http\Controllers\Admin;


class ViewsController extends Controller
{
    public function showEula()
    {
        return view('admin.static.eula');
    }
}
