<?php

namespace App\Http\Middleware;

use Closure;

class ForwardedProto
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
        // set forwarded proto on $_SERVER as some third party libraries only look there for the correct protocol
        if (isset($_SERVER['HTTP_X_CLIENT_SCHEME'])) {
            $_SERVER['HTTP_X_FORWARDED_PROTO'] = $_SERVER['HTTP_X_CLIENT_SCHEME'];
            $request->server->set('HTTP_X_FORWARDED_PROTO', $_SERVER['HTTP_X_FORWARDED_PROTO']);
            $request->headers->set('x-forwarded-proto', $_SERVER['HTTP_X_FORWARDED_PROTO']);
        }
        elseif (isset($_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO'])) {
            $_SERVER['HTTP_X_FORWARDED_PROTO'] = $_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO'];
            $request->server->set('HTTP_X_FORWARDED_PROTO', $_SERVER['HTTP_X_FORWARDED_PROTO']);
            $request->headers->set('x-forwarded-proto', $_SERVER['HTTP_X_FORWARDED_PROTO']);
        }

        return $next($request);
    }
}
