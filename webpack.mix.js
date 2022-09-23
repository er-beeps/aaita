const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .styles([
        'public/packages/backpack/base/css/bundle.css',
    'public/css/jquery.fancybox.min.css',
        'public/packages/source-sans-pro/source-sans-pro.css',
        'public/css/nepali.datepicker.v2.2.min.css',
        'public/packages/line-awesome/css/line-awesome.min.css',
        'public/packages/select2/dist/css/select2.min.css',
        'public/packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css',
        'public/packages/dataTables-custom/css/dataTables.bootstrap4.min.css',
        'public/packages/dataTables-custom/css/select.dataTables.min.css',
    ], 'public/css/vendor.css')
    .scripts([
        'public/packages/backpack/base/js/bundle.js',
        'public/packages/select2/dist/js/select2.full.min.js',
        'public/js/nepali.datepicker.v2.2.min.js',
        'public/js/date_helper.js',
        'public/js/jquery.fancybox.min.js',
        'public/packages/moment/min/moment.min.js',
        'public/packages/dataTables-custom/js/jquery.dataTables.min.js',
        'public/packages/dataTables-custom/js/dataTables.bootstrap4.min.js',
        'public/packages/dataTables-custom/js/dataTables.select.min.js',
    ], 'public/js/vendor.js')
    .version();
