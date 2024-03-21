@extends('admin.layouts.app')

@section('page-title', 'Create new data for post ' . $post->log_title)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.misc.new-data-for-post') }} "{{ $post->log_title }}"
                    </h1>
                </div>

                @include('admin.partials.notifications')
                <form class="card js-parsley js-form" method="POST" action="{{ route('admin.post.data.store', ['post' => $post->id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        @input([
                            'type' => 'select',
                            'name' => 'period_id',
                            'items' => $periods,
                            'label' => __('admin.misc.period'),
                            'placeholder' => 'Select a period',
                            'required' => true,
                        ])

                        @input([
                            'type' => 'number',
                            'name' => 'view_count',
                            'label' => __('admin.misc.view-count'),
                            'min' => 0,
                        ])

                        @input([
                            'type' => 'number',
                            'name' => 'like_count',
                            'label' => __('admin.misc.like-count'),
                            'min' => 0,
                        ])

                        @input([
                            'type' => 'number',
                            'name' => 'comment_count',
                            'label' => __('admin.misc.comment-count'),
                            'min' => 0,
                        ])

                        @input([
                            'type' => 'number',
                            'name' => 'share_count',
                            'label' => __('admin.misc.share-count'),
                            'min' => 0,
                        ])

                    </div>

                    <div class="card-footer text-right">
                        <div class="d-flex">
                            <a href="{{ route('admin.post.data.index', ['post' => $post->id]) }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }}</a>
                            <button type="submit" class="btn btn-primary ml-auto js-submit" data-loading='<i class="fe fe-loader mr-2"></i> Saving...'>
                                <i class="fe fe-save mr-2"></i> {{ __('admin.misc.save') }}
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

@endsection
