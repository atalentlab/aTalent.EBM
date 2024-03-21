@extends('admin.layouts.app')

@section('page-title', __('admin.misc.create-new-organization'))

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.misc.create-new-organization') }}
                    </h1>
                </div>

                @include('admin.partials.notifications')
                <form class="js-parsley js-form" method="POST" action="{{ route('admin.organization.store') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.misc.organization-info') }}</h3>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            </div>
                        </div>

                        <div class="card-body">

                            @input([
                                'type' => 'checkbox',
                                'name' => 'published',
                                 'label' => __('admin.misc.published'),
                                 'help' => __('admin.misc.edit-org-desc'),
                            ])

                            @input([
                                'type' => 'checkbox',
                                'name' => 'is_fetching',
                                'label' => __('admin.misc.fetching-data'),
                                'help' => __('admin.misc.create-new-desc'),
                            ])

                            @input([
                                'name' => 'name',
                                'required' => true,
                                 'label' => __('admin.misc.name'),
                                  'placeholder' => __('admin.misc.enter-name'),
                                'maxLength' => 255,
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
                                'label' => __('admin.misc.intro'),
                                 'placeholder' => __('admin.misc.enter-intro'),
                                'maxLength' => 500,
                            ])

                            @input([
                                'type' => 'url',
                                'name' => 'website',
                                 'label' => __('admin.misc.website'),
                                'maxLength' => 255,
                                'placeholder' => 'eg: https://www.example.com',
                                'strict' => true,
                            ])

                            @input([
                                'type' => 'file',
                                'name' => 'logo',
                                'label' => __('admin.misc.logo'),
                                 'placeholder' => __('admin.misc.select-logo'),
                                'mimeTypes' => 'image/svg+xml',
                                'maxSize' => 50,
                                'required' => true,
                            ])

                            @input([
                                'type' => 'select',
                                'name' => 'competitors',
                                'label' => 'Competitors',
                                'label' => __('admin.misc.competitors'),
                                'placeholder' => __('admin.organizations.type-on-organization-name'),
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
                            <h3 class="card-title">{{ __('admin.header.channels') }}</h3>
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
                                        <input type="text" name="channels[{{ $channel['id'] }}][channel_username]" id="channel-{{ $channel['id'] }}" value="{{ old('channels.' .  $channel['id'] . '.channel_username') }}" class="form-control" aria-describedby="basic-addon-{{ $channel['id'] }}" maxlength="255" placeholder="{{ __('admin.misc.enter') }} {{ $channel['name'] }} {{ __('admin.misc.organization') }} {{ __('admin.misc.id') }}">
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
