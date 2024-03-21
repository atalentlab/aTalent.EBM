@extends('admin.layouts.app')

@section('body')

    <div class="page">
        <div class="flex-fill">
            <main class="my-3 my-md-5">
                <div class="text-center mb-6">
                    <img src="{{ asset('images/logos/ebm-logo-purple.svg') }}" class="h-7" alt="EBM logo">
                </div>
                @yield('content')
            </main>
        </div>
    </div>

@endsection
