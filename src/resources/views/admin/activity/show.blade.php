@extends('admin.layouts.app')

@section('page-title', 'Activity Log Detail')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.misc.activity-log-detail') }}
                    </h1>
                </div>

                @include('admin.partials.notifications')

                <div class="card">

                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable" id="activities-table">
                            <tbody>
                                <tr>
                                    <td>{{ __('admin.crawler.times-stamp') }}</td>
                                    <td>{{ $activity->created_at }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admin.misc.user') }}</td>
                                    <td>{{ $activity->getCauser() }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admin.misc.subject') }}</td>
                                    <td>{{ $activity->getSubject() }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admin.misc.description') }}</td>
                                    <td>{{ $activity->description }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admin.misc.log') }}</td>
                                    <td>{{ $activity->log_name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admin.misc.changes') }}</td>
                                    <td>{{ $activity->changes }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('admin.activity.index') }}" class="btn btn-secondary">
                            <i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }}
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection
