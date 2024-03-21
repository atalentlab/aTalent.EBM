<div id="hoverMoveContainer">
    <div id="hoverMoveContainer__inner" class="bg-home">

        @include('main.partials.header')

        <div class="l-section u-mtb-120__responsive u-text-center">

            <div class="l-container">
                <div class="header-title-section layer5-no-margin">
                    <h1 class="text--xxl text--strong u-pt-30 text--shadow-m banner-title">
                        <span> {{ __('messages.header.title') }} </span>
                    </h1>
                    <div class="u-pt-30 description text--shadow-m">
                        {{ __('messages.header.desc') }}
                    </div>

                    @if(strlen(__('messages.header.desc2')) > 0)
                    <div class="u-pt-15 description text--shadow-m text--center">
                        {{ __('messages.header.desc2') }}
                    </div>
                    @endif

                </div>
            </div>

            <div class="l-container">
                <div class="u-pt-30 layer5-no-margin u-display-inline__m">
                    <div class="l-cell-x-12 l-cell-s-6">
                    <div class="btn-wrap u-mt-15 u-float-left u-mr-15 u-full-width">
                        <a href="{{ route('admin.auth.login') }}" class="btn btn__hero btn-white">
                            <span class="btn-over btn-over-white"></span>
                            <span class="btn-text">{{ __('messages.cta.discover-ranking') }}</span>
                        </a>
                    </div>
                    </div>
                    <div class="l-cell-x-12 l-cell-s-6">
                    <div class="btn-wrap u-mtb-15 u-float-right u-ml-15 u-full-width">
                        <a href="{{ route('admin.auth.register') }}" class="btn btn-red btn__hero">
                            <span class="btn-over btn-over-red"></span>
                            <span class="btn-text">{{ __('messages.cta.register-free') }}</span>
                        </a>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
