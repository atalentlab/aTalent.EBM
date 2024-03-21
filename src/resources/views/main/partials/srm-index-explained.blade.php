<div class="content-container container-position-fix">



    <div class="u-full-width u-p-relative u-pb-120__m"  id="about-srm">

        <div class="l-container">

            {{--<span class="bg-layer bg-layer__right bg-circle">--}}
             {{--<img src="/images/backgrounds/background-circle.svg" alt="" class="">--}}
            {{--</span>--}}

            <div class="l-row u-mb-50">
                <div class="l-cell-l-7 l-cell-m-8 l-cell-s-10 l-cell-x-12">

                    <div class="text--xxxl text--strong u-pb-30 u-text-center--responsive">{{ __('messages.about-srm.title') }}</div>

                    <div class="text--xxxs u-pb-30 text--description"> {{ __('messages.about-srm.desc') }}

                        <div class="u-mtb-30">
                            <div class="text--strong text--xs"> {{ __('messages.about-srm.sub-text-title-1') }} </div>
                            <div class="">
                                {{ __('messages.about-srm.sub-text-1') }}
                            </div>
                        </div>

                        <div class="u-mtb-30">
                            <div class="text--strong text--xs"> {{ __('messages.about-srm.sub-text-title-2') }} </div>
                            <div class="">
                                {{ __('messages.about-srm.sub-text-2') }}
                            </div>
                        </div>

                        <div class="u-mtb-30">
                            <div class="text--strong text--xs"> {{ __('messages.about-srm.sub-text-title-3') }} </div>
                            <div class="">
                                {{ __('messages.about-srm.sub-text-3') }}
                            </div>
                        </div>

                        {{--<div class="u-mt-15"><span class="text--strong"> {{ __('messages.about-srm.sub-text-title-1') }} </span> {{ __('messages.about-srm.sub-text-1') }} </div>--}}
                        {{--<div class="u-mt-15"><span class="text--strong"> {{ __('messages.about-srm.sub-text-title-2') }} </span> {{ __('messages.about-srm.sub-text-2') }}</div>--}}
                        {{--<div class="u-mt-15"><span class="text--strong"> {{ __('messages.about-srm.sub-text-title-3') }} </span> {{ __('messages.about-srm.sub-text-3') }}</div>--}}
                    </div>
                </div>
            </div>

        </div>



        <div class="tab-container l-container-flex u-mt-120 js-animate">
            <div class="tab-container__inner u-full-width">
                <div class="l-container">
                    <div class="tab-container__title text--xl text--strong u-hidden-x">
                        <div class=""><span class="letters text--caps">{{ __('messages.tabs.title.text-1') }}</span></div>
                        <div class=""><span class="letters text--caps">{{ __('messages.tabs.title.text-2') }}</span></div>
                        <div class=""><span class="letters text--caps">{{ __('messages.tabs.title.text-3') }}</span></div>
                    </div>

                    <div class="tab-container__title u-hidden-m u-hidden-s u-hidden-l">
                        <div class="text--xxxs">{{ __('messages.tabs.title.text-1') }} {{ __('messages.tabs.title.text-2') }}</div>
                        <div class="text--xxl text--strong">{{ __('messages.tabs.title.text-3') }}</div>
                    </div>
                </div>
            </div>

            <div class="tab-container__right">
                <div class="tab-container__right-inner">

                    <div class="tabs">
                        <div class="tab-item purple js-tab-item" data-type="purple">
                            <div class="tab-item__inner js-slick-init">
                                <div class="text--xxxxs">{{ __('messages.tabs.tab-item-1.sub-title') }}</div>
                                <div class="text--l text--strong">{{ __('messages.tabs.tab-item-1.title') }}</div>
                            </div>
                        </div>
                        <div class="tab-item purple-medium js-tab-item" data-type="purple-medium">
                            <div class="tab-item__inner">
                                <div class="text--xxxxs">{{ __('messages.tabs.tab-item-2.sub-title') }}</div>
                                <div class="text--l text--strong">{{ __('messages.tabs.tab-item-2.title') }}</div>
                            </div>
                        </div>
                        <div class="tab-item purple-light js-tab-item" data-type="purple-light">
                            <div class="tab-item__inner">
                                <div class="text--xxxxs">{{ __('messages.tabs.tab-item-3.sub-title') }}</div>
                                <div class="text--l text--strong">{{ __('messages.tabs.tab-item-3.title') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content-container__overlay js-tab-content-container__overlay">
                        <div class="purple-medium"></div>
                        <div class="purple-light"></div>
                    </div>

                    {{--slider-purple--}}
                    <div class="tab-content-container js-tab-content-container purple active">

                        <div class=""></div>
                        <div class="tab-left-logo">
                            <img src="/images/social-bg.svg" alt="" class="u-img-responsive js-slick-destroy">
                        </div>

                        <div class="tab-slider-section">

                            {{--for mobile tab--}}
                            <div class="tab-mobile u-full-width">
                                <div class="js-tab-item" data-type="purple">
                                    <div class="text--xxxs">{{ __('messages.tabs.tab-item-1.sub-title') }}</div>
                                    <div class="text--xxl text--strong">{{ __('messages.tabs.tab-item-1.title') }}</div>
                                </div>
                            </div>

                            <div class="intro-text-mobile u-pt-30">
                                {{ __('messages.tabs.tab-item-1.intro-text') }}
                            </div>

                            {{--End of for mobile tab--}}

                            <div class="u-ptb-15 u-hidden-x">
                                {{ __('messages.tabs.tab-item-1.intro-text') }}
                            </div>

                            <div class="js-slider-tab-purple slides">

                                <div class="slick-content">
                                    {{ __('messages.tabs.tab-item-1.wechat.text-1') }} <span class="text--strong">{{ __('messages.tabs.tab-item-1.wechat.name') }}</span> {{ __('messages.tabs.tab-item-1.wechat.text-2') }}
                                </div>

                                <div class="slick-content">
                                    {{ __('messages.tabs.tab-item-1.weibo.text-1') }} <span class="text--strong">{{ __('messages.tabs.tab-item-1.weibo.name') }}</span> {{ __('messages.tabs.tab-item-1.weibo.text-2') }}
                                </div>

                                <div class="slick-content">
                                    {{ __('messages.tabs.tab-item-1.linkedin.text-1') }} <span class="text--strong">{{ __('messages.tabs.tab-item-1.linkedin.name') }}</span> {{ __('messages.tabs.tab-item-1.linkedin.text-2') }}
                                </div>

                                <div class="slick-content">
                                    {{ __('messages.tabs.tab-item-1.kanzhun.text-1') }} <span class="text--strong">{{ __('messages.tabs.tab-item-1.kanzhun.name') }}</span> {{ __('messages.tabs.tab-item-1.kanzhun.text-2') }}
                                </div>

                            </div>
                        </div>

                    </div>

                    {{--slider-purple-medium--}}
                    <div class="tab-content-container js-tab-content-container purple-medium">

                        <div class="tab-left-logo">
                            <img src="/images/icons/activity.svg" alt="" class="u-img-responsive js-slick-destroy">
                        </div>

                        <div class="tab-slider-section">

                            {{--for mobile tab--}}
                            <div class="tab-mobile u-full-width">
                                <div class="js-tab-item" data-type="purple-medium">
                                    <div class="text--xxxs">{{ __('messages.tabs.tab-item-2.sub-title') }}</div>
                                    <div class="text--xxl text--strong">{{ __('messages.tabs.tab-item-2.title') }}</div>
                                </div>
                            </div>

                            <div class="intro-text-mobile u-pt-30">
                                {{ __('messages.tabs.tab-item-2.intro-text') }}
                            </div>
                            {{--End of for mobile tab--}}

                            <div class="u-ptb-15 u-hidden-x">
                                {{ __('messages.tabs.tab-item-2.intro-text') }}
                            </div>

                            <div class="js-slider-tab-purple-medium slides">


                                <div class="slick-content">
                                    {{ __('messages.tabs.tab-item-2.wechat.text-1') }} <span class="text--strong">{{ __('messages.tabs.tab-item-2.wechat.name') }}</span> {{ __('messages.tabs.tab-item-2.wechat.text-2') }}
                                </div>

                                <div class="slick-content">
                                    {{ __('messages.tabs.tab-item-2.weibo.text-1') }} <span class="text--strong">{{ __('messages.tabs.tab-item-2.weibo.name') }}</span> {{ __('messages.tabs.tab-item-2.weibo.text-2') }}
                                </div>

                                <div class="slick-content">
                                    {{ __('messages.tabs.tab-item-2.linkedin.text-1') }} <span class="text--strong">{{ __('messages.tabs.tab-item-2.linkedin.name') }}</span> {{ __('messages.tabs.tab-item-2.linkedin.text-2') }}
                                </div>

                                <div class="slick-content">
                                    {{ __('messages.tabs.tab-item-2.kanzhun.text-1') }} <span class="text--strong">{{ __('messages.tabs.tab-item-2.kanzhun.name') }}</span> {{ __('messages.tabs.tab-item-2.kanzhun.text-2') }}
                                </div>

                            </div>
                        </div>

                    </div>

                    {{--slider-purple-light--}}
                    <div class="tab-content-container js-tab-content-container purple-light">

                        <div class="tab-left-logo">
                            <img src="/images/icons/engagement.svg" alt="" class="u-img-responsive js-slick-destroy">
                        </div>

                        <div class="tab-slider-section">

                            {{--for mobile tab--}}
                            <div class="tab-mobile u-full-width">
                                <div class="js-tab-item" data-type="purple-light">
                                    <div class="text--xxxs">{{ __('messages.tabs.tab-item-3.sub-title') }}</div>
                                    <div class="text--xxl text--strong">{{ __('messages.tabs.tab-item-3.title') }}</div>
                                </div>
                            </div>

                            <div class="intro-text-mobile u-pt-30">
                                {{ __('messages.tabs.tab-item-3.intro-text') }}
                            </div>
                            {{--End of for mobile tab--}}

                            <div class="u-ptb-15 u-hidden-x">
                                {{ __('messages.tabs.tab-item-3.intro-text') }}
                            </div>

                            <div class="js-slider-tab-purple-light slides">

                                <div class="slick-content">
                                    {{ __('messages.tabs.tab-item-3.wechat.text-1') }} <span class="text--strong">{{ __('messages.tabs.tab-item-3.wechat.name') }}</span> {{ __('messages.tabs.tab-item-3.wechat.text-2') }}
                                </div>

                                <div class="slick-content">
                                    {{ __('messages.tabs.tab-item-3.weibo.text-1') }} <span class="text--strong">{{ __('messages.tabs.tab-item-3.weibo.name') }}</span> {{ __('messages.tabs.tab-item-3.weibo.text-2') }}
                                </div>

                                <div class="slick-content">
                                    {{ __('messages.tabs.tab-item-3.linkedin.text-1') }} <span class="text--strong">{{ __('messages.tabs.tab-item-3.linkedin.name') }}</span> {{ __('messages.tabs.tab-item-3.linkedin.text-2') }}
                                </div>

                                <div class="slick-content">
                                    {{ __('messages.tabs.tab-item-3.kanzhun.text-1') }} <span class="text--strong">{{ __('messages.tabs.tab-item-3.kanzhun.name') }}</span> {{ __('messages.tabs.tab-item-3.kanzhun.text-2') }}
                                </div>

                            </div>
                        </div>

                    </div>

                    {{--all slider nav--}}
                    <div class="slider-nav js-slider-nav-purple active"></div>
                    <div class="slider-nav js-slider-nav-purple-medium"></div>
                    <div class="slider-nav js-slider-nav-purple-light"></div>

                </div>
            </div>

        </div>


    </div>







        {{--<span class="bg-layer bg-layer__left bg-square1">--}}
            {{--<span class="u-display-inline">--}}
                {{--<img src="/images/backgrounds/dots.svg" alt="" class="rotation">--}}
            {{--</span>--}}
        {{--</span>--}}

</div>