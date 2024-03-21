@extends('admin.layouts.app')

@section('page-title', __('admin.header.user.change-password'))

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.header.user.change-password') }}
                    </h1>
                </div>

                @include('admin.partials.notifications')

                <form class="card js-parsley js-form" method="POST" action="{{ route('admin.profile.password.update') }}">
                    @csrf

                    <div class="card-body">

                        <div class="form-group{{ $errors->has('password') ? ' is-invalid' : '' }}">
                            <label class="form-label" for="password">{{__('admin.misc.new-password')}}<span class="form-required">*</span></label>
                            <input id="password" type="password" class="form-control" name="password" value="{{ old('password') }}" minlength="8" maxlength="255" required placeholder="{{__('admin.misc.enter-new-password')}}">
                            @include('admin.partials.form.error', ['field' => 'password'])
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}">
                            <label class="form-label" for="password_confirmation">{{__('admin.misc.confirm-new-password')}}<span class="form-required">*</span></label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}" minlength="8" maxlength="255" required data-parsley-equalto="#password" placeholder="{{__('admin.misc.confirm-new-password')}}">
                            @include('admin.partials.form.error', ['field' => 'password_confirmation'])
                        </div>

                    </div>

                    <div class="card-footer text-right">
                        <div class="d-flex">
                            <a href="{{ route('admin.profile.show') }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }} {{__('admin.misc.to-profile')}}</a>
                            <button type="submit" class="btn btn-primary ml-auto js-submit" data-loading='<i class="fe fe-loader mr-2"></i> Saving...'>
                                <i class="fe fe-save mr-2"></i> {{ __('admin.misc.save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
