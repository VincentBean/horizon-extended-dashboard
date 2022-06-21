const mix = require('laravel-mix');

mix.setPublicPath('public')
    .js('resources/js/app.js', 'public')
    .version()
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ]);
