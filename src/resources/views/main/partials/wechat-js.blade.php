@if(isset($weChatJs))

    <script src="https://res.wx.qq.com/open/js/jweixin-1.6.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        var title = document.title;
        var hash = window.location.hash.substr(1);

        if (hash) {
            var result = hash.split('&').reduce(function (result, item) {
                var parts = item.split('=');
                result[parts[0]] = parts[1];
                return result;
            }, {});

            if (result['organization']) {
                title = result['organization'] + ' - EBM';
            }
        }

        wx.config({!! $weChatJs !!});
        wx.ready(function () {
            let shareData = {
                title: title,
                desc: '{{ __('messages.wechat-share.message') }}',
                link: window.location.href,
                imgUrl: '{{ asset('/images/wechat-share.jpg') }}'
            };
            wx.updateAppMessageShareData(shareData);
            wx.updateTimelineShareData(shareData);
        });
        wx.error(function (res) {
            // alert(res.errMsg);
        });
    </script>

@endif
