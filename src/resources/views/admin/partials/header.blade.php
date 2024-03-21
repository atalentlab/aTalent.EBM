@if(auth()->guard('admin')->user())
<header class="header p-0 fixed-top">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand flex-shrink-0" href="{{ auth()->guard('admin')->user()->getHomepage() }}" title="Home">
                <img src="{{ asset('images/logos/ebm-logo-purple.svg') }}" height="30" width="65" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    @can('view my organization')
                        <li class="nav-item">
                            <a class="nav-link {{ is_active_menu('my-organization.index') }}" href="{{ route('admin.my-organization.index') }}"><i class="fe fe-briefcase mr-2"></i> {{ __('admin.header.my-organization') }}</a>
                        </li>
                    @endcan
                    @can('view statistics dashboard')
                    <li class="nav-item">
                        <a class="nav-link {{ is_active_menu('dashboard') }}" href="{{ route('admin.dashboard.index') }}"><i class="fe fe-bar-chart-2 mr-2"></i> {{ __('admin.header.top-performers') }}</a>
                    </li>
                    @endcan
                    @canany('view organizations', 'view industries', 'view channels', 'view crawler dashboard', 'view api users')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ is_active_menu('management') }}" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fe fe-database mr-2"></i> {{ __('admin.header.data-management') }}
                        </a>

                        <div class="dropdown-menu">
                            @can('view organizations')
                                <a class="dropdown-item" href="{{ route('admin.organization.index') }}"><i class="fe fe-briefcase mr-2"></i>  {{ __('admin.header.organizations') }}</a>
                            @endcan
                            @can('view industries')
                                <a class="dropdown-item" href="{{ route('admin.industry.index') }}"><i class="fe fe-layers mr-2"></i>  {{ __('admin.header.industries') }}</a>
                            @endcan
                            @can('view channels')
                                <a class="dropdown-item" href="{{ route('admin.channel.index') }}"><i class="fe fe-globe mr-2"></i>  {{ __('admin.header.channels') }}</a>
                            @endcan
                            @can('view crawler dashboard')
                                <a class="dropdown-item" href="{{ route('admin.crawler.index') }}"><i class="fe fe-code mr-2"></i> {{ __('admin.header.crawler-dashboard') }}</a>
                            @endcan
                            @can('view api users')
                                <a class="dropdown-item" href="{{ route('admin.api-user.index') }}"><i class="fe fe-link-2 mr-2"></i> {{ __('admin.header.api-access') }}</a>
                            @endcan
                        </div>
                    </li>
                    @endcanany
                    @canany('view users', 'view activity log', 'view notifications')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ is_active_menu('admin') }}" href="#" id="navbarSettings" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fe fe-settings mr-2"></i> {{ __('admin.header.admin') }}
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarSettings">
                            @can('view users')
                            <a class="dropdown-item" href="{{ route('admin.user.index') }}"><i class="fe fe-users mr-2"></i> {{ __('admin.header.users') }}</a>
                            @endcan
                            @can('view activity log')
                            <a class="dropdown-item" href="{{ route('admin.activity.index') }}"><i class="fe fe-activity mr-2"></i> {{ __('admin.header.activity-log') }}</a>
                            @endcan
                            @can('view notifications')
                            <a class="dropdown-item" href="{{ route('admin.notification.index') }}"><i class="fe fe-alert-circle mr-2"></i>  {{ __('admin.header.notifications') }}</a>
                            @endcan
                        </div>
                    </li>
                    @endcanany
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item js-switch-locale">
                        <i class="fe fe-globe nav-link"></i> {{ app()->getLocale() == 'zh' ? '简' : 'En' }}
                        <form action="{{ route('admin.switch-locale') }}" method="POST" class="js-switch-locale-form">
                            @csrf
                            <input type="hidden" name="locale" value="{{ app()->getLocale() == 'zh' ? 'en' : 'zh' }}">
                        </form>
                    </li>

                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle pr-0 leading-none" data-toggle="dropdown" aria-expanded="false">
                            <span class="avatar">{{ mb_substr(auth()->guard('admin')->user()->name, 0, 1, 'utf-8') }}</span>
                            <span class="ml-2 d-none d-lg-block">
                                <span class="text-default">{{ auth()->guard('admin')->user()->name }}</span>
                                @if(auth()->guard('admin')->user()->roles->first())
                                    <small class="text-muted d-block mt-1">{{ auth()->guard('admin')->user()->roles->first()->name }}</small>
                                @endif
                            </span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; transform: translate3d(-56px, 32px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a class="dropdown-item" href="{{ route('admin.profile.show') }}">
                                <i class="dropdown-icon fe fe-user"></i> {{ __('admin.header.user.profile') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('admin.profile.password.edit') }}">
                                <i class="dropdown-icon fe fe-lock"></i> {{ __('admin.header.user.change-password') }}
                            </a>
                            @can('manage my organization')
                            <a class="dropdown-item" href="{{ route('admin.my-organization.settings') }}">
                                <i class="dropdown-icon fe fe-briefcase"></i> {{ __('admin.header.user.my-organization-settings') }}
                            </a>
                            @endcan

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="{{ route('admin.auth.logout') }}">
                                <i class="dropdown-icon fe fe-log-out"></i> {{ __('admin.header.user.sign-out') }}
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
@endif
