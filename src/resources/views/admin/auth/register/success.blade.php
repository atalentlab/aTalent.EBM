@extends('admin.layouts.auth')

@section('page-title', __('admin.auth.register.success.page-title'))

@section('content')

    <div class="card">

        <div class="card-body p-6">
            <div class="alert alert-icon alert-success" role="alert">
                <i class="fe fe-check mr-2" aria-hidden="true"></i> {{ __('admin.auth.register.success.title') }}
            </div>

            <p>{{ __('admin.auth.register.success.copy') }}</p>
        </div>

    </div>

@endsection
