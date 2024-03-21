try {
    window.$ = window.jQuery = require('jquery');

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') // CSRF token for ajax forms
        }
    });

    window.anime = require('animejs/lib/anime');
    window.debounce = require('lodash.debounce');
} catch (e) {}
