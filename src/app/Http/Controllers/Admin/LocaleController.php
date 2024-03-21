<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switchLocale(Request $request)
    {
        $locale =  $request->input('locale');
        $prevPath = str_replace(url('/'), '', url()->previous());

        if (in_array($locale, config('translatable.route-prefix-locales'))) {
            $path = '/zh'.$prevPath;
        } else {
            $path =  str_replace(url('/zh'), '', url()->previous());
        }
        app()->setLocale($locale);
        return redirect()->to($path);
    }
}
