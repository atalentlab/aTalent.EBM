@extends('admin.layouts.app')

@section('page-title', __('admin.header.top-performers'))

@section('content')


    <div class="container">
        @include('admin.partials.notifications')

        @can('view statistics dashboard')
        <div id="statistics-dashboard"
             data-user-permissions="{{ auth()->guard('admin')->user()->getAllPermissions()->pluck('name') }}"
             data-periods="{{ json_encode($periods) }}"
             data-channels="{{ json_encode($channels) }}"
             data-industries="{{ json_encode($industries) }}"
             data-top-performers-data-url="{{ route('admin.dashboard.top-performers-data') }}"
             data-top-performing-content-data-url="{{ route('admin.dashboard.top-performing-content-data') }}"
             data-top-performing-content-metrics="{{ json_encode($topPerformingContentMetrics) }}"
             data-my-profile-url="{{ route('admin.profile.show') }}"
             data-locale="{{ app()->getLocale() }}"
        >
            <div class="row">
                <div class="col-12">
                    <div class="card p-5">
                        <div class="card-body">
                            <div class="dimmer active">
                                <div class="loader"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view statistics dashboard.index table')
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h2 class="page-title w-100">{{ __('admin.ebm-ranking.title') }}</h2>
                    <p>{{ __('admin.ebm-ranking.intro') }}</p>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.ebm-ranking.indices') }}</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable" id="index-table">
                            <thead>
                            <tr>
                                <th>{{ __('admin.ebm-ranking.rank') }}</th>
                                <th>{{ __('admin.misc.organization') }}</th>
                                <th>{{ __('admin.ebm-ranking.ebm-score') }}
                                    <i class="fe fe-help-circle ml-2" data-toggle="tooltip" data-placement="right" title="EBM Index is formulated with a mix of three parameters: popularity, activity, and engagement"></i>
                                </th>
                                <th>{{ __('admin.ebm-ranking.progression') }}
                                    <i class="fe fe-help-circle ml-2" data-toggle="tooltip" data-placement="right" title="The progress of the score, positive or negative, from one week to another"></i>
                                </th>
                                <th>{{ __('admin.ebm-ranking.activity') }}
                                    <i class="fe fe-help-circle ml-2" data-toggle="tooltip" data-placement="right" title="Popularity measures the level of public attention on each social recruitment account (i.e. number of followers)."></i>
                                </th>
                                <th>{{ __('admin.ebm-ranking.popularity') }}
                                    <i class="fe fe-help-circle ml-2" data-toggle="tooltip" data-placement="right" title="Activity scales the dynamics of social recruitment exercises, in terms of the frequency on generating various quality contents to reach out the “fans”."></i>
                                </th>
                                <th>{{ __('admin.ebm-ranking.engagement') }}
                                    <i class="fe fe-help-circle ml-2" data-toggle="tooltip" data-placement="right" title="Engagement measures the level of interaction between accounts and followers, also, how likely the followers are involved in this well-established social media community."></i>
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view indices dashboard')
        <div class="row">
            <div class="col-12">
                <div class="page-header flex-column align-items-start">
                    <h2 class="page-title">{{ __('admin.ebm-indices-detailed.title') }}</h2>
                    <p>{{ __('admin.ebm-indices-detailed.intro') }}</p>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.ebm-ranking.indices') }}</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable" id="index-detailed-table">
                            <thead>
                            <tr>
                                <th>{{ __('admin.ebm-ranking.period') }}</th>
                                <th>{{ __('admin.misc.organization') }}</th>
                                <th>{{ __('admin.ebm-ranking.composite') }}</th>
                                <th>{{ __('admin.ebm-ranking.shift') }}</th>
                                <th>{{ __('admin.ebm-ranking.popularity') }}</th>
                                <th>{{ __('admin.ebm-ranking.activity') }}</th>
                                <th>{{ __('admin.ebm-ranking.engagement') }}</th>
                                <th><i class="fe fe-settings"></i></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </div>

@endsection

