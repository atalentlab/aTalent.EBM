@extends('admin.layouts.app')

@section('page-title', __('admin.misc.activity-log-for') . $entity->log_title)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        @if(app()->getLocale() == 'zh')
                            {{ $entity->log_title }} {{ __('admin.misc.activity-log-for') }}
                        @else
                            {{ __('admin.misc.activity-log-for') }} {{ $entity->log_title }}
                        @endif
                    </h1>
                </div>

                @include('admin.partials.notifications')

                <div class="card">
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable" id="activities-table">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.crawler.times-stamp') }}</th>
                                    <th>{{ __('admin.misc.user') }}</th>
                                    <th>{{ __('admin.misc.description') }}</th>
                                    <th><i class="fe fe-settings"></i></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="card-footer text-right">
                        <div class="d-flex">
                            @can('view organizations')
                            <a href="{{ route('admin.organization.edit', ['id' => $entity->id]) }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2">
                                {{ __('admin.misc.back') }}</i></a>
                            @endcan
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
                searching: false,
                language: $tableTrans,
                ajax: {
                    url: '{!! route('admin.organization.activity.data', ['id' => $entity->id]) !!}',
                    type: 'POST',
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown + '\nPlease refresh the page to try again.');
                    }
                },
                order: [ [0, 'desc'] ],
                columns: [
                    { data: 'created_at', name: 'created_at', searchable: false },
                    { data: 'user', name: 'user' },
                    { data: 'description', name: 'description', searchable: false },
                    { data: 'actions', name: 'actions', searchable: false, sortable: false }
                ]
            });
        });
    </script>
@endpush
