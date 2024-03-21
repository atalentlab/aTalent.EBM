let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
    resolve: {
        alias: {
            'circle-progress': 'jquery-circle-progress',
            'core': path.resolve(__dirname, 'node_modules/tabler-ui/dist/assets/js/core.js'),
            'vector-map': 'jvectormap'
        }
    }
});

// we handle copying images, fonts and other CSS referenced assets to the public directory ourselves

/*mix.options({
 	processCssUrls: false
});*/


// CMS
mix.js('resources/assets/admin/js/app.js', 'public/admin/js')
    .sass('resources/assets/admin/sass/app.scss', 'public/admin/css');

// Front
mix.js('resources/assets/main/js/app.js', 'public/main/js')
    .sass('resources/assets/main/sass/app.scss', 'public/main/css');


/*mix.autoload({
    jquery: [ '$', 'jQuery', 'jquery', 'window.jQuery'],
});*/

/*

mix.extract([
    'lodash',
    'popper.js',
    'bootstrap',
    'bootstrap-datepicker',
    'bootstrap-sass',
    'chart.js',
    'd3',
    'jquery',
    'jquery-circle-progress',
    'jvectormap',
    'moment',
    'requirejs/require',
    'select2',
    'selectize',
    'sparkline',
    'tablesorter'
], 'public/js/vendor.js');

// add a cache busting query string for production assets
if (mix.inProduction()) {
    mix.version();
}

mix.setPublicPath('public');*/
