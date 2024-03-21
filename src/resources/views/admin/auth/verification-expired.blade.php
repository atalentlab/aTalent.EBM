@extends('admin.layouts.auth')

@section('page-title', 'User Registration Verification Expired')

@section('content')

    <div class="card">

        <div class="card-body p-6">
            <div class="card-title">User Registration Verification Expired</div>

            <p>Your verification token has expired. Please check your email for a new account verification link.</p>

            <div class="card-footer">
                <a href="{{ route('admin.auth.login') }}" class="btn btn-primary btn-block">Back to the login page</a>
            </div>
        </div>

    </div>

@endsection
