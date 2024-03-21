@extends('admin.layouts.app')

@section('page-title', __('admin.misc.create-new-post-for') . $organization->name)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        @if(app()->getLocale() == 'zh')
                            {{ $organization->name }} {{ __('admin.misc.new-post-for') }}

                        @else
                            {{ __('admin.misc.new-post-for') }}
                            {{ $organization->name }}

                        @endif

                    </h1>
                </div>

                @include('admin.partials.notifications')
                <form class="card js-parsley js-form" method="POST" action="{{ route('admin.organization.post.store', ['organization' => $organization->id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        @input([
                            'type' => 'checkbox',
                            'name' => 'is_actively_fetching',
                            'label' => __('admin.misc.fetching'),
                            'placeholder' => __('admin.misc.enter-title'),
                        ])

                        @input([
                            'name' => 'title',
                            'maxLength' => 255,
                             'label' => __('admin.misc.title'),
                             'placeholder' => __('admin.misc.enter-title'),
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
                            'label' => __('admin.misc.posted-date'),
                            'required' => true,
                        ])

                        @input([
                            'type' => 'url',
                            'name' => 'url',
                            'label' => __('admin.misc.url'),
                            'maxLength' => 255,
                            'placeholder' => 'eg: https://www.example.com',
                            'strict' => true,
                        ])

                    </div>

                    <div class="card-footer text-right">
                        <div class="d-flex">
                            <a href="{{ route('admin.organization.post.index', ['organization' => $organization->id]) }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }}</a>
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
