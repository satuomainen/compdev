var elixir = require('laravel-elixir');
var gulp = require('gulp');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 |    elixir(function(mix) {
 |      mix.sass('app.scss');
 |    });
 */
 var paths = {
 'bootstrap': 'node_modules/bootstrap-sass/assets/'
 };

 elixir(function(mix) {
    mix.sass('app.scss')
        .copy(paths.bootstrap + 'fonts/bootstrap/**', 'public/fonts/bootstrap')
        .scripts([
            'vendor/components/jquery/jquery.js',
            paths.bootstrap + "javascripts/bootstrap.js"
        ], 'public/js/app.js', './');
 });
