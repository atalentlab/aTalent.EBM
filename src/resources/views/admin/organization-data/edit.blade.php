@extends('admin.layouts.app')

@section('page-title', 'Edit data ' . $entity->log_title)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.misc.edit-data') }} {{ $entity->log_title }}
                    </h1>
                </div>

                @include('admin.partials.notifications')
                <form class="card js-parsley js-form" method="POST" action="{{ route('admin.organization.data.update', ['organization' => $organization->id, 'id' => $entity->id]) }}">
                    @csrf

                    <div class="card-body">

                        @input([
                            'type' => 'select',
                            'name' => 'channel_id',
                            'items' => $channels,
                            'label' => __('admin.misc.channel'),
                            'placeholder' => 'Select a channel',
                            'required' => true,
                        ])

                        @input([
                            'type' => 'select',
                            'name' => 'period_id',
                            'items' => $periods,
                            'label' => __('admin.misc.periods'),
                            'placeholder' => 'Select a period',
                            'required' => true,
                        ])

                        @input([
                            'type' => 'number',
                            'name' => 'follower_count',
                             'label' => __('admin.misc.follower-count'),
                            'required' => true,
                            'min' => 0,
                        ])

                    </div>

                    <div class="card-footer text-right">
                        <div class="d-flex">
                            <a href="{{ route('admin.organization.data.index', ['organization' => $organization->id]) }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }}</a>
                            @can('manage organization data')
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
        @include('admin.partials.delete-modal', ['action' => route('admin.organization.data.delete', ['organization' => $organization->id, 'id' => $entity->id])])
    @endpush

@endsection
