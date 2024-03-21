<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title', 'Admin') | {{ config('app.name') }}</title>

    {{-- Styles --}}
    <link rel="stylesheet" href="{{ mix('/admin/css/app.css') }}">
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
    <div class="page">
        <div class="flex-fill">
            @include('admin.partials.header')
            <main class="mb-3 mb-md-5 header-fixed-offset">
                @yield('content')
            </main>
        </div>
        @include('admin.partials.footer')
    </div>
@show

@stack('modals')

{{-- Scripts --}}
<script src="{{ mix('/admin/js/app.js') }}"></script>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('settings.gtm-container-code') }}"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
@stack('scripts')
</body>
</html>
