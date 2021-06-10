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

/*
import MarkerClusterer from '@googlemaps/markerclustererplus';
import GoogleMaps from '@googlemaps/js-api-loader';
@googlemaps/js-api-loader
*/

mix.js(['resources/js/app.js','resources/js/owl.carousel.min.js'], 'public/js/')
    .sass('resources/sass/app.scss', 'public/css')
    .css('resources/css/app.css', 'public/css')
    .version();

mix.css('resources/css/owl.carousel.min.css', 'public/css');
