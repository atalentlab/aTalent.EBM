@extends('admin.layouts.auth')

@section('page-title', 'Account Activation')

@section('content')

    <form  class="card js-form js-parsley" method="POST" action="{{ route('admin.auth.verify.confirm', ['token' => $token]) }}">
        @csrf
        @honeypot

        <div class="card-body p-6">
            <div class="card-title">Account Activation</div>
            <p class="text-muted">Please set a password to activate your account.</p>

            @if (session('status'))
                <div class="alert alert-icon alert-success">
                    <i class="fe fe-check mr-2" aria-hidden="true"></i> {{ session('status') }}
                </div>
            @endif

            <div class="form-group{{ $errors->has('password') ? ' is-invalid' : '' }}">
                <label class="form-label" for="password">Password<span class="form-required">*</span></label>
                <input id="password" type="password" class="form-control" name="password" value="{{ old('password') }}" minlength="8" maxlength="255" required placeholder="Enter password">
                @include('admin.partials.form.error', ['field' => 'password'])
            </div>

            <div class="form-group{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}">
                <label class="form-label" for="password_confirmation">Confirm Password<span class="form-required">*</span></label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}" minlength="8" maxlength="255" required data-parsley-equalto="#password" placeholder="Confirm Password">
                @include('admin.partials.form.error', ['field' => 'password_confirmation'])
            </div>

            <div class="form-group{{ $errors->has('agreed_to_toc') ? ' is-invalid' : '' }}">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="agreed_to_toc" {{ old('agreed_to_toc') ? 'checked' : '' }} required>
                    <span class="custom-control-label">I have read and agree to the <a href="{{ route('admin.static.eula') }}" target="_blank">end-user license agreement</a>.</span>
                </label>
                @include('admin.partials.form.error', ['field' => 'agreed_to_toc'])
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary js-submit btn-block mb-2" data-loading='<i class="fe fe-loader mr-2"></i> Logging in...'>Login to my account</button>
            </div>
        </div>

    </form>

@endsection
