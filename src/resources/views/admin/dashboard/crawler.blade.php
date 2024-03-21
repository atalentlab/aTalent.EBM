@extends('admin.layouts.app')

@section('page-title',__('admin.header.crawler-dashboard'))

@section('content')

    <div class="container js-ajax-container">
        <div class="row">
            <div class="col-12">

                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.crawler.title') }}
                    </h1>
                    <form class="ml-auto form-inline js-crawler-dashboard-filter-form" method="POST" action="{{ route('admin.crawler.data') }}">
                        <div class="form-group mr-3">
                            <label class="form-label mr-1" for="channel_id">{{__('admin.misc.channel')}}</label>
                            <select name="channel_id" id="channel_id" class="form-control js-crawler-dashboard-channel-select">
                                <option value="">{{__('admin.misc.all')}}</option>
                                @foreach($channels as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label mr-1" for="period_id">{{__('admin.ebm-ranking.period')}}:</label>
                            <select name="period_id" id="period_id" class="form-control js-crawler-dashboard-period-select">
                                @foreach($periods as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>

                @include('admin.partials.notifications')

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.crawler.channel-overview') }}</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap" id="channels-table">
                            <thead>
                            <tr>
                                <th>{{ __('admin.misc.channel') }}</th>
                                <th>{{ __('admin.crawler.completion') }}</th>
                                <th>{{ __('admin.crawler.of-new-posts') }}</th>
                                <th>{{ __('admin.crawler.of-errors') }}</th>
                                <th>{{ __('admin.crawler.last-crawled-at') }}</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.crawler.crawler-errors') }}</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable" id="errors-table" data-url="{{ route('admin.crawler.errors-data') }}">
                            <thead>
                            <tr>
                                <th>{{ __('admin.crawler.times-stamp') }}</th>
                                <th>{{ __('admin.misc.channel') }}</th>
                                <th>{{ __('admin.misc.organization') }}</th>
                                <th>{{ __('admin.misc.data') }}</th>
                                <th>{{ __('admin.misc.posts') }}</th>
                                <th>{{ __('admin.crawler.error-message') }}</th>
                                <th><i class="fe fe-settings"></i></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script id="channels-table-template" type="x-tmpl-mustache">
        <tr>
            <td>@{{ name }}</td>
            <td>
                <div class="clearfix">
                    <div class="float-left">
                        <strong>@{{ crawled_completed_percentage }}%</strong>
                    </div>
                    <div class="float-right">
                        <small class="text-muted">@{{ success_crawled_count }} of @{{ total_count }}</small>
                    </div>
                </div>
                <div class="progress progress-xs">
                    <div class="progress-bar bg-@{{ color }}" role="progressbar" style="width: @{{ crawled_completed_percentage }}%" aria-valuenow="@{{ crawled_completed_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </td>
            <td>@{{ posts_count }}</td>
            <td>@{{ error_count }}</td>
            <td>@{{ last_crawled_at }}</td>
        </tr>
    </script>
@endpush
