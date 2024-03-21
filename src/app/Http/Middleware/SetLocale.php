<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (in_array(request()->segment(1), config('translatable.route-prefix-locales'))) {
            $locale = request()->segment(1);
        } elseif (\session()->has('locale')) {
            $locale =  \session()->get('locale');
            if (in_array($locale, config('translatable.route-prefix-locales'))) {
                return redirect()->to('/zh');
            } else {
                $locale = config('app.fallback_locale');
            }
        } else {
            $locale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
            if (in_array($locale, config('translatable.route-prefix-locales'))) {
                return redirect()->to('/zh');
            } else {
                $locale = config('app.fallback_locale');
            }
        }

        app()->setLocale($locale);





//        app()->setLocale($locale);
//        setLocale(LC_TIME, config('translatable.locales-full')[$locale]['lc_time']);

//        if (in_array(request()->segment(1), config('translatable.locales')) ) {
//            app()->setLocale(request()->segment(1));
//            setlocale(LC_TIME, config('translatable.locales-full')[request()->segment(1)]['lc_time']);
//        } else {
//
//        }
        return $next($request);
    }
}
