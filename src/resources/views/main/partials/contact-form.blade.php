<div class="u-full-width">

    <h2 class="text--xxxl text--strong text-left u-mtb-60">{{ __('messages.contact-form.title') }}</h2>

    <div class="u-mtb-30">

        <form action="{{ route('main.register') }}" method="POST" class="contact-form js-contact-form js-remote-form js-parsley" data-parsley-validate="">
            @honeypot

            <div class="js-form-content">

                <div class="u-mb-15"><span class="text--strong text--xs">{{ __('messages.contact-form.sub-title-1') }} </span> <span class="text--xxxs">({{ __('messages.contact-form.sub-title-1-note') }})</span></div>

                <span class="form-wrap">
                    <input type="text" name="name" placeholder="{{ __('messages.contact-form.your-name') }}" class="form__field form__input form__input--base text--italic" maxlength="255" data-parsley-error-message="{{ __('messages.contact-form.validation.your-name') }}" required>
                </span>

                <span class="form-wrap">
                    <input type="text" name="organization" placeholder="{{ __('messages.contact-form.your-company') }}" class="form__field form__input form__input--base text--italic" maxlength="255" data-parsley-error-message="{{ __('messages.contact-form.validation.your-company') }}" required>
                </span>

                <span class="form-wrap">
                    <input type="email" name="email" placeholder="{{ __('messages.contact-form.your-email') }}" class="form__field form__input form__input--base text--italic" maxlength="255" data-parsley-error-message="{{ __('messages.contact-form.validation.your-email') }}" required>
                </span>

                <div class="u-mtb-15 text--center">

                    <div class="form-error parsley-custom-error-message text--m u-mb-15 text--red"></div>

                    <div class="btn-wrap u-mtb-15 u-ml-15 u-display-inline">
                        <button type="submit" class="btn btn-red__mob js-btn-submit u-min-width-130">
                            <span class="btn-over btn-over-red__mob"></span>
                            <span class="btn-text js-btn-text" data-loading="Sending...">{{ __('messages.cta.click-submit') }}</span>
                        </button>
                    </div>
                </div>

            </div>

            <div class="js-success-box contact-form__success-message u-mt-90 text--center"></div>

        </form>
    </div>

</div>
