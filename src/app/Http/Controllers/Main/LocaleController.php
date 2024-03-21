<?php

namespace App\Http\Controllers\Main;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function switchLocale(Request $request)
    {
        $locale =  $request->input('locale');
        Session::put('locale', $locale);
        if ($locale == 'zh') {
            return redirect()->to('/zh');
        } else {
            return redirect()->to('/');
        }
    }
}
