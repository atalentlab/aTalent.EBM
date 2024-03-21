@extends('admin.layouts.app')

@section('page-title',  __('admin.misc.create-new-data-for') . $organization->name)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        @if(app()->getLocale() == 'zh')
                            {{ $organization->name }} {{ __('admin.misc.new-data-for') }}

                        @else
                            {{ __('admin.misc.new-data-for') }}
                            {{ $organization->name }}

                        @endif
                    </h1>
                </div>

                @include('admin.partials.notifications')
                <form class="card js-parsley js-form" method="POST" action="{{ route('admin.organization.data.store', ['organization' => $organization->id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        @input([
                            'type' => 'select',
                            'name' => 'channel_id',
                            'items' => $channels,
                            'label' => __('admin.misc.channel'),
                            'placeholder' => __('admin.misc.select-a-channel'),
                            'required' => true,
                        ])

                        @input([
                            'type' => 'select',
                            'name' => 'period_id',
                            'items' => $periods,
                            'label' => __('admin.misc.periods'),
                            'placeholder' => __('admin.misc.select-a-channel'),
                            'required' => true,
                        ])

                        @input([
                            'type' => 'number',
                            'name' => 'follower_count',
                            'label' =>  __('admin.misc.follower-count'),
                            'placeholder' => __('admin.misc.enter-fol-count'),
                            'required' => true,
                            'min' => 0,
                        ])

                    </div>

                    <div class="card-footer text-right">
                        <div class="d-flex">
                            <a href="{{ route('admin.organization.data.index', ['organization' => $organization->id]) }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }}</a>
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
