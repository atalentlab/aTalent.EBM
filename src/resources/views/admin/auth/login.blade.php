@extends('admin.layouts.auth')

@section('page-title', __('admin.header.log-in'))

@section('content')

    <form  class="card js-form js-parsley" method="POST" action="{{ route('admin.auth.login') }}">
        @csrf

        <div class="card-body p-6">
            <div class="card-title">{{__('admin.log-in.title')}}</div>

            <div class="form-group{{ $errors->has('email') ? ' is-invalid' : '' }}">
                <label class="form-label" for="email">{{__('admin.misc.email')}}</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="{{__('admin.misc.enter-email')}}" data-parsley-error-message="{{__('admin.validation.error-mail-message')}}">
                @include('admin.partials.form.error', ['field' => 'email'])
            </div>

            <div class="form-group{{ $errors->has('password') ? ' is-invalid' : '' }}">
                <label class="form-label" for="password">{{__('admin.log-in.password')}}</label>
                <input id="password" type="password" class="form-control" name="password" required placeholder="{{__('admin.log-in.enter-password')}}" data-parsley-error-message="{{__('admin.validation.error-message')}}">
                @include('admin.partials.form.error', ['field' => 'password'])
            </div>

            <div class="form-group">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span class="custom-control-label">{{__('admin.log-in.remember-me')}}</span>
                </label>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary js-submit btn-block mb-2" data-loading='<i class="fe fe-loader mr-2"></i> Signing in...'>{{__('admin.log-in.sign-in')}}</button>
                <div class="text-center">
                    <a href="{{ route('admin.auth.password.request') }}" class="small">{{__('admin.log-in.i-forgot-password')}}</a>
                </div>
            </div>
        </div>

    </form>

    <div class="text-center text-muted">
        {{__('admin.log-in.dont-have-account')}} <a href="{{ route('admin.auth.register') }}">{{__('admin.log-in.register')}}</a>
    </div>

@endsection
