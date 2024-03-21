export function init() {
    // init tooltips with the body as selector to keep them working after ajax calls
    $('body').tooltip({selector: '[data-toggle="tooltip"]'});

    // close tooltip when clicking outside of it
    $('body').on('click', function (e) {
        $('[data-toggle=tooltip]').each(function () {
            // hide any open tooltips when the anywhere else in the body is clicked
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.tooltip').has(e.target).length === 0) {
                $(this).tooltip('hide');
            }
        });
    });
}
