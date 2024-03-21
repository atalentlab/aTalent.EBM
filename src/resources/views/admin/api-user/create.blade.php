@extends('admin.layouts.app')

@section('page-title', __('admin.misc.create-new-api-user'))

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.misc.create-new-api-user') }}
                    </h1>
                </div>

                @include('admin.partials.notifications')

                <form class="card js-parsley js-form" method="POST" action="{{ route('admin.api-user.store') }}">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        @input([
                            'type' => 'checkbox',
                            'name' => 'activated',
                            'label' => __('admin.misc.activated'),
                            'help' => __('admin.misc.edit-api-desc'),
                        ])

                        @input([
                            'name' => 'name',
                            'label' => __('admin.misc.name'),
                            'required' => true,
                            'maxLength' => 255,
                            'placeholder' => __('admin.misc.enter-name'),
                        ])

                    </div>

                    <div class="card-footer text-right">
                        <div class="d-flex">
                            <a href="{{ route('admin.api-user.index') }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }}</a>
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
