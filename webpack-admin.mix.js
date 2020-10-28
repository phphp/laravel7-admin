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

mix.setPublicPath('public/admin')
   .setResourceRoot('/admin/')

mix.js('resources/admin_js/app.js', 'js/admin.js')
   .sass('resources/admin_sass/app.scss', 'css/admin.css');

mix.webpackConfig({
   output: {
      publicPath: '/admin/',
      chunkFilename: 'js/chunks/[name].js',
   }
});