import { overflow } from '../../../../js/helpers';

export default () => ({
  tallStackUiMenuMobile: false,
  tallStackUiSettled: false,
  init() {
    // The padding that clears the sidebar is bound, so it only lands once Alpine
    // boots. A transition declared alongside it animates that first application,
    // sliding the whole content in on every page load, so it is only attached
    // after the browser has painted the padding: two frames, because a single
    // one still runs before the style recalc of the frame the padding landed on.
    requestAnimationFrame(() => requestAnimationFrame(() => (this.tallStackUiSettled = true)));

    this.$watch('tallStackUiMenuMobile', (value) => {
      // The shared helper is used instead of a class on the html element so
      // the lock is coordinated with whatever else may hold it, such as a
      // modal opened from inside the drawer.
      overflow(value, 'side-bar');
    });
  },
  /**
   * Release the body scroll-lock when the layout is torn down with the drawer
   * still open (e.g. removed by Livewire/wire:navigate), preventing an
   * orphaned scroll-lock.
   *
   * @return {void}
   */
  destroy() {
    if (this.tallStackUiMenuMobile) {
      overflow(false, 'side-bar');
    }
  },
});
