/** @type {import('vite').UserConfig} */
import tailwindcss from '@tailwindcss/vite';

export default {
  build: {
    assetsDir: '',
    manifest: true,
    rollupOptions: {
      input: [
          'js/tallstackui.js',
          'css/v3.css',
          'tippy.js/dist/tippy.css',
      ],
    },
  },
  plugins: [tailwindcss()],
};
