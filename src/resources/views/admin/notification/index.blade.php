@extends('admin.layouts.app')

@section('page-title', __('admin.header.notifications'))

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.header.notifications') }}
                    </h1>
                </div>

                @include('admin.partials.notifications')

                <div class="card">
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable" id="notifications-table">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.crawler.times-stamp') }}</th>
                                    <th>{{ __('admin.misc.title') }}</th>
                                    <th>{{ __('admin.misc.type') }}</th>
                                    <th>{{ __('admin.misc.status') }}</th>
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

            $('#notifications-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthChange: false,
                language: $tableTrans,
                initComplete: function(settings, json) {
                    let $container = $(this.api().table().container());

                    $container.prepend('<div class="dataTables_filter float-right"><label>{{__('admin.misc.type')}}<select class="form-control" id="type-filter" onchange="$(\'#notifications-table\').DataTable().draw()">' +
                        '<option value="" selected>{{__('admin.misc.all')}}</option>' +
                        @foreach(\App\Enums\AdminNotificationType::getContentList() as $key => $type)
                            '<option value="{{ $key }}">{{ $type }}</option>' +
                        @endforeach
                            '</select></label></div>');
                },
                ajax: {
                    url: '{!! route('admin.notification.data') !!}',
                    type: 'POST',
                    data: function (d) {
                        d.type = $('#type-filter').val();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown + '\nPlease refresh the page to try again.');
                    }
                },
                order: [ [0, 'desc'] ],
                columns: [
                    { data: 'created_at', name: 'created_at', searchable: false },
                    { data: 'title', name: 'title' },
                    { data: 'type', name: 'type' },
                    { data: 'status', name: 'status' },
                    { data: 'actions', name: 'actions', searchable: false, sortable: false }
                ]
            });
        });
    </script>
@endpush
