@extends('admin.layouts.app')

@section('page-title', 'View organization ' . $organization->name)

@section('content')

    <div class="container">
        @include('admin.partials.notifications')

        <div id="my-organization"
             data-data-url="{{ route('admin.organization.show-data', ['id' => $organization->id]) }}"
             data-competition-analysis-data-url="{{ route('admin.organization.show-data.competition-analysis', ['id' => $organization->id]) }}"
             data-competition-analysis-chart-data-url="{{ route('admin.organization.show-data.competition-analysis-chart', ['id' => $organization->id]) }}"
             data-my-organization-settings-url="{{ route('admin.organization.edit', ['id' => $organization->id]) }}"
             data-organizations-list-url="{{ route('admin.organizations.list') }}"
             data-user-permissions="{{ auth()->guard('admin')->user()->getAllPermissions()->pluck('name') }}"
             data-user-info="null"
             data-periods="{{ $periods }}"
             data-channels="{{ $channels }}"
             data-organization-data="{{ $organizationData }}"
             data-organization-incomplete-message="This organization profile is missing some information before we can start collecting data for it."
             data-organization-incomplete-cta="Complete organization profile"
             data-page-type="organization"
             data-my-profile-url="{{ route('admin.profile.show') }}"
             data-locale="{{ app()->getLocale() }}"
        >
            <div class="col-12">
                <div class="card p-5">
                    <div class="card-body">
                        <div class="dimmer active">
                            <div class="loader"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-footer text-right">
                <div class="d-flex">
                    <a href="{{ route('admin.organization.index') }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i>{{ __('admin.misc.back') }}</a>
                    <a href="{{ route('admin.organization.edit', ['id' => $organization->id]) }}" class="btn btn-secondary mr-1"><i class="fe fe-edit mr-2"></i>{{ __('admin.misc.edit') }}</a>
                    @can('manage organizations')
                        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete-modal">
                            <i class="fe fe-trash mr-2"></i> {{ __('admin.misc.delete') }}
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    @push('modals')
        @include('admin.partials.delete-modal', ['action' => route('admin.organization.delete', $organization->id)])
    @endpush

@endsection
