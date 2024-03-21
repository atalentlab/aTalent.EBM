@extends('admin.layouts.auth')

@section('page-title', __('admin.auth.register.show.page-title'))

@section('content')

    <form  class="card js-form js-parsley" method="POST" action="{{ route('admin.auth.register.submit') }}">
        @csrf
        @honeypot

        <div class="card-body p-6">
            <div class="card-title">{{ __('admin.auth.register.show.title') }} </div>

            @if (session('status'))
                <div class="alert alert-icon alert-success">
                    <i class="fe fe-check mr-2" aria-hidden="true"></i> {{ session('status') }}
                </div>
            @endif

            @input([
                'name' => 'name',
                'required' => true,
                'maxLength' => 255,
                'label' => __('admin.auth.register.show.form.name.label'),
                'placeholder' => __('admin.auth.register.show.form.name.placeholder'),
                'dataParsleyErrorMessage' => __('admin.validation.error-message'),
            ])

            @input([
                'type' => 'email',
                'name' => 'email',
                'required' => true,
                'maxLength' => 255,
                'label' => __('admin.auth.register.show.form.email.label'),
                'placeholder' => __('admin.auth.register.show.form.email.placeholder'),
                'dataParsleyErrorMessage' => __('admin.validation.error-mail-message'),


            ])

            @input([
                'type' => 'select',
                'name' => 'organization',
                'label' => __('admin.auth.register.show.form.organization.label'),
                'placeholder' => __('admin.auth.register.show.form.organization.placeholder'),
                'required' => true,
                'remote' => true,
                'remoteUrl' => route('admin.organizations.list'),
                'relation' => 'organization',
                'relationField' => 'name',
                'allowCreate' => true,
                'dataParsleyErrorMessage' => __('admin.validation.error-message'),
            ])

            @input([
                'name' => 'phone',
                'required' => false,
                'maxLength' => 15,
                'minLength' => 8,
                'label' => __('admin.auth.register.show.form.phone.label'),
                'placeholder' => __('admin.auth.register.show.form.phone.placeholder'),
                 'dataParsleyErrorMessage' => __('admin.validation.phone-desc'),

            ])

            <div class="form-footer">
                <button type="submit" class="btn btn-primary js-submit btn-block mb-2" data-loading='<i class="fe fe-loader mr-2"></i> {{ __('admin.auth.register.show.form.submit.loading') }}'>{{ __('admin.auth.register.show.form.submit.cta') }}</button>
            </div>
        </div>

    </form>

@endsection
