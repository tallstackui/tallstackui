export default () => ({
  tallStackUiMenuMobile: false,
  init() {
    this.$watch('tallStackUiMenuMobile', (value) => {
      const html = document.querySelector('html');

      Alpine.store('tsui.side-bar').toggle(true);

      if (value) {
        html.classList.add('overflow-hidden');
      }

      if (!value) {
        html.classList.remove('overflow-hidden');
      }
    });
  },
});
