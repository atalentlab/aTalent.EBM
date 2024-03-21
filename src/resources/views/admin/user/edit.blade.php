@extends('admin.layouts.app')

@section('page-title', __('admin.misc.edit-user') . $entity->name)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.misc.edit-user') }} {{ $entity->name }}
                    </h1>
                </div>

                @include('admin.partials.notifications')

                <form class="js-parsley js-form" method="POST" action="{{ route('admin.user.update', $entity->id) }}" data-parsley-excluded="[disabled=disabled]">
                    @csrf

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
                                'help' => __('admin.misc.edit-user-desc'),
                                'label' => __('admin.misc.activated'),
                            ])

                            @input([
                                'type' => 'checkbox',
                                'name' => 'verified',
                                'help' => __('admin.misc.edit-user-desc'),
                                'label' => __('admin.misc.verified'),
                            ])

                            @input([
                                'name' => 'name',
                                'required' => true,
                                'maxLength' => 255,
                                'label' => __('admin.misc.name'),
                                'placeholder' => __('admin.misc.name'),

                            ])

                            @input([
                                'type' => 'email',
                                'name' => 'email',
                                'required' => true,
                                'maxLength' => 255,
                                'label' => __('admin.misc.email'),
                                'placeholder' => __('admin.misc.email'),
                            ])

                            @input([
                                'name' => 'phone',
                                'placeholder' => __('admin.misc.phone-number'),
                                'required' => false,
                                'maxLength' => 255,
                                 'label' => __('admin.misc.phone-number'),
                            ])

                            @input([
                                'type' => 'select',
                                'name' => 'roles',
                                'items' => $roles,
                                'placeholder' => 'Select roles',
                                'required' => false,
                                'multiple' => true,
                                'relationField' => 'name',
                                'label' => __('admin.misc.roles'),
                                'help' => __('admin.misc.help-user-desc'),
                            ])

                            @input([
                                'type' => 'select',
                                'name' => 'organization_id',
                                'placeholder' => __('admin.organizations.type-on-organization-name'),
                                'required' => false,
                                'remote' => true,
                                'remoteUrl' => route('admin.organizations.list'),
                                'relation' => 'organization',
                                'relationField' => 'name',
                                'label' => __('admin.misc.organization'),
                            ])
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.header.channels') }}</h3>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            @input([
                                'type' => 'checkbox',
                                'name' => 'receives_competitor_report',
                                'label' => __('admin.misc.receive-com-report'),
                                'help' => __('admin.misc.organization-report-desc'),
                            ])

                            @input([
                                'type' => 'checkbox',
                                'name' => 'receives_my_organization_report',
                                'label' => __('admin.misc.receive-org-reg'),
                                'help' => __('admin.misc.organization-report-desc'),
                            ])
                        </div>
                    </div>

                    <div id="active-memberships"
                         data-roles="{{ $membershipRoles->toJson() }}"
                         data-memberships="{{ $activeMemberships->toJson() }}"
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

                    @if($inActiveMemberships->count())
                    <div id="inactive-memberships"
                         data-roles="{{ $membershipRoles->toJson() }}"
                         data-memberships="{{ $inActiveMemberships->toJson() }}"
                         data-card-title="Inactive memberships"
                         data-locale="{{app()->getLocale()}}">
                        <div class="card">
                            <div class="card-body">
                                <div class="dimmer active">
                                    <div class="loader"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="card">
                        <div class="card-footer text-right">
                            <div class="d-flex">
                                <a href="{{ route('admin.user.index') }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }}</a>
                                @can('manage users')
                                    <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete-modal">
                                        <i class="fe fe-trash mr-2"></i> {{ __('admin.misc.delete') }}
                                    </button>
                                    <button type="submit" class="btn btn-primary ml-auto js-submit" data-loading='<i class="fe fe-loader mr-2"></i> Saving...'>
                                        <i class="fe fe-save mr-2"></i> {{ __('admin.misc.save') }}
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>





    @push('modals')
        @include('admin.partials.delete-modal', ['action' => route('admin.user.delete', $entity->id)])
    @endpush

@endsection
