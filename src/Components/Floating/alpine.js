export default (show, anchorFn) => ({
  init() {
    const anchor = typeof anchorFn === 'function' ? anchorFn() : null;

    if (this.$el.classList.contains('w-full') && anchor) {
      this.$watch(show, (value) => {
        if (value) {
          this.$nextTick(() => {
            this.$el.style.width = anchor.offsetWidth + 'px';
          });
        }
      });

      let raf;
      const recalc = () => {
        this.$el.style.width = anchor.offsetWidth + 'px';
      };

      new MutationObserver(() => {
        cancelAnimationFrame(raf);
        raf = requestAnimationFrame(recalc);
      }).observe(this.$el, { childList: true, subtree: true });
    }

    if (anchor) {
      const overlay = anchor.closest('[x-data*=tallstackui_modal], [x-data*=tallstackui_slide]');

      if (overlay) {
        overlay.addEventListener('close', () => {
          this[show] = false;
        });
      }
    }
  },
});
