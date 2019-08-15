const { mix } = require('laravel-mix');

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

mix.js('resources/assets/js/dashboard.js', 'public/js');
mix.js('resources/assets/js/assignments.js', 'public/js');
mix.js('resources/assets/js/availability.js', 'public/js');
mix.js('resources/assets/js/lineup.js', 'public/js');
mix.js('resources/assets/js/roster.js', 'public/js');
mix.js('resources/assets/js/index.js', 'public/js');
mix.sass('resources/assets/scss/style.scss', 'public/css');
mix.sass('resources/assets/scss/fonts.scss', 'public/css');
mix.browserSync('localhost:8000/teams');
