@extends('main.layouts.app')

@section('page-title',  __('messages.header.page-title') )

@section('content')

    @include('main.partials.main-table')

    <main>
        @include('main.partials.srm-index-explained')

        <div class="content-container container-position-fix">

            <div class="u-full-width u-mt-60 u-mb-120 u-p-relative">

                <div class="l-container l-container-no-p-m">

                    <div class="l-row bg-red_m" id="contact-form">

                        {{--<span class="bg-layer bg-layer__left bg-circle__how-to-join">--}}
                        {{--<img src="/images/backgrounds/background-circle.svg" alt="" class="u-hidden-x">--}}
                        {{--</span>--}}

                        <div class="l-cell-x-12 l-cell-s-6 u-hidden-x">

                            <div class="u-full-width u-pr-30--m">
                                @include('main.partials.how-to-join')
                            </div>
                        </div>

                        <div class="l-cell-x-12 l-cell-s-6">
                            <div class="u-full-width u-pl-30--m">
                                @include('main.partials.contact-form')
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </main>

@endsection

@push('scripts')

    @include('main.partials.wechat-js')

@endpush
