import { overflow } from '../../../../js/helpers';

export default () => ({
  tallStackUiMenuMobile: false,
  init() {
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
