@extends('admin.layouts.app')

@section('page-title', __('admin.misc.data-for-post') . $post->log_title)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        {{ __('admin.misc.data-for-post') }} "{{ $post->log_title }}"
                    </h1>
                    @can('manage post data')
                    <a href="{{ route('admin.post.data.create', ['post' => $post->id]) }}" class="btn btn-outline-primary ml-auto"><i class="fe fe-plus mr-2"></i>{{ __('admin.misc.new-data') }}</a>
                    @endcan
                </div>

                @include('admin.partials.notifications')

                <div class="card">
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable" id="data-table">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.misc.period') }}</th>
                                    <th>{{ __('admin.misc.view-count') }}</th>
                                    <th>{{ __('admin.misc.like-count') }}</th>
                                    <th>{{ __('admin.misc.comment-count') }}</th>
                                    <th>{{ __('admin.misc.share-count') }}</th>
                                    <th><i class="fe fe-settings"></i></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="card-footer text-right">
                        <div class="d-flex">
                            @can('view posts')
                            <a href="{{ route('admin.organization.post.index', ['organization' => $post->organization_id]) }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }} {{ __('admin.misc.to-listing') }}</a>
                            <a href="{{ route('admin.organization.post.edit', ['organization' => $post->organization_id, 'id' => $post->id]) }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }} {{ __('admin.misc.to-edit-page') }}</a>
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
            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthChange: false,
                language: $tableTrans,
                ajax: {
                    url: '{!! route('admin.post.data.data', ['post' => $post->id]) !!}',
                    type: 'POST',
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown + '\nPlease refresh the page to try again.');
                    }
                },
                order: [ [0, 'desc'] ],
                columns: [
                    { data: 'period.name', name: 'period.name' },
                    { data: 'view_count', name: 'view_count' },
                    { data: 'like_count', name: 'like_count' },
                    { data: 'comment_count', name: 'comment_count' },
                    { data: 'share_count', name: 'share_count' },
                    { data: 'actions', name: 'actions', searchable: false, sortable: false }
                ]
            });
        });
    </script>
@endpush
