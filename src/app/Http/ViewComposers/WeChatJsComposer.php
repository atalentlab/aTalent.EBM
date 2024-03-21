<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use EasyWeChat;
use EasyWeChat\Kernel\Exceptions\HttpException;
use App\Services\SentryService;

class WeChatJsComposer
{
    public function compose(View $view)
    {
        if (config('settings.enable-wechat-api')) {
            // generate config for WeChat JSSDK (provides custom share msgs)
            try {
                $officialAccount    = EasyWeChat::officialAccount();
                $js                 = $officialAccount->jssdk->buildConfig(['updateTimelineShareData' , 'updateAppMessageShareData'], config('app.debug', false));
                $view->with('weChatJs', $js);
            } catch (HttpException $e) {
                // Send error to sentry instead of crashing the whole app
                SentryService::captureError($e);
            }
        }
    }
}
