@extends('admin.layouts.app')

@section('page-title', 'Data for ' . $organization->name)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        @if(app()->getLocale() == 'zh')
                            {{ $organization->name }} {{ __('admin.misc.data-for') }}

                        @else
                            {{ __('admin.misc.data-for') }}
                            {{ $organization->name }}

                        @endif                    </h1>
                    @can('manage organization data')
                    <a href="{{ route('admin.organization.data.create', ['organization' => $organization->id]) }}" class="btn btn-outline-primary ml-auto"><i class="fe fe-plus mr-2"></i> {{ __('admin.misc.new-data') }}</a>
                    @endcan
                </div>

                @include('admin.partials.notifications')

                <div class="card">
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable" id="data-table">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.misc.period') }}</th>
                                    <th>{{ __('admin.misc.channel') }}</th>
                                    <th>{{ __('admin.misc.follower-count') }}</th>
                                    <th><i class="fe fe-settings"></i></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="card-footer text-right">
                        <div class="d-flex">
                            <a href="{{ route('admin.organization.index') }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }} to listing</a>
                            <a href="{{ route('admin.organization.edit', ['id' => $organization->id]) }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }} to edit page</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        $(function() {
            let locale = $('html').attr('lang') == 'zh' ? 0 : 1;
            let $trans = {
                "channel" : locale ? 'Channel' : '渠道',
                "all" : locale ? 'All' : '全部'
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

            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthChange: false,
                language: $tableTrans,
                initComplete: function(settings, json) {
                    let $container = $(this.api().table().container());

                    $container.prepend('<div class="dataTables_filter float-right"><label>' +
                        $trans["channel"]+':<select class="form-control" id="channel-filter" onchange="$(\'#data-table\').DataTable().draw()">' +
                        '<option value="" selected>'+$trans["all"]+'</option>' +
                        @foreach($channels as $key => $channel)
                            '<option value="{{ $key }}">{{ $channel }}</option>' +
                        @endforeach
                            '</select></label></div>');
                },
                ajax: {
                    url: '{!! route('admin.organization.data.data', ['organization' => $organization->id]) !!}',
                    type: 'POST',
                    data: function (d) {
                        d.channel_id = $('#channel-filter').val();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown + '\nPlease refresh the page to try again.');
                    }
                },
                order: [ [0, 'desc'] ],
                columns: [
                    { data: 'period.start_date', name: 'period.start_date' },
                    { data: 'channel', name: 'channel' },
                    { data: 'follower_count', name: 'follower_count' },
                    { data: 'actions', name: 'actions', searchable: false, sortable: false }
                ]
            });
        });
    </script>
@endpush
