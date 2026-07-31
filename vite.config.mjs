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
        'js/tallstackui-clipboard.js',
        'js/tallstackui-upload.js',
        'js/tallstackui-editor.js',
        'js/tallstackui-chart.js',
      ],
    },
  },
  plugins: [tailwindcss()],
};
