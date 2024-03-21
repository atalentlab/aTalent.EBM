@extends('admin.layouts.app')

@section('page-title', __('admin.header.user.profile'))

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.header.user.profile') }}
                    </h1>
                </div>

                @include('admin.partials.notifications')

                <form class="js-parsley js-form" method="POST" action="{{ route('admin.profile.update') }}" data-parsley-excluded="[disabled=disabled]">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.misc.personal-info') }}</h3>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            </div>
                        </div>
                        <div class="card-body">

                            @input([
                                'name' => 'name',
                                'label' => __('admin.misc.name'),
                                'required' => true,
                                'maxLength' => 255,
                                'placeholder' => __('admin.misc.enter-name'),
                            ])

                            @input([
                                'type' => 'email',
                                'name' => 'email',
                                'label' => __('admin.misc.email'),
                                'required' => true,
                                'maxLength' => 255,
                                'placeholder' => __('admin.misc.enter-email'),
                            ])

                            @input([
                                'name' => 'phone',
                                'label' => __('admin.misc.phone'),
                                'placeholder' => __('admin.misc.enter-phone-number'),
                                'required' => false,
                                'maxLength' => 15,
                                'minLength' => 8,
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
                                'disabled' => !$entity->can('change my organization'),
                                'help' => !$entity->can('change my organization') ? 'To change your organization, please <a href="mailto:' . config('settings.support-email') . '?Subject=Change my organization">contact support</a>.' : null,
                            ])
                        </div>
                    </div>


                    @canany('receive organization report', 'receive competitor report')
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title" id="email-preferences">Email Preferences</h3>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($entity->can('receive competitor report'))
                                @input([
                                    'type' => 'checkbox',
                                    'name' => 'receives_competitor_report',
                                    'label' => 'Receive a weekly report on your organization\'s social performance compared to its competitors in your inbox',
                                ])
                            @endif
                            @if($entity->can('receive organization report'))
                                @input([
                                    'type' => 'checkbox',
                                    'name' => 'receives_my_organization_report',
                                    'label' => 'Receive a monthly report on your organization\'s social performance in your inbox',
                                ])
                            @endif
                        </div>
                    </div>
                    @endcanany

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{__('admin.misc.membership-plan')}}</h3>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            {{__('admin.misc.your-cur-mem-plan')}}: <strong>{{ $entity->roles->implode('name', ', ') }}</strong>

                            @if($entity->canApplyForMembership())
                                <a href="#membership-plans" class="btn btn-primary ml-2">{{__('admin.misc.upgrade-plan')}}</a>
                            @endif

                            @foreach($activeMemberships as $activeMembership)
                                <div>
                                    You have {{ $activeMembership->getTimeLeftPretty() }} left on your <strong>{{ $activeMembership->role->name }}</strong> {{ $activeMembership->is_trial ? 'trial' : 'plan' }}.
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="row row-cards">
                        <div class="col-12">
                            <h2 class="text-center mt-6 mb-6" id="membership-plans">{{__('admin.misc.membership-plan')}}</h2>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="card card-md h-100">
                                @if($entity->hasRole(__('admin.misc.basic-user')))
                                    <div class="ribbon ribbon-start bg-primary" title="Your current plan">
                                        {{__('admin.misc.your-plan')}}
                                    </div>
                                @endif

                                <div class="card-body text-center">
                                    <div class="text-uppercase text-muted font-weight-medium">{{__('admin.misc.basic-user')}}</div>
                                    <div class="my-4">
                                        <span class="display-3">$0</span>
                                        <span>/ {{__('admin.misc.year')}}</span>
                                    </div>
                                    <ul class="list-unstyled lh-lg text-left pt-3">
                                        <li class="d-flex align-items-center justify-content-start">
                                            <i class="fe fe-check text-success lead mr-2"></i>
                                            <span>{{__('admin.misc.organization-page')}}</span>
                                        </li>
                                        <li><strong>500</strong> {{__('admin.misc.indexed-organization')}}</li>
                                        <li><strong>4</strong> {{__('admin.misc.social-accounts')}}</li>
                                        <li class="d-flex align-items-center justify-content-start">
                                            <i class="fe fe-check text-success lead mr-2"></i>
                                            <span>{{__('admin.misc.week-top-performer')}}</span>
                                        </li>
                                        <li class="d-flex align-items-center justify-content-start">
                                            <i class="fe fe-x text-danger lead mr-2"></i>
                                            <span>{{__('admin.misc.week-top')}}</span>
                                        </li>
                                        <li class="d-flex align-items-center justify-content-start">
                                            <i class="fe fe-x text-danger lead mr-2"></i>
                                            <span>{{__('admin.misc.direct-competitor-mon')}}</span>
                                        </li>
                                        <li class="d-flex align-items-center justify-content-start">
                                            <i class="fe fe-x text-danger lead mr-2"></i>
                                            <span>{{__('admin.misc.direct-competitor-mon')}}</span>
                                        </li>
                                        <li><strong>1</strong>{{__('admin.misc.user')}}</li>
                                    </ul>
                                </div>
                                <div class="card-footer border-top-0">
                                    <div class="text-center">
                                        @include('admin.profile.plan.cta', ['role' => 'Basic User', 'isPopular' => false])
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-4">
                            <div class="card card-md h-100">
                                @if($entity->hasRole(__('admin.misc.premium-user')))
                                    <div class="ribbon ribbon-start bg-primary" title="Your current plan">
                                        {{__('admin.misc.your-plan')}}
                                    </div>
                                @else
                                    <div class="ribbon ribbon-start bg-green" title="Most popular">
                                        {{__('admin.misc.popular')}}
                                    </div>
                                @endif

                                <div class="card-body text-center">
                                    <div class="text-uppercase text-muted font-weight-medium">{{__('admin.misc.premium-user')}}</div>
                                    <div class="my-4">
                                        <span class="display-3">$1,500</span>
                                        <span>/ {{__('admin.misc.year')}}</span>
                                    </div>
                                    <ul class="list-unstyled lh-lg text-left pt-3">
                                        <li class="d-flex align-items-center justify-content-start">
                                            <i class="fe fe-check text-success lead mr-2"></i>
                                            <span>{{__('admin.misc.organization-page')}}</span>
                                        </li>
                                        <li><strong>500</strong> {{__('admin.misc.indexed-organization')}}</li>
                                        <li><strong>4</strong> {{__('admin.misc.social-accounts')}}</li>
                                        <li class="d-flex align-items-center justify-content-start">
                                            <i class="fe fe-check text-success lead mr-2"></i>
                                            <span>{{__('admin.misc.week-top-performer')}}</span>
                                        </li>
                                        <li class="d-flex align-items-center justify-content-start">
                                            <i class="fe fe-check text-success lead mr-2"></i>
                                            <span>{{__('admin.misc.week-top')}}</span>
                                        </li>
                                        <li><strong>1</strong> {{__('admin.misc.direct-competitor-mon')}}</li>
                                        <li class="d-flex align-items-center justify-content-start">
                                            <i class="fe fe-check text-success lead mr-2"></i>
                                            <span>{{__('admin.misc.automated-report')}}</span>
                                        </li>
                                        <li><strong>3</strong> {{__('admin.header.users')}}</li>
                                        <li class="d-flex align-items-center justify-content-start">
                                            <i class="fe fe-check text-success lead mr-2"></i>
                                            <span>{{__('admin.misc.weeks-free-trial')}}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-footer border-top-0">
                                    <div class="text-center">
                                        @include('admin.profile.plan.cta', ['role' => 'Premium User', 'isPopular' => true])
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-4">
                            <div class="card card-md h-100">
                                @if($entity->hasRole(__('admin.misc.enterprise-user')))
                                    <div class="ribbon ribbon-start bg-primary" title="Your current plan">
                                        {{__('admin.misc.your-plan')}}
                                    </div>
                                @endif

                                <div class="card-body text-center">
                                    <div class="text-uppercase text-muted font-weight-medium">{{__('admin.misc.enterprise-user')}}</div>
                                    <div class="my-4">
                                        <span class="display-3">$3,000</span>
                                        <span>/ {{__('admin.misc.year')}}</span>
                                    </div>
                                    <ul class="list-unstyled lh-lg text-left pt-3">
                                        <li class="d-flex align-items-center justify-content-start">
                                            <i class="fe fe-check text-success lead mr-2"></i>
                                            <span>{{__('admin.misc.organization-page')}}</span>
                                        </li>
                                        <li><strong>500</strong>{{__('admin.misc.indexed-organization')}} </li>
                                        <li><strong>4</strong> {{__('admin.misc.social-accounts')}}</li>
                                        <li class="d-flex align-items-center justify-content-start">
                                            <i class="fe fe-check text-success lead mr-2"></i>
                                            <span>{{__('admin.misc.week-top-performer')}}</span>
                                        </li>
                                        <li class="d-flex align-items-center justify-content-start">
                                            <i class="fe fe-check text-success lead mr-2"></i>
                                            <span>{{__('admin.misc.week-top')}}</span>
                                        </li>
                                        <li><strong>5</strong> {{__('admin.misc.direct-competitor-mon')}}</li>
                                        <li class="d-flex align-items-center justify-content-start">
                                            <i class="fe fe-check text-success lead mr-2"></i>
                                            <span>{{__('admin.misc.automated-report')}}</span>
                                        </li>
                                        <li><strong>5</strong> {{__('admin.header.users')}}</li>
                                        <li class="d-flex align-items-center justify-content-start">
                                            <i class="fe fe-check text-success lead mr-2"></i>
                                            <span>{{__('admin.misc.weeks-free-trial')}}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-footer border-top-0">
                                    <div class="text-center">
                                        @include('admin.profile.plan.cta', ['role' => 'Enterprise User', 'isPopular' => false])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-footer text-right">
                            <div class="d-flex">
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

@endsection
