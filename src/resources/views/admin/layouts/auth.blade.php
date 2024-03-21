@extends('admin.layouts.app')

@section('body')

    <div class="page">

        <div class="m-5">
        <span class="btn btn-outline-primary btn-sm float-right js-switch-locale">
            <span>
            <i class="fe fe-globe nav-link"></i> {{ app()->getLocale() == 'zh' ? '简' : 'En' }}
            <form action="{{ route('admin.switch-locale') }}" method="POST" class="js-switch-locale-form">
                @csrf
                <input type="hidden" name="locale" value="{{ app()->getLocale() == 'zh' ? 'en' : 'zh' }}">
            </form>
            </span>
        </span>
        </div>

        <div class="page-single">
            <div class="container">
                <div class="row">
                    <div class="col col-login mx-auto">

                        <div class="text-center mb-6">
                            <img src="{{ asset('images/logos/ebm-logo-purple.svg') }}" class="h-7" alt="EBM logo">
                        </div>

                        @yield('content')

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
