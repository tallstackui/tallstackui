/** @type {import('vite').UserConfig} */
import tailwindcss from '@tailwindcss/vite';

export default {
  build: {
    assetsDir: '',
    manifest: true,
    rollupOptions: {
      input: [
          'js/tallstackui.js',
          'tippy.js/dist/tippy.css',
      ],
    },
  },
  plugins: [
      tailwindcss(),
  ],
};
