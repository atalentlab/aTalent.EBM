@extends('admin.layouts.app')

@section('page-title', 'Edit post ' . $entity->log_title)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.misc.edit-post') }} {{ $entity->log_title }}
                    </h1>
                    <div class="ml-auto">
                        @can('view post data')
                        <a href="{{ route('admin.post.data.index', ['id' => $entity->id]) }}" class="btn btn-secondary ml-1" title="View data">{{ __('admin.misc.view-data') }}</a>
                        @endcan
                    </div>
                </div>

                @include('admin.partials.notifications')
                <form class="card js-parsley js-form" method="POST" action="{{ route('admin.organization.post.update', ['organization' => $organization->id, 'id' => $entity->id]) }}">
                    @csrf

                    <div class="card-body">

                        @input([
                            'type' => 'checkbox',
                            'name' => 'is_actively_fetching',
                            'label' => __('admin.misc.fetching-data'),
                        ])

                        @input([
                            'name' => 'title',
                            'maxLength' => 255,
                            'label' => __('admin.misc.title'),

                        ])

                        @input([
                            'name' => 'post_id',
                            'label' => __('admin.misc.post-id'),
                            'required' => true,
                            'placeholder' => __('admin.misc.enter-post-id'),
                            'maxLength' => 400,
                        ])

                        @input([
                            'type' => 'select',
                            'name' => 'channel_id',
                            'items' => $channels,
                            'label' => __('admin.misc.channel'),
                            'placeholder' => __('admin.misc.select-a-channel'),
                            'required' => true,
                        ])

                        @input([
                            'type' => 'datetime',
                            'name' => 'posted_date',
                            'required' => true,
                            'label' => __('admin.misc.POSTED-DATE'),

                        ])

                        @input([
                            'type' => 'url',
                            'name' => 'url',
                            'maxLength' => 255,
                            'placeholder' => 'eg: https://www.example.com',
                            'strict' => true,
                            'label' => __('admin.misc.url'),


                        ])

                    </div>

                    <div class="card-footer text-right">
                        <div class="d-flex">
                            <a href="{{ route('admin.organization.post.index', ['organization' => $organization->id]) }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }}</a>
                            @can('manage posts')
                            <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete-modal">
                                <i class="fe fe-trash mr-2"></i> {{ __('admin.misc.delete') }}
                            </button>
                            <button type="submit" class="btn btn-primary ml-auto js-submit" data-loading='<i class="fe fe-loader mr-2"></i> Saving...'>
                                <i class="fe fe-save mr-2"></i> {{ __('admin.misc.save') }}
                            </button>
                            @endcan
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

    @push('modals')
        @include('admin.partials.delete-modal', ['action' => route('admin.organization.post.delete', ['organization' => $organization->id, 'id' => $entity->id])])
    @endpush

@endsection
