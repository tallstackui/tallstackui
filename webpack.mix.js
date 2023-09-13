const mix = require('laravel-mix')

mix.js('resources/js/tasteui.js', 'dist/tasteui.js')
    .setPublicPath('dist')
    .postCss('resources/css/tasteui.css', 'dist', [require('tailwindcss')]);

if (mix.inProduction()) {
    mix.version()
}

mix.disableSuccessNotifications()
