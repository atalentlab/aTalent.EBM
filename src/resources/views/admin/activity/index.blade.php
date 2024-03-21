@extends('admin.layouts.app')

@section('page-title', __('admin.header.activity-log'))

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.header.activity-log') }}

                    </h1>
                </div>

                @include('admin.partials.notifications')

                <div class="card">
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable" id="activities-table">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.crawler.times-stamp') }}</th>
                                    <th>{{ __('admin.misc.log') }}</th>
                                    <th>{{ __('admin.misc.user') }}</th>
                                    <th>{{ __('admin.misc.description') }}</th>
                                    <th>{{ __('admin.misc.subject') }}</th>
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
    <script>
        $(function() {
            let locale = $('html').attr('lang') == 'zh' ? 0 : 1;
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
            $('#activities-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthChange: false,
                language: $tableTrans,
                initComplete: function(settings, json) {
                    let $container = $(this.api().table().container());

                    $container.prepend('<div class="dataTables_filter float-right"><label>{{__('admin.misc.log')}}<select class="form-control" id="log-filter" onchange="$(\'#activities-table\').DataTable().draw()">' +
                        '<option value="" selected>{{__('admin.misc.all')}}</option>' +
                        @foreach($logNames as $logName)
                            '<option value="{{ $logName }}">{{ $logName }}</option>' +
                        @endforeach
                        '</select></label></div>');
                },
                ajax: {
                    url: '{!! route('admin.activity.data') !!}',
                    type: 'POST',
                    data: function (d) {
                        d.log = $('#log-filter').val();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown + '\nPlease refresh the page to try again.');
                    }
                },
                order: [ [0, 'desc'] ],
                columns: [
                    { data: 'created_at', name: 'created_at', searchable: false },
                    { data: 'log_name', name: 'log_name' },
                    { data: 'user', name: 'user', searchable: false },
                    { data: 'description', name: 'description' },
                    { data: 'subject', name: 'subject', searchable: false },
                    { data: 'actions', name: 'actions', searchable: false, sortable: false }
                ]
            });
        });
    </script>
@endpush
