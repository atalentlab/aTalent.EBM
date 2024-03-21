
var letterAnimate;

function submitRemoteForm(e) {
    e.preventDefault();

    const $form = $(this);
    const $btn = $form.find('.js-btn-submit');
    const $btnText = $btn.find('.js-btn-text');
    const $formContent = $form.find('.js-form-content');
    const $successBox = $form.find('.js-success-box');

    $.ajax($form.prop('action'), {
        method: $form.prop('method'),
        dataType: "JSON",
        data: $form.serialize(),
        beforeSend: (xhr) => {
            $form.addClass('is-loading');
            $btn.attr('disabled', true);
            $btnText.data('original', $btnText.html());
            $btnText.html($btnText.data('loading'));
            $form.parsley().reset(); // remove old error messages
            $form.find('.form-error').text('');
        },
        success: (data) => {
            $formContent.fadeOut();
            $successBox.hide();
            if ($successBox){
                $successBox.html(data.body);
                $successBox.addClass('open');
                letterAnimate();
                $successBox.fadeIn();
            }
            // TODO: Put GA tracking in here

        },
        error: (jqXHR, textStatus, errorThrown) => {
            // add back-end errors to Parsley
            try {
                let errors = jqXHR.responseJSON.errors;

                if (!errors && jqXHR.responseJSON.message) {
                    $form.find('.form-error').text(jqXHR.responseJSON.message);
                }

                for (let key in errors) {
                    let field = $form.find('[name="' + key + '"]').parsley();

                    if (field) {
                        for (let k in errors[key]) {
                            field.addError(k, {message: errors[key][k]});
                        }
                    }
                }
            }
            catch (e) {}
        },
        complete: () => {
            $form.removeClass('is-loading');
            $btn.removeAttr('disabled');
            $btnText.html($btnText.data('original'));
        }
    });
}


export function init() {

    $('.js-success-box').hide();

    $('.js-remote-form').on('submit', submitRemoteForm);


    // init parsley with different error/success classes
    $('.js-parsley').parsley({
        errorClass: 'is-invalid',
        successClass: 'is-valid',
        classHandler: function(ParsleyField) {
            return ParsleyField.$element.parents('.form-wrap');
        },
        errorsContainer: function(ParsleyField) {
            return ParsleyField.$element.parents('.form-wrap');
        },
        errorsWrapper: '<div class="invalid-feedback"></div>',
        errorTemplate: '<div></div>'
    });

    letterAnimate = function() {

       if($('.ml12').length > 0) {
           $('.ml12 .letters').each(function () {
               $(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='letter'>$&</span>"));
           });
       }

        anime.timeline({loop: false})
            .add({
                targets: '.js-success-box .ml12 .letter',
                translateX: [40,0],
                translateZ: 0,
                opacity: [0,1],
                easing: "easeOutExpo",
                duration: 1200,
                delay: function(el, i) {
                    return 500 + 30 * i;
                }
            });


        anime.timeline({loop: false})
            .add({
                targets: '.js-success-box .js-success-tick',
                rotate: '360deg',
                opacity: [0,1],
                easing: "easeInExpo",
                duration: 1200,
            })
            .add({
                targets: '.js-success-box .anime-fade-up',
                translateY:[80, 0],
                opacity: [0,1],
                easing: "spring",
                duration: 500,
                delay: function(el, i) {
                    return 300 + 100 * i;
                }
            })
            .add({
                targets: '.js-success-box .qr-success',
                scale: [0,1],
                opacity: [0,1],
                easing: "easeInExpo",
                duration: 1200,
            },'-=1500');
    }


   // letterAnimate();

}
