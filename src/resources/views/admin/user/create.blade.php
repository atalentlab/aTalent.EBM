@extends('admin.layouts.app')

@section('page-title', __('admin.misc.create-new-user'))

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.misc.create-new-user') }}
                    </h1>
                </div>

                @include('admin.partials.notifications')

                <form class="js-parsley js-form" id="create-user-form" data-needs-confirmation="true" method="POST" action="{{ route('admin.user.store') }}" data-parsley-excluded="[disabled=disabled]">
                    @csrf
                    @method('PUT')

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.misc.general') }}</h3>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            @input([
                                'type' => 'checkbox',
                                'name' => 'activated',
                                'label' => __('admin.misc.activated'),
                                'help' => __('admin.misc.edit-user-desc'),
                            ])

                            @input([
                                'name' => 'name',
                                 'label' => __('admin.misc.name'),
                                 'placeholder' => __('admin.misc.enter-name'),
                                'required' => true,
                                'maxLength' => 255,
                            ])

                            @input([
                                'type' => 'email',
                                'name' => 'email',
                                 'label' => __('admin.misc.email'),
                                'placeholder' => __('admin.misc.enter-email'),
                                'required' => true,
                                'maxLength' => 255,
                            ])

                            @input([
                                'name' => 'phone',
                               'label' => __('admin.misc.phone-number'),
                                'placeholder' => __('admin.misc.enter-phone-number'),
                                'required' => false,
                                'maxLength' => 255,
                            ])

                            @input([
                                'type' => 'select',
                                'name' => 'roles',
                                'items' => $roles,
                                'label' => __('admin.misc.roles'),
                                'placeholder' => __('admin.misc.select-roles'),
                                'required' => false,
                                'multiple' => true,
                                'relationField' => 'name',
                                'help' => __('admin.misc.help-user-desc'),
                            ])

                            @input([
                                'type' => 'select',
                                'name' => 'organization_id',
                                'label' => __('admin.misc.organization'),
                                'placeholder' => __('admin.organizations.type-on-organization-name'),
                                'required' => false,
                                'remote' => true,
                                'remoteUrl' => route('admin.organizations.list'),
                                'relation' => 'organization',
                                'relationField' => 'name',
                            ])
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.misc.email-preferences') }}</h3>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            @input([
                                'type' => 'checkbox',
                                'name' => 'receives_competitor_report',
                                'label' => __('admin.misc.receive-com-report'),
                                'help' => __('admin.misc.receive-org-reg-desc'),
                            ])

                            @input([
                                'type' => 'checkbox',
                                'name' => 'receives_my_organization_report',
                                'label' => __('admin.misc.receive-org-reg'),
                                'help' => __('admin.misc.receive-org-reg-desc'),
                            ])
                        </div>
                    </div>

                    <div id="active-memberships"
                         data-roles="{{ $membershipRoles->toJson() }}"
                         data-memberships="[]"
                         data-card-title="{{__('admin.misc.active-membership')}}"
                         data-help-text="{{__('admin.misc.membership-help-text')}}"
                         data-locale="{{app()->getLocale()}}">
                        <div class="card">
                            <div class="card-body">
                                <div class="dimmer active">
                                    <div class="loader"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-footer text-right">
                            <div class="d-flex">
                                <a href="{{ route('admin.user.index') }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }}</a>
                                <button type="submit" class="btn btn-primary ml-auto js-submit" data-loading='<i class="fe fe-loader mr-2"></i> Saving...'>
                                    <i class="fe fe-save mr-2"></i> {{ __('admin.misc.save') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('modals')
        @include('admin.partials.confirmation-modal', ['message' => 'After creating this user a verification email will be sent to their email address with login instructions. Are you sure you want to proceed?', 'target' => '#create-user-form'])
    @endpush

@endsection
