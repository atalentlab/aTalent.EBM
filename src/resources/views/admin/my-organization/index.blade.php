@extends('admin.layouts.app')

@section('page-title', __('admin.misc.my-organization'))

@section('content')

    <div class="container">
        @include('admin.partials.notifications')

        @if($organization)
            <div id="my-organization"
                 data-data-url="{{ route('admin.my-organization.index-data') }}"
                 data-competition-analysis-data-url="{{ route('admin.my-organization.index-data.competition-analysis') }}"
                 data-competition-analysis-chart-data-url="{{ route('admin.my-organization.index-data.competition-analysis-chart') }}"
                 data-my-organization-settings-url="{{ route('admin.my-organization.settings') }}"
                 data-organizations-list-url="{{ route('admin.organizations.list') }}"
                 data-user-permissions="{{ auth()->guard('admin')->user()->getAllPermissions()->pluck('name') }}"
                 data-user-info="{{ $userInfo }}"
                 data-periods="{{ $periods }}"
                 data-channels="{{ $channels }}"
                 data-organization-data="{{ $organizationData }}"
                 data-organization-incomplete-message="Your organization profile is missing some information before we can start collecting data for it."
                 data-organization-incomplete-cta="Complete organization profile"
                 data-page-type="my-organization"
                 data-my-profile-url="{{ route('admin.profile.show') }}"
                 data-upgrade-account-url="{{ route('admin.contact.upgrade-account') }}"
                 data-locale="{{ app()->getLocale() }}">
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
        @else
            <div class="row row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="h3">No organization</div>
                            <p>Your account doesn't have any organization affiliated with it yet.</p>
                            <a href="mailto:{{ config('settings.support-email') }}" target="_blank" class="btn btn-primary">Contact Support</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
