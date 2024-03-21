@extends('admin.layouts.app')

@section('page-title', __('admin.misc.edit') . $entity->log_title)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.misc.edit') }} {{ $entity->log_title }}
                    </h1>
                </div>

                @include('admin.partials.notifications')
                <form class="card js-parsley js-form" method="POST" action="{{ route('admin.post.data.update', ['post' => $post->id, 'id' => $entity->id]) }}">
                    @csrf

                    <div class="card-body">

                        @input([
                            'type' => 'select',
                            'name' => 'period_id',
                            'items' => $periods,
                            'label' => __('admin.ebm-ranking.period'),
                            'placeholder' => __('admin.misc.select-a-period'),
                            'required' => true,
                        ])

                        @input([
                            'type' => 'number',
                            'name' => 'view_count',
                            'min' => 0,
                            'label' => __('admin.misc.view-count'),
                             'placeholder' => __('admin.misc.enter-view-count'),
                        ])

                        @input([
                            'type' => 'number',
                            'name' => 'like_count',
                            'min' => 0,
                             'label' => __('admin.misc.like-count'),
                             'placeholder' => __('admin.misc.enter-like-count'),
                        ])

                        @input([
                            'type' => 'number',
                            'name' => 'comment_count',
                            'min' => 0,
                             'label' => __('admin.misc.comment-count'),
                            'placeholder' => __('admin.misc.enter-comment-count'),
                        ])

                        @input([
                            'type' => 'number',
                            'name' => 'share_count',
                            'min' => 0,
                            'label' => __('admin.misc.share-count'),
                           'placeholder' => __('admin.misc.enter-share-count'),

                        ])

                    </div>

                    <div class="card-footer text-right">
                        <div class="d-flex">
                            <a href="{{ route('admin.post.data.index', ['post' => $post->id]) }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }}</a>
                            @can('manage post data')
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
        @include('admin.partials.delete-modal', ['action' => route('admin.post.data.delete', ['post' => $post->id, 'id' => $entity->id])])
    @endpush

@endsection
