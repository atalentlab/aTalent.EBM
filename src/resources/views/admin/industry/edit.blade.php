@extends('admin.layouts.app')

@section('page-title', __('admin.misc.edit-industry') . $entity->name)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.misc.edit-industry') }} {{ $entity->name }}
                    </h1>
                </div>

                @include('admin.partials.notifications')

                <form class="card js-parsley js-form" method="POST" action="{{ route('admin.industry.update', $entity->id) }}">
                    @csrf

                    <div class="card-body">

                        @input([
                            'type' => 'checkbox',
                            'name' => 'published',
                            'help' => __('admin.misc.edit-intro'),
                             'label' => __('admin.misc.published'),

                        ])

                        @input([
                            'name' => 'name',
                            'required' => true,
                            'maxLength' => 255,
                            'label' => __('admin.misc.name'),
                            'placeholder' => __('admin.misc.enter-name'),

                        ])

                        @input([
                            'type' => 'number',
                            'name' => 'order',
                            'required' => true,
                            'min' => 0,
                            'label' => __('admin.misc.order'),
                             'placeholder' => __('admin.misc.enter-order'),

                        ])

                    </div>

                    <div class="card-footer text-right">
                        <div class="d-flex">
                            <a href="{{ route('admin.industry.index') }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }}</a>
                            @can('manage industries')
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
        @include('admin.partials.delete-modal', ['action' => route('admin.industry.delete', $entity->id)])
    @endpush

@endsection
