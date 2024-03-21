@extends('admin.layouts.app')

@section('page-title', 'Posts for ' . $organization->name)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        @if(app()->getLocale() == 'zh')
                            {{ $organization->name }} {{ __('admin.misc.post-for') }}

                        @else
                            {{ __('admin.misc.post-for') }}
                            {{ $organization->name }}

                        @endif
                    </h1>
                    @can('manage posts')
                    <a href="{{ route('admin.organization.post.create', ['organization' => $organization->id]) }}" class="btn btn-outline-primary ml-auto"><i class="fe fe-plus mr-2"></i>{{ __('admin.misc.new-data') }}</a>
                    @endcan
                </div>

                @include('admin.partials.notifications')

                <div class="card">
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable" id="posts-table">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.misc.posted-date') }}</th>
                                    <th>{{ __('admin.misc.title') }}</th>
                                    <th>{{ __('admin.misc.channel') }}</th>
                                    <th>{{ __('admin.misc.fetching') }}</th>
                                    <th>{{ __('admin.misc.data') }}</th>
                                    <th><i class="fe fe-settings"></i></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="card-footer text-right">
                        <div class="d-flex">
                            <a href="{{ route('admin.organization.index') }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }} {{__('admin.misc.to-listing')}}</a>
                            <a href="{{ route('admin.organization.edit', ['id' => $organization->id]) }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }} {{__('admin.misc.to-edit-page')}}</a>
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

            $('#posts-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthChange: false,
                language: $tableTrans,
                initComplete: function(settings, json) {
                    let $container = $(this.api().table().container());

                    $container.prepend('<div class="dataTables_filter float-right"><label>{{__('admin.misc.channel')}}:<select class="form-control" id="channel-filter" onchange="$(\'#posts-table\').DataTable().draw()">' +
                        '<option value="" selected>{{__("admin.misc.all")}}</option>' +
                        @foreach($channels as $key => $channel)
                            '<option value="{{ $key }}">{{ $channel }}</option>' +
                        @endforeach
                            '</select></label></div>');
                },
                ajax: {
                    url: '{!! route('admin.organization.post.data', ['organization' => $organization->id]) !!}',
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
                    { data: 'posted_date', name: 'posted_date' },
                    { data: 'title', name: 'title' },
                    { data: 'channel', name: 'channel' },
                    { data: 'is_actively_fetching', name: 'is_actively_fetching', searchable: false },
                    { data: 'post_data_count', name: 'post_data_count', searchable: false },
                    { data: 'actions', name: 'actions', searchable: false, sortable: false }
                ]
            });
        });
    </script>
@endpush
