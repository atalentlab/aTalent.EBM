const selectize = require('./selectize');

function onSubmitBtnClick(e) {
    const $btn = $(e.currentTarget);


    if ($btn.data('form')) {
        const $form = $($btn.data('form'));

        if ($form.length) {
            e.preventDefault();
            setBtnLoadingState($btn);
            $form.submit();
        }
    }
}

function submitForm(e) {
    const $form = $(e.currentTarget);
    let $btn = $form.find('.js-submit');

    $form.parsley().validate();

    if (!$form.parsley().isValid()) {
        return false;
    }

    if ($form.data('needs-confirmation')) {
        $('#confirmation-modal').modal('toggle');
        return false;
    }

    setBtnLoadingState($btn);
}

function onConfirm(e) {
    const $btn = $(e.currentTarget);
    const $form = $($btn.data('target'));

    if ($form.length) {
        $form.data('needs-confirmation', null);
        $form.submit();

        setBtnLoadingState($btn);
    }
}

function setBtnLoadingState($btn) {
    $btn.data('original', $btn.html());
    $btn.attr('disabled', 'disabled');
    $btn.html($btn.data('loading'));
}

function onRangeChange(e) {
    let $targetInput = $('#' + $(this).data('range-for'));

    $targetInput.val($(this).val());
}

function onToggleContent(e) {
    const $input = $(e.currentTarget);
    const $selectedOption = $input.find(":selected");

    if (!$input.data('target')) return false;

    const $target = $($input.data('target'));

    if (!$target.length) return false;

    $target.closest('.form-group').toggleClass('d-none', $input.val() === "");
    $target.val($selectedOption.data('content'));
}

function onClearClick(e) {
    e.preventDefault();

    const $btn = $(e.currentTarget);
    const $toClear = $($btn.data('target'));

    $toClear.find(':input').val('');
}

export function init() {
    $(document).on('click', '.js-submit', onSubmitBtnClick);

    // form submit animations
    $(document).on('submit', '.js-form', submitForm);

    // confirmation lightbox toggle
    $(document).on('click', '.js-confirm', onConfirm);

    $(document).on('change', '.js-toggle-content', onToggleContent);

    $(document).on('click', '.js-clear', onClearClick);


    // init selectize dropdowns
    $('.selectize').each((key, item) => {
        selectize.init($(item));
    });

    $(document).on('change input', '.js-custom-range', onRangeChange);


    $('.js-switch-locale').on('click' , function () {
        $('.js-switch-locale-form').submit();
    });

}
