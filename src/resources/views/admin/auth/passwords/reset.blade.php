@extends('admin.layouts.auth')

@section('page-title', __('admin.log-in.reset-password'))

@section('content')

    <form  class="card js-form js-parsley" method="POST" action="{{ route('admin.auth.password.request') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="card-body p-6">
            <div class="card-title">{{__('admin.log-in.reset-password')}}</div>
            <p class="text-muted">{{__('admin.log-in.reset-desc')}}</p>

            @if (session('status'))
                <div class="alert alert-icon alert-success">
                    <i class="fe fe-check mr-2" aria-hidden="true"></i> {{ session('status') }}
                </div>
            @endif

            <div class="form-group{{ $errors->has('email') ? ' is-invalid' : '' }}">
                <label class="form-label" for="email">{{__('admin.misc.email')}}<span class="form-required">*</span></label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email', $email) }}" maxlength="255" required placeholder="{{__('admin.misc.enter-email')}}" data-parsley-error-message="{{__('admin.validation.error-mail-message')}}">
                @include('admin.partials.form.error', ['field' => 'email'])
            </div>

            <div class="form-group{{ $errors->has('password') ? ' is-invalid' : '' }}">
                <label class="form-label" for="password">{{__('admin.log-in.password')}}<span class="form-required">*</span></label>
                <input id="password" type="password" class="form-control" name="password" value="{{ old('password') }}" minlength="8" maxlength="255" required placeholder="{{__('admin.log-in.enter-password')}}" data-parsley-error-message="{{__('admin.validation.error-message')}}">
                @include('admin.partials.form.error', ['field' => 'password'])
            </div>

            <div class="form-group{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}">
                <label class="form-label" for="password_confirmation">{{__('admin.log-in.confirm-password')}}<span class="form-required">*</span></label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}" minlength="8" maxlength="255" required data-parsley-equalto="#password" placeholder="{{__('admin.log-in.confirm-password')}}" data-parsley-error-message="{{__('admin.validation.error-message')}}">
                @include('admin.partials.form.error', ['field' => 'password_confirmation'])
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary js-submit btn-block mb-2" data-loading='<i class="fe fe-loader mr-2"></i> Resetting...'>{{__('admin.log-in.reset-my-password')}}</button>
            </div>
        </div>

    </form>

@endsection
