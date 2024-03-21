let errorsTable;

function onFilterChange(e) {
    $(this).closest('form').submit();
}

function onFilterFormSubmit(e) {
    e.preventDefault();

    let $form = $(this);

    $.ajax($form.prop('action'), {
        method: $form.prop('method'),
        dataType: "JSON",
        data: $form.serialize(),
        beforeSend: (xhr) => {
            $('.js-ajax-container').addClass('is-loading');
        },
        success: (data) => {
            renderChannelsTable(data['channelsTableData']);
        },
        error: (jqXHR, textStatus, errorThrown) => {},
        complete: () => {
            $('.js-ajax-container').removeClass('is-loading');
        }
    });

    errorsTable.draw();
}

function renderChannelsTable(data) {
    let template = $('#channels-table-template').html();
    let content = '';

    $.each(data, function(index, channel) {
        content += Mustache.render(template, channel);
    });

    $('#channels-table').find('tbody').html(content);
}

function initErrorsTable() {
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

    let $elem = $('#errors-table');

    errorsTable = $elem.DataTable({
        processing: true,
        serverSide: true,
        pageLength: 20,
        lengthChange: false,
        language: $tableTrans,
        ajax: {
            url: $elem.data('url'),
            type: 'POST',
            data: function (d) {
                d.period_id = $('.js-crawler-dashboard-period-select').val();
                d.channel_id = $('.js-crawler-dashboard-channel-select').val();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown + '\nPlease refresh the page to try again.');
            }
        },
        order: [ [0, 'desc'] ],
        columns: [
            { data: 'updated_at', name: 'updated_at' },
            { data: 'channel_id', name: 'channel_id' },
            { data: 'organization_id', name: 'organization_id' },
            { data: 'is_organization_data_sent', name: 'is_organization_data_sent', searchable: false },
            { data: 'posts_crawled_count', name: 'posts_crawled_count', searchable: false },
            { data: 'message', name: 'message', sortable: false },
            { data: 'actions', name: 'actions', searchable: false, sortable: false }
        ]
    });
}



export function init() {
    $(document).on('change', '.js-crawler-dashboard-period-select, .js-crawler-dashboard-channel-select', onFilterChange);

    $(document).on('submit', '.js-crawler-dashboard-filter-form', onFilterFormSubmit);

    initErrorsTable();

    $('.js-crawler-dashboard-filter-form').submit();
}
