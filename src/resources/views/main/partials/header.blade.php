<header class="l-section">
    <div class="u-p-30">
        <div class="brand-logo u-pl-15">
            <img src="/images/logos/ebm-logo-dark.svg" class="u-img-responsive" alt="logo">
        </div>

        <nav class="nav-right u-float-right">
            <a href="#" class="js-nav-item u-hidden-s u-hidden-x" data-id="about-srm"><nav-item> {{ __('messages.cta.about-srm') }} </nav-item></a>
            <a href="#" class="js-nav-item u-hidden-s u-hidden-x" data-id="contact-form"><nav-item> {{ __('messages.cta.how-to-join') }} </nav-item></a>
            <a href="{{ route('admin.auth.login') }}" class="u-hidden-s u-hidden-x"><nav-item> {{ __('messages.cta.login') }} </nav-item></a>
            <nav-item class="u-hidden-s u-hidden-x">
                <div class="btn-wrap">
                    <a href="{{ route('admin.auth.register') }}" class="btn btn-red">
                        <span class="btn-over btn-over-red"></span>
                        <span class="btn-text"> {{ __('messages.cta.register') }}</span>
                    </a>
                </div>
            </nav-item>

            {{--TODO:- language switch--}}
            <span class="u-display-inline u-p-relative switch-postion-mob js-language-dropdown-toggle">

                <div class="language-switch">
                    <span class="link-base text--medium js-switch-locale"><nav-item> {{ app()->getLocale() == 'zh' ? 'En' : '简' }} </nav-item> </span>

                    <form action="{{ route('main.switch-locale') }}" method="POST" class="js-switch-locale-form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="locale" value="{{ app()->getLocale() == 'zh' ? 'en' : 'zh' }}">
                    </form>

                </div>
            </span>


        </nav>
    </div>
</header>
