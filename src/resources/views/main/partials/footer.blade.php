<footer>



    <div class="footer-social-container u-hidden-x">

        {{--<span class="footer-social-icon"><a href="#"> <i class="icon-linkedin text--white"></i> </a> </span>--}}

        <span class="footer-social-icon"> <a href="#" class="js-wechat-lightbox-open"> <i class="icon-wechat text--white"></i> </a></span>

        <span class="footer-social-icon"><a href="https://www.weibo.com/p/1006062690478861" target="_blank"> <i class="icon-weibo text--white"></i></a></span>

    </div>


    <div class="u-full-width pre-footer u-p-relative u-hidden-x">

            <div class="left-overlay-circle"></div>
        <div class="l-container">

            <div class="l-cell-x-6 u-pr-60 text--left">
                <h3 class="text--strong text--white"> {{ __('messages.footer.title') }} </h3>
                <div class="u-pr-15">

                    {{ __('messages.footer.desc') }}

                </div>
            </div>

            <div class="l-cell-x-6 text--left">
                <form action="{{ route('main.newsletter-subscribe') }}" method="POST" class="js-remote-form subscribe-form js-parsley" data-parsley-validate="">
                    @honeypot

                    <div class="js-form-content">
                    <h3 for="newsletter_email" class="text--strong">{{ __('messages.newsletter-form.sub-title') }} </h3>
                        {{--<span class="text--xxxs">({{ __('messages.newsletter-form.sub-title-note') }})</span>--}}

                    <div class="l-row u-mt-15">

                        <div class="l-container-flex u-plr-15">
                            <div class="u-flex  form-wrap">
                                <input type="email" name="email" placeholder="{{ __('messages.newsletter-form.your-email') }}" id="newsletter_email" class="form__field form__input form__input--base"  data-parsley-error-message="{{ __('messages.newsletter-form.validation.your-email') }}" required maxlength="255">
                            </div>

                            <div class="u-flex-none u-ml-15 u-display-inline">
                            <div class="btn-wrap u-display-inline">
                                <button type="submit" class="btn btn-white js-btn-submit">
                                    <span class="btn-over btn-over-white"></span>
                                    <span class="btn-text js-btn-text" data-loading="Sending...">{{ __('messages.cta.subscribe') }}</span>
                                </button>
                            </div>
                            </div>

                        </div>


                    </div>

                        <div class="form-error parsley-custom-error-message text--grey"></div>

                    </div>

                    <div class="js-success-box contact-form__success-message text--center"></div>


                </form>

            </div>
        </div>

    </div>


    <div class="u-full-width pre-footer u-p-relative u-hidden-s u-hidden-m u-hidden-l">

        <div class="u-plr-30">
        <div class="text--xxl text--strong">{{ __('messages.footer.stay-in-touch') }}</div>

        <div class="text--xxxxs u-mt-50 text--justify"> </div>


        <div class="l-container-flex u-mt-50">

            <span class="vertical-center u-plr-15 footer-social">   <a href="#" class="js-wechat-lightbox-open"> <i class="icon-wechat text--white"></i> </a> </span>

            <span class="vertical-center u-plr-15 footer-social"><a href="https://www.weibo.com/p/1006062690478861" target="_blank"> <i class="icon-weibo text--white"></i></a></span>
        </div>

        </div>

    </div>

    <div class="u-full-width u-p-30  u-p-relative text--center text--xxxxs">
        <span>The Employer Branding Monitor™ © {{ date('Y') }}. All Rights Reserved.
            <a href="http://www.beian.miit.gov.cn" class="btn-link--white" target="_blank"> 沪ICP备16053590号-8 </a>
        </span>
    </div>

</footer>