@push('scripts')
    <script>
        $(function() {
            let $tableElem = $('#index-table');
            let locale = $('html').attr('lang') == 'zh' ? 0 : 1;
            let $trans = {
                "industry" : locale ? 'Industry' : '行业',
                "channel" : locale ? 'Channel' : '渠道',
                "period" : locale ? 'Period' : '期间',
                "all-period" : locale ? 'All Periods' : '全部期间',
                "all-industries" : locale ? 'All Industries' : '所有行业',
                "all-channels" : locale ? 'All Channels' : '所有渠道',
            }

            let $tableTrans = {
                "sSearch":  locale ? 'Search' : '搜索',
                "sInfo": locale ? "Showing _START_ to _END_ of _TOTAL_ entries" : "显示_TOTAL_条中的_START_至_END_条" ,
                "sInfoEmpty": locale ? "Showing 0 to 0 of 0 entries" : '显示0条中的0至0条',
                "sZeroRecords":   locale ? "No data available in table" : '表中无数据',
                "oPaginate": {
                    "sNext":    locale ? "Next" : "下一页",
                    "sPrevious": locale ? "Previous" : "上一页",
                },
            }

            $tableElem.DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthChange: false,
                "language": $tableTrans,
                initComplete: function(settings, json) {
                    let $container = $(this.api().table().container());

                    $container.prepend('<div class="dataTables_filter float-right"><label>' +
                        $trans["industry"]+':<select class="form-control js-industry-filter" onchange="$(\'#index-table\').DataTable().draw()">' +
                        @foreach($industries as $key => $industry)
                            @if($industry['id'] == null)
                            '<option value="{{ $industry['id'] }}"> '+ $trans['all-industries'] +' </option>' +
                            @else
                            '<option value="{{ $industry['id'] }}">{{ $industry['industry_name'] }} </option>' +
                            @endif
                        @endforeach
                            '</select></label></div>');

                    $container.prepend('<div class="dataTables_filter float-right ml-0"><label>' +
                        $trans["channel"]+':<select class="form-control js-channel-filter" onchange="$(\'#index-table\').DataTable().draw()">' +
                        @foreach($channels as $key => $channel)
                            @if($channel['id'] == null)
                            '<option value="{{ $channel['id'] }}"> '+ $trans['all-channels'] +' </option>' +
                            @else
                            '<option value="{{ $channel['id'] }}">{{ $channel['name'] }}</option>' +
                            @endif
                        @endforeach
                            '</select></label></div>');

                    $container.prepend('<div class="dataTables_filter float-right ml-0"><label>' +
                        $trans["period"]+':<select class="form-control js-period-filter" onchange="$(\'#index-table\').DataTable().draw()">' +
                        @foreach($periods as $key => $period)
                            '<option value="{{ $period['id'] }}" {{ $loop->first ? 'selected' : '' }}>{{ $period['name_with_year'] }}</option>' +
                        @endforeach
                            '</select></label></div>');

                },
                ajax: {
                    url: '{!! route('admin.dashboard.indices-table-data') !!}',
                    type: 'POST',
                    data: function (d) {
                        let $container = $tableElem.closest('.dataTables_wrapper');
                        d.period_id = $container.find('.js-period-filter').val();
                        d.channel_id = $container.find('.js-channel-filter').val();
                        d.industry_id = $container.find('.js-industry-filter').val();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown + '\nPlease refresh the page to try again.');
                    }
                },
                order: [ [2, 'desc'] ],
                columns: [
                    { data: null,
                      'render': function (data, type, full, meta) { return meta.row + 1; },
                      searchable: false,
                      sortable: false
                    },
                    { data: 'organization.name', name: 'organization.name' },
                    { data: 'composite', name: 'composite' },
                    { data: 'composite_shift', name: 'composite_shift' },
                    { data: 'popularity', name: 'popularity' },
                    { data: 'activity', name: 'activity' },
                    { data: 'engagement', name: 'engagement' }
                ]
            });
        });


        $(function() {
            let $tableElem = $('#index-detailed-table');
            let locale = $('html').attr('lang') == 'zh' ? 0 : 1;
            let $trans = {
                "industry" : locale ? 'Industry' : '行业',
                "channel" : locale ? 'Channel' : '渠道',
                "period" : locale ? 'Period' : '期间',
                "all-period" : locale ? 'All Periods' : '全部期间',
                "all-industries" : locale ? 'All Industries' : '所有行业',
                "all-channels" : locale ? 'All Channels' : '所有渠道',
            }

            let $tableTrans = {
                "sSearch":  locale ? 'Search' : '搜索',
                "sInfo": locale ? "Showing _START_ to _END_ of _TOTAL_ entries" : "显示_TOTAL_条中的_START_至_END_条" ,
                "sInfoEmpty": locale ? "Showing 0 to 0 of 0 entries" : '显示0条中的0至0条',
                "sZeroRecords":   locale ? "No data available in table" : '表中无数据',
                "oPaginate": {
                    "sNext":    locale ? "Next" : "下一页",
                    "sPrevious": locale ? "Previous" : "上一页",
                },
            }

            $tableElem.DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthChange: false,
                "language": $tableTrans,
                initComplete: function(settings, json) {
                    let $container = $(this.api().table().container());

                    $container.prepend('<div class="dataTables_filter float-right"><label>' +
                        $trans["industry"]+':<select class="form-control js-industry-filter" onchange="$(\'#index-detailed-table\').DataTable().draw()">' +
                        @foreach($industries as $key => $industry)
                            @if($industry['id'] == null)
                            '<option value="{{ $industry['id'] }}"> '+ $trans['all-industries'] +' </option>' +
                            @else
                            '<option value="{{ $industry['id'] }}">{{ $industry['industry_name'] }} </option>' +
                            @endif
                        @endforeach
                            '</select></label></div>');

                    $container.prepend('<div class="dataTables_filter float-right ml-0"><label>' +
                        $trans["channel"]+':<select class="form-control js-channel-filter" onchange="$(\'#index-detailed-table\').DataTable().draw()">' +
                        @foreach($channels as $key => $channel)
                            @if($channel['id'] == null)
                            '<option value="{{ $channel['id'] }}"> '+ $trans['all-channels'] +' </option>' +
                        @else
                            '<option value="{{ $channel['id'] }}">{{ $channel['name'] }}</option>' +
                        @endif
                        @endforeach
                            '</select></label></div>');

                    $container.prepend('<div class="dataTables_filter float-right ml-0"><label>' +
                        $trans["period"]+':<select class="form-control js-period-filter" onchange="$(\'#index-detailed-table\').DataTable().draw()">' +
                        '<option value="" selected>'+  $trans["all-period"] +'</option>' +
                        @foreach($periods as $key => $period)
                            '<option value="{{ $period['id'] }}">{{ $period['name_with_year'] }}</option>' +
                        @endforeach
                            '</select></label></div>');

                    $('[data-toggle="popover"]').popover({html: true});
                },
                drawCallback: function(settings) {
                    $('[data-toggle="popover"]').popover({html: true});
                },
                ajax: {
                    url: '{!! route('admin.dashboard.indices-detailed-table-data') !!}',
                    type: 'POST',
                    data: function (d) {
                        let $container = $tableElem.closest('.dataTables_wrapper');
                        d.period_id = $container.find('.js-period-filter').val();
                        d.channel_id = $container.find('.js-channel-filter').val();
                        d.industry_id = $container.find('.js-industry-filter').val();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown + '\nPlease refresh the page to try again.');
                    }
                },
                order: [ [0, 'desc'] ],
                columns: [
                    { data: 'period.start_date', name: 'period.start_date' },
                    { data: 'organization.name', name: 'organization.name' },
                    { data: 'composite', name: 'composite' },
                    { data: 'composite_shift', name: 'composite_shift' },
                    { data: 'popularity', name: 'popularity' },
                    { data: 'activity', name: 'activity' },
                    { data: 'engagement', name: 'engagement' },
                    { data: 'actions', name: 'actions', searchable: false, sortable: false }
                ]
            });
        });
    </script>
@endpush
