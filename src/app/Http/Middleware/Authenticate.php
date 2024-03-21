<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as BaseAuthenticate;
use Closure;

class Authenticate extends BaseAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        foreach($guards as $guard) {
            if ($guard === 'admin') {
                // allow only activated and verified users
                if (!$this->auth->guard($guard)->user()->activated || !$this->auth->guard($guard)->user()->verified) {
                    $this->auth->guard($guard)->logout();
                    abort(403);
                }
            }
            elseif ($guard === 'api') {
                // allow only activated API users
                if (!$this->auth->guard($guard)->user()->activated) {
                    return response()->json(['Forbidden'], 403);
                }
            }
        }

        return $next($request);
    }
}
