<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <meta name="description" content="{{ __('messages.social-share.message') }}">
    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(!app()->environment('production'))
        {{-- Don't allow search engines to index the site unless it's in production --}}
        {{-- https://developers.google.com/search/docs/advanced/crawling/block-indexing --}}
        <meta name="robots" content="noindex">
    @endif


    {{--<meta property="og:url" content="@yield('og-url', url()->current())">--}}
    <meta property="og:type" content="@yield('og-type', 'website')">
    <meta property="og:title" content="{{ __('messages.social-share.title') }}">
    <meta property="og:summary" content="{{ __('messages.social-share.message') }}">
    <meta property="og:description" content="{{ __('messages.social-share.message') }}">
    <meta property="og:image" content="@yield('og-image', asset('/images/social-share.png'))">

    <title>@yield('page-title', config('app.name', 'Laravel'))</title>

    {{-- Styles --}}
    <link rel="stylesheet" href="{{ mix('/main/css/app.css') }}">
    <link rel="shortcut icon" href="/images/favicon-red.ico">
    @stack('stylesheets')

    <script>
        window.dataLayer = window.dataLayer || [];

        @if(auth()->user())
        window.dataLayer.push({
            'userId': '{{ auth()->user()->id }}'
        });
        @endif
    </script>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{{ config('settings.gtm-container-code') }}');</script>
    <!-- End Google Tag Manager -->
</head>
<body>
@section('body')
    <div class="l-main-container js-main-container {{app()->getLocale()}}" data-lang="{{ app()->getLocale() }}">

        @include('main.partials.main-banner')

        @yield('content')

        @include('main.partials.footer')

        <div class="btn-fixed-right-container">
        <span class="btn-scroll-top js-scroll-top js-nav-item" data-id="discover-ranking"><i class="icon-backTop"></i></span>
        </div>

    </div>

    @stack('lightboxes')
    @include('main.partials.share-lightbox')
    @include('main.partials.wechat-lightbox')
    @show
@show

{{-- Scripts --}}
@stack('scripts-before')
<script src="{{ mix('/main/js/app.js') }}"></script>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('settings.gtm-container-code') }}"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<!-- Start of HubSpot Embed Code -->
<script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/6304879.js"></script>
<!-- End of HubSpot Embed Code -->

@stack('scripts')
</body>
</html>
