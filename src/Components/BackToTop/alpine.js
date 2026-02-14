export default (anchor, smooth) => ({
  show: false,
  init() {
    if (anchor) {
      const el = document.querySelector(anchor);

      if (el) {
        new IntersectionObserver(([entry]) => {
          this.show = !entry.isIntersecting;
        }).observe(el);

        return;
      }
    }

    window.addEventListener(
      'scroll',
      () => {
        this.show = window.scrollY > 200;
      },
      { passive: true }
    );
  },
  /**
   * Scroll the page back to the top.
   *
   * @return {void}
   */
  scroll() {
    window.scrollTo({ top: 0, behavior: smooth ? 'smooth' : 'instant' });
  },
});
