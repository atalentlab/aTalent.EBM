//window._ = require('lodash');
window.Popper = require('popper.js').default;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    window.Popper = require('popper.js').default;

    require('bootstrap');

    require('datatables.net');

    require('tabler-ui/dist/assets/js/core');

    //require('tabler-ui/dist/assets/js/dashboard');

    require('tabler-ui/src/assets/plugins/input-mask/js/jquery.mask.min');


    require('selectize');

    Mustache = require('mustache');

    require('nestable2');

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') // CSRF token for ajax forms
        }
    });

} catch (e) {}
