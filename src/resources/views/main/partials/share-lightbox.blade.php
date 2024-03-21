<div class="lightbox-lite js-share-lightbox js-lightbox-lite share-lightbox">
  <div class="lightbox-lite__box">


    <a href="#" class="lightbox-lite-close js-lightbox-lite-close icon-cross"></a>
    <span class="icon-chevron-left js-social-back social-back"></span>


    <div class="lightbox-lite__box-inner">

      <h3 class="text-gray-dark text--center text--strong share-title"> {{ __('messages.social-share.modal-title') }}</h3>

      <div class="lightbox-lite-content-placeholder js-lightbox-lite-content-placeholder">

        <div class="u-full-width js-social-share-icons">

          <div class="l-cell-x-4 text--xxl text--black">
            <span class="link-base js-linkedin-share" data-url="">
            <img src="/images/icons/ic_linkedin.svg" alt="" style="width: 50px;">
            </span>
          </div>
          <div class="l-cell-x-4 text--xxl text--black">
            <span class="link-base js-wechat-share-icon">
             <img src="/images/icons/ic_wechat.svg" alt=""  style="width: 50px;">
            </span>
          </div>
          <div class="l-cell-x-4 text--xxl text--black">
            <span class="link-base js-weibo-share" data-url="" data-image="{{ asset('/images/social-share.png') }}" data-title="{{ __('messages.social-share.message') }}">
            <img src="/images/icons/ic_weibo.svg" alt=""  style="width: 50px;">
            </span>
          </div>
        </div>
        <div class="u-full-width js-wechat-qr">
        <canvas class="l-center-block" id="qrcode"></canvas>
        </div>

      </div>
    </div>
  </div>
</div>
