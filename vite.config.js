/** @type {import('vite').UserConfig} */
export default {
  build: {
    assetsDir: '',
    manifest: true,
    rollupOptions: {
      input: ['js/tallstackui.js', 'src/resources/css/tallstackui.css'],
    },
  },
};
