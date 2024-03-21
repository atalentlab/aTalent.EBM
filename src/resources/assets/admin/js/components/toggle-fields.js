function onToggleFieldsChange(e) {
    const $input = $(e.currentTarget);
    const $target = $($input.data('target'));

    if (!$target.length) {
        return;
    }

    $target.prop('disabled', !!$input.val());
}

export function init() {
    $(document).on('change', '.js-toggle-field', onToggleFieldsChange);
}
