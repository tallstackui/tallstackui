const mix = require('laravel-mix');

mix.js('js/tallstackui.js', 'dist/tallstackui.js')
    .setPublicPath('dist')
    .postCss('src/resources/css/tallstackui.css', 'dist', [require('tailwindcss')]);

if (mix.inProduction()) {
  mix.version();
}

mix.disableNotifications();
