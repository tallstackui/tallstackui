/** @type {import('vite').UserConfig} */
import tailwindcss from '@tailwindcss/vite';

export default {
  build: {
    assetsDir: '',
    manifest: true,
    rollupOptions: {
      input: [
        'js/tallstackui.js',
        'js/tallstackui-date.js',
        'js/tallstackui-select.js',
        'js/tallstackui-tooltip.js',
        'js/tallstackui-clipboard.js',
      ],
    },
  },
  plugins: [tailwindcss()],
};
