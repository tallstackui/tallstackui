/** @type {import('vite').UserConfig} */
export default {
  build: {
    assetsDir: '',
    manifest: true,
    rollupOptions: {
      input: ['js/tallstackui.js'],
      output: {
        manualChunks: false,
        inlineDynamicImports: true,
      },
    },
  },
};
