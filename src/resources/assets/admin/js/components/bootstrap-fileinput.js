import bsCustomFileInput from 'bs-custom-file-input'

function showPreview($elem, img) {
    $elem.find('img').attr('src', img);
    $elem.fadeIn('slow');
}

function hidePreview($elem) {
    let $img = $elem.find('img');

    if ($img.data('src')) {
        showPreview($elem, $img.data('src'));
    }
    else {
        $img.attr('src', null);
        $elem.hide();
    }
}

export function init() {
    bsCustomFileInput.init();

    $('.custom-file').each(function() {
        let $container = $(this).closest('.form-group');
        let $preview = $container.find('.custom-file-preview');

        $(this).find('input[type="file"]').on('change', function() {
            let input = this;

            $(this).parsley().whenValid({}).done(function() {
                if (input.files && input.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        showPreview($preview, e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
                else {
                    hidePreview($preview);
                }
            }).fail(function() {
                hidePreview($preview);
                $(input).val(null);
            });
        });
    });
}
