@extends('admin.layouts.app')

@section('page-title', __('admin.organizations.title') . $entity->name)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.organizations.title') }} {{ $entity->name }}
                    </h1>
                    <div class="ml-auto">
                        @can('view posts')
                        <a href="{{ route('admin.organization.post.index', ['id' => $entity->id]) }}" class="btn btn-secondary ml-1" title="View posts">{{ __('admin.misc.view-posts') }}</a>
                        @endcan
                        @can('view organization data')
                        <a href="{{ route('admin.organization.data.index', ['id' => $entity->id]) }}" class="btn btn-secondary ml-1" title="View data">{{ __('admin.misc.view-data') }}</a>
                        @endcan
                        @can('view activity log')
                        <a href="{{ route('admin.organization.activity', ['id' => $entity->id]) }}" class="btn btn-secondary ml-1" title="View activity">{{ __('admin.organizations.view-activity-log') }}</a>
                        @endcan
                        @can('view crawler dashboard')
                        <a href="{{ route('admin.organization.crawler-log.index', ['id' => $entity->id]) }}" class="btn btn-secondary ml-1" title="View crawler log">{{ __('admin.organizations.view-crawler-log') }}</a>
                        @endcan
                    </div>
                </div>

                @include('admin.partials.notifications')
                <form class="js-parsley js-form" method="POST" action="{{ route('admin.organization.update', $entity->id) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.organizations.organization-information') }}</h3>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            </div>
                        </div>

                        <div class="card-body">

                            @input([
                                'type' => 'checkbox',
                                'name' => 'published',
                                'help' => __('admin.misc.edit-org-desc'),
                                 'label' => __('admin.misc.published'),
                            ])

                            @input([
                                'type' => 'checkbox',
                                'name' => 'is_fetching',
                                'label' => __('admin.misc.fetching-data'),
                                'help' => __('admin.misc.edit-fet-desc'),
                            ])

                            @input([
                                'name' => 'name',
                                'required' => true,
                                'maxLength' => 255,
                                'label' => __('admin.misc.name'),
                                'placeholder' => __('admin.misc.enter-name'),

                            ])

                            @input([
                                'type' => 'select',
                                'name' => 'industry_id',
                                'items' => $industries,
                                'label' => __('admin.misc.industry'),
                                'placeholder' => __('admin.misc.select-an-industry'),
                                'required' => true,
                            ])

                            @input([
                                'type' => 'textarea',
                                'name' => 'intro',
                                'placeholder' => __('admin.misc.enter-intro'),
                                'maxLength' => 500,
                                'label' => __('admin.misc.intro'),

                            ])

                            @input([
                                'type' => 'url',
                                'name' => 'website',
                                'maxLength' => 255,
                                'placeholder' => 'eg: https://www.example.com',
                                'strict' => true,
                                 'label' => __('admin.misc.website'),

                            ])

                            @input([
                                'type' => 'file',
                                'name' => 'logo',
                                'mimeTypes' => 'image/svg+xml',
                                'maxSize' => 50,
                              'label' => __('admin.misc.logo'),

                            ])

                            @input([
                                'type' => 'select',
                                'name' => 'competitors',
                                'label' => __('admin.misc.competitors'),
                                'placeholder' => __('admin.misc.type-on-org'),
                                'required' => false,
                                'remote' => true,
                                'remoteUrl' => route('admin.organizations.list'),
                                'relation' => 'competitors',
                                'relationField' => 'name',
                                'multiple' => true,
                                'maxItems' => 5,
                                'help' => __('admin.organizations.max'),
                            ])

                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 id="channels" class="card-title">{{ __('admin.header.channels') }}</h3>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            </div>
                        </div>

                        <div class="card-body">

                            @foreach($channels as $channel)
                                <div class="form-group{{ $errors->has('channels.' . $channel['id'] . '.channel_username') ? ' is-invalid' : '' }}">
                                    <label class="form-label" for="channel-{{ $channel['id'] }}">{{ $channel['name'] }}</label>
                                    <div class="input-group">
                                        @if($channel['url_prefix'])
                                            <span class="input-group-prepend" id="basic-addon-{{ $channel['id'] }}">
                                                <span class="input-group-text">{{ $channel['url_prefix'] }}</span>
                                            </span>
                                        @endif
                                        <input type="text" name="channels[{{ $channel['id'] }}][channel_username]" id="channel-{{ $channel['id'] }}" value="{{ old('channels.' .  $channel['id'] . '.channel_username', $channel['channel_username']) }}" class="form-control" aria-describedby="basic-addon-{{ $channel['id'] }}" maxlength="255" placeholder="{{ __('admin.misc.enter') }} {{ $channel['name'] }} {{ __('admin.misc.organization') }} {{ __('admin.misc.id') }}">
                                        @if($channel['url_suffix'])
                                            <span class="input-group-append" id="basic-addon-append-{{ $channel['id'] }}">
                                                <span class="input-group-text">{{ $channel['url_suffix'] }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    @include('admin.partials.form.error', ['field' => 'channels.' . $channel['id'] . '.channel_username'])
                                </div>
                            @endforeach

                        </div>

                    </div>

                    <div class="card">
                        <div class="card-footer text-right">
                            <div class="d-flex">
                                <a href="{{ route('admin.organization.index') }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }}</a>
                                <a href="{{ route('admin.organization.show', ['id' => $entity->id]) }}" class="btn btn-secondary mr-1"><i class="fe fe-eye mr-2"></i>{{ __('admin.misc.view') }}</a>

                                @can('manage organizations')
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
        @include('admin.partials.delete-modal', ['action' => route('admin.organization.delete', $entity->id)])
    @endpush

@endsection
