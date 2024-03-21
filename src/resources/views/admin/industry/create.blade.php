@extends('admin.layouts.app')

@section('page-title', __('admin.misc.create-new-industry'))

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.misc.create-new-industry') }}
                    </h1>
                </div>

                @include('admin.partials.notifications')

                <form class="card js-parsley js-form" method="POST" action="{{ route('admin.industry.store') }}">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        @input([
                            'type' => 'checkbox',
                            'name' => 'published',
                             'label' => __('admin.misc.published'),
                            'help' => __('admin.misc.edit-ind-desc'),
                        ])

                        @input([
                            'name' => 'name',
                            'label' => __('admin.misc.name'),
                            'placeholder' => __('admin.misc.enter-name'),
                            'required' => true,
                            'maxLength' => 255,
                        ])

                        @input([
                            'type' => 'number',
                            'name' => 'order',
                            'label' => __('admin.misc.order'),
                            'required' => true,
                            'min' => 0,
                            'value' => $defaultOrder,
                            'placeholder' => __('admin.misc.enter-order'),
                        ])

                    </div>

                    <div class="card-footer text-right">
                        <div class="d-flex">
                            <a href="{{ route('admin.industry.index') }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }}</a>
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
