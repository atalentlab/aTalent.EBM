@extends('admin.layouts.app')

@section('page-title', __('admin.header.user.my-organization-settings'))

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{__('admin.header.user.my-organization-settings')}}
                    </h1>
                </div>

                @include('admin.partials.notifications')

                @if($entity)

                <form class="js-parsley js-form" method="POST" action="{{ route('admin.my-organization.settings.update') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{__('admin.organizations.organization-information')}}</h3>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            </div>
                        </div>

                        <div class="card-body">

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
                                'placeholder' => __('admin.misc.enter-name'),
                                'required' => true,
                            ])

                            @input([
                                'type' => 'textarea',
                                'name' => 'intro',
                                'label' => __('admin.organizations.organization-introduction'),
                                'placeholder' => __('admin.organizations.org-enter-ind'),
                                'maxLength' => 500,
                            ])

                            @input([
                                'type' => 'url',
                                'name' => 'website',
                                'maxLength' => 255,
                                'placeholder' => 'eg: https://www.example.com',
                                'strict' => true,
                            ])

                            @input([
                                'type' => 'file',
                                'name' => 'logo',
                                'mimeTypes' => 'image/svg+xml',
                                'maxSize' => 50,
                            ])

                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 id="channels" class="card-title">Social Channels (Employer Branding related)</h3>
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
                                        <input type="text" name="channels[{{ $channel['id'] }}][channel_username]" id="channel-{{ $channel['id'] }}" value="{{ old('channels.' .  $channel['id'] . '.channel_username', $channel['channel_username']) }}" class="form-control" aria-describedby="basic-addon-{{ $channel['id'] }}" maxlength="255" placeholder="{{__('admin.misc.enter')}} {{ $channel['name'] }} {{__('admin.misc.enter')}}">
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
                                <a href="{{ route('admin.my-organization.index') }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }}</a>
                                <button type="submit" class="btn btn-primary ml-auto js-submit" data-loading='<i class="fe fe-loader mr-2"></i> Saving...'>
                                    <i class="fe fe-save mr-2"></i> {{ __('admin.misc.save') }}
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
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
        </div>
    </div>

@endsection
