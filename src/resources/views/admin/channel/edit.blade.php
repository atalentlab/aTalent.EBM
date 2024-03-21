@extends('admin.layouts.app')

@section('page-title', __('admin.misc.edit-channel') . $entity->name)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.misc.edit-channel') }}
                        {{ $entity->name }}
                    </h1>
                </div>

                @include('admin.partials.notifications')

                <form class="js-parsley js-form" method="POST" action="{{ route('admin.channel.update', $entity->id) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"> {{ __('admin.header.channels') }}</h3>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            </div>
                        </div>

                        <div class="card-body">

                            @input([
                            'type' => 'checkbox',
                            'name' => 'published',
                            'help' => __('admin.misc.edit-cha-desc'),
                            'label' => __('admin.misc.published'),

                            ])

                            @input([
                            'name' => 'name',
                            'maxLength' => 255,
                            'required' => true,
                            'label' => __('admin.misc.name'),
                            'placeholder' => __('admin.misc.name'),

                            ])

                            @input([
                            'type' => 'number',
                            'name' => 'order',
                            'required' => true,
                            'min' => 0,
                            'label' => __('admin.misc.order'),
                            'placeholder' => __('admin.misc.enter-order'),

                            ])

                            @input([
                            'type' => 'file',
                            'name' => 'logo',
                            'mimeTypes' => 'image/svg+xml',
                            'maxSize' => 50,
                            'label' => __('admin.misc.logo'),

                            ])

                            @input([
                            'name' => 'organization_url_prefix',
                            'maxLength' => 255,
                            'label' => __('admin.misc.organization-url-pre'),
                            'placeholder' => 'eg: https://www.linkedin.com/company/',

                            ])

                            @input([
                            'name' => 'organization_url_suffix',
                            'maxLength' => 255,
                            'label' => __('admin.misc.organization-url-suf'),
                            'placeholder' => 'eg: .html',
                            ])

                            @input([
                            'type' => 'number',
                            'name' => 'post_max_fetch_age',
                            'label' => __('admin.misc.post-max-fetching'),
                            'required' => true,
                            'min' => 0,
                            'max' => 365,
                            'step' => 1,
                            'help' => __('admin.misc.post-max-desc'),
                            ])

                            <div class="form-group">
                                <label class="form-label">{{ __('admin.misc.collectible-data') }}</label>
                                <div class="selectgroup selectgroup-pills">
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="can_collect_views_data" value="1" class="selectgroup-input" {{ old('can_collect_views_data', isset($entity) ? $entity->can_collect_views_data : null) ? 'checked' : '' }}>
                                        <span class="selectgroup-button">{{ __('admin.misc.views') }}</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="can_collect_likes_data" value="1" class="selectgroup-input" {{ old('can_collect_likes_data', isset($entity) ? $entity->can_collect_likes_data : null) ? 'checked' : '' }}>
                                        <span class="selectgroup-button">{{ __('admin.misc.likes') }}</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="can_collect_comments_data" value="1" class="selectgroup-input" {{ old('can_collect_comments_data', isset($entity) ? $entity->can_collect_comments_data : null) ? 'checked' : '' }}>
                                        <span class="selectgroup-button">{{ __('admin.misc.comments') }}</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="can_collect_shares_data" value="1" class="selectgroup-input" {{ old('can_collect_shares_data', isset($entity) ? $entity->can_collect_shares_data : null) ? 'checked' : '' }}>
                                        <span class="selectgroup-button">{{ __('admin.misc.shares') }}</span>
                                    </label>
                                </div>
                                <small class="form-text text-muted"><i class="fe fe-help-circle align-middle mr-1"></i>{{__('admin.misc.col-desc')}}</small>
                            </div>

                        </div>

                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('admin.misc.ranking-settings') }}</h3>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            </div>
                        </div>

                        <div class="card-body">
                            @input([
                            'type' => 'range',
                            'name' => 'ranking_weight',
                            'maxLength' => 255,
                            'required' => true,
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                            'help' => 'Decides how much this channel contributes to the final EBM index',
                            'label' => __('admin.misc.ranking-weight'),
                            'help' => __('admin.misc.rank-weight-desc'),
                            ])

                            <div class="row">

                                <div class="col-md-4">
                                    @input([
                                    'type' => 'number',
                                    'name' => 'weight_activity',
                                    'label' => __('admin.misc.activity-weight'),
                                    'placeholder' => 'Enter activity weight',
                                    'required' => true,
                                    'min' => 0,
                                    'max' => 100,
                                    'step' => 1,
                                    ])
                                </div>
                                <div class="col-md-4">
                                    @input([
                                    'type' => 'number',
                                    'name' => 'weight_popularity',
                                    'label' => __('admin.misc.popularity-weight'),
                                    'placeholder' => 'Enter popularity weight',
                                    'required' => true,
                                    'min' => 0,
                                    'max' => 100,
                                    'step' => 1,
                                    ])
                                </div>
                                <div class="col-md-4">
                                    @input([
                                    'type' => 'number',
                                    'name' => 'weight_engagement',
                                    'label' => __('admin.misc.engagement-weight'),
                                    'placeholder' => 'Enter engagement weight',
                                    'required' => true,
                                    'min' => 0,
                                    'max' => 100,
                                    'step' => 1,
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-footer text-right">
                            <div class="d-flex">
                                <a href="{{ route('admin.channel.index') }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }}</a>
                                @can('manage channels')
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
        @include('admin.partials.delete-modal', ['action' => route('admin.channel.delete', $entity->id)])
    @endpush

@endsection
