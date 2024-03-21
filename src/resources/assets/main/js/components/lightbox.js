
/**
 * Share lightbox
 * -------------------------
 */

export function init() {


    let $wechatQR        = $('.js-wechat-qr');
    let $socialContainer = $('.js-social-share-icons');
    let $socialBack      = $('.js-social-back');

    $wechatQR.hide();
    $socialBack.hide();

    window.$shareLightbox = $('.js-share-open').lightboxLite({
        lightbox: '.js-share-lightbox',
        size: 'small',
    });

    $('.js-wechat-share-icon').on('click',function () {
        $socialContainer.fadeOut(function () {
            $wechatQR.fadeIn();
            $socialBack.fadeIn();
        });
    });

    $('.js-lightbox-lite-close').on('click',function () {
        $wechatQR.hide();
        $socialBack.hide();
        $socialContainer.show();
    });

    $('.js-linkedin-share').on('click',function () {
        var url = $(this).attr('data-url');
        window.open('https://www.linkedin.com/sharing/share-offsite/?url='+encodeURIComponent(url), '', 'left=20,top=20,width=500,height=420,personalbar=0,toolbar=0,scrollbars=0,resizable=0');

        //window.open('https://www.linkedin.com/shareArticle?mini=true&url='+encodeURIComponent(url), '', 'left=20,top=20,width=500,height=420,personalbar=0,toolbar=0,scrollbars=0,resizable=0');
    });

    $('.js-weibo-share').on('click',function () {
        var url = $(this).attr('data-url');
        var image = $(this).attr('data-image');
        var message = $(this).attr('data-title');
        var appKey = 2747378020;
        var title = message;
        window.open('http://service.weibo.com/share/share.php?url='+encodeURIComponent(url)+'&appkey='+appKey+'&title='+title+'&pic='+image+'&ralateUid=','','left=20,top=20,width=500,height=420,personalbar=0,toolbar=0,scrollbars=0,resizable=0');
    });

    $socialBack.on('click', function () {
        $(this).fadeOut( function () {
            $wechatQR.hide();
            $socialContainer.fadeIn();
        })
    });



    window.$lightbox = $('.js-wechat-lightbox-open').lightboxLite({
        lightbox: '.js-wechat-lightbox',
        size: 'small',
    });


}
