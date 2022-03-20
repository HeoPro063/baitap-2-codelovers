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
    .vue()
    .sass('resources/sass/app.scss', 'public/css');


    mix.styles([
        'public/plugins/fontawesome-free/css/all.min.css',
        'public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
        'public/plugins/icheck-bootstrap/icheck-bootstrap.min.css',
        'public/plugins/jqvmap/jqvmap.min.css',
        'public/dist/css/adminlte.min.css',
        'public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css',
        'public/plugins/daterangepicker/daterangepicker.css',
        'public/plugins/summernote/summernote-bs4.min.css',
    ], 'public/css/all.css');
    
    mix.scripts([
        'public/plugins/jquery/jquery.min.js',
        'public/plugins/jquery-ui/jquery-ui.min.js',
        'public/plugins/bootstrap/js/bootstrap.bundle.min.js',
        'public/plugins/chart.js/Chart.min.js',
        'public/plugins/sparklines/sparkline.js',
        'public/plugins/jqvmap/jquery.vmap.min.js',
        'public/plugins/jqvmap/maps/jquery.vmap.usa.js',
        'public/plugins/jquery-knob/jquery.knob.min.js',
        'public/plugins/moment/moment.min.js',
        'public/plugins/daterangepicker/daterangepicker.js',
        'public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
        'public/plugins/summernote/summernote-bs4.min.js',
        'public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js',
        'public/dist/js/adminlte.js',
        'public/dist/js/demo.js',
        'public/dist/js/pages/dashboard.js',
    ], 'public/js/all.js');
    