@extends('admin.layouts.app')

@section('page-title', __('admin.header.channels'))

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.header.channels') }}
                    </h1>
                    @can('manage channels')
                    <a href="{{ route('admin.channel.create') }}" class="btn btn-outline-primary ml-auto"><i class="fe fe-plus mr-2"></i>{{ __('admin.organizations.new-channel') }} </a>
                    @endcan
                </div>

                @include('admin.partials.notifications')

                <div class="card">
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable" id="channels-table">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.misc.order') }}</th>
                                    <th>{{ __('admin.misc.name') }}</th>
                                    <th>{{ __('admin.misc.published') }}</th>
                                    <th>{{ __('admin.misc.posts') }}</th>
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


            $('#channels-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthChange: false,
                language: $tableTrans,
                ajax: {
                    url: '{!! route('admin.channel.data') !!}',
                    type: 'POST',
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown + '\nPlease refresh the page to try again.');
                    }
                },
                order: [ [0, 'asc'] ],
                columns: [
                    { data: 'order', name: 'order' },
                    { data: 'channel_name', name: 'name' },
                    { data: 'published', name: 'published', searchable: false },
                    { data: 'posts_count', name: 'posts_count', searchable: false },
                    { data: 'actions', name: 'actions', searchable: false, sortable: false }
                ]
            });
        });
    </script>
@endpush
