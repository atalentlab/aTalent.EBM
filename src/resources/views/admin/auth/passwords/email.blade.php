@extends('admin.layouts.auth')

@section('page-title', __('admin.log-in.forgot-password'))

@section('content')

    <form  class="card js-form js-parsley" method="POST" action="{{ route('admin.auth.password.email') }}">
        @csrf

        <div class="card-body p-6">
            <div class="card-title">{{__('admin.log-in.forgot-password')}}</div>
            <p class="text-muted">{{__('admin.log-in.forgot-desc')}}</p>

            @if (session('status'))
                <div class="alert alert-icon alert-success">
                    <i class="fe fe-check mr-2" aria-hidden="true"></i> {{ session('status') }}
                </div>
            @endif

            <div class="form-group{{ $errors->has('email') ? ' is-invalid' : '' }}">
                <label class="form-label" for="email">{{__('admin.misc.email')}}</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="{{__('admin.misc.enter-email')}}" data-parsley-error-message="{{__('admin.validation.error-mail-message')}}">
                @include('admin.partials.form.error', ['field' => 'email'])
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary js-submit btn-block mb-2" data-loading='<i class="fe fe-loader mr-2"></i> Sending...'>{{__('admin.log-in.send-me-new-password')}}</button>
            </div>
        </div>

    </form>

    <div class="text-center text-muted">
        {{__('admin.log-in.forget-it')}} <a href="{{ route('admin.auth.login') }}">{{__('admin.log-in.send-back')}}</a> {{__('admin.log-in.to-sign-screen')}}
    </div>

@endsection
