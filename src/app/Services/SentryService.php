<?php

namespace App\Services;

class SentryService
{
    public static function captureError($exception)
    {
        if (app()->bound('sentry')) {
            if (!config('app.debug') && app()->environment('production')) {
                app('sentry')->captureException($exception);
            }
        }
    }
}
