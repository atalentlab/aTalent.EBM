@extends('admin.layouts.app')

@section('page-title', __('admin.misc.view-notification') . $entity->title)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ $entity->title }}
                    </h1>
                </div>

                @include('admin.partials.notifications')

                @includeIf('admin.notification.type.' . $entity->type)
            </div>
        </div>
    </div>

    @push('modals')
        @include('admin.partials.delete-modal', ['action' => route('admin.notification.delete', $entity->id)])
    @endpush

@endsection
