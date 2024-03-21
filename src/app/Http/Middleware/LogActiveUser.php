<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Cache;

class LogActiveUser
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
        return $next($request);
    }
    
    public function terminate($request, $response)
    {
        // log user activity
        // only updated the database if at least 15 minutes have passed since the last user activity
        if (auth()->user()) {
            $user = auth()->user();

            if (!Cache::get('log-active.' . $user->id)) {
                Cache::put('log-active.' . $user->id, true, 15);
                $user->last_login = Carbon::now();
                $user->save();
            }
        }
    }
}
