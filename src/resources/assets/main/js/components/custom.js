export function init() {

    // $('.js-language-dropdown-toggle').on('click',function () {
    //     $(this).toggleClass('open');
    // });

    $('.js-switch-locale').on('click' , function () {
        $('.js-switch-locale-form').submit();
    });

}