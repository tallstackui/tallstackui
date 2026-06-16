import {
  unique,
  overflow,
  register_ui_element,
  unregister_ui_element,
  top_ui_element,
} from '../../../js/helpers';

export default (state, overflowing) => ({
  id: unique(),
  show: state,
  init() {
    this.$watch('show', (value) => {
      overflow(value, 'slide', overflowing);

      value ? register_ui_element(this.id, 'slide') : unregister_ui_element(this.id);

      this.$el.dispatchEvent(new CustomEvent(value ? 'open' : 'close'));
    });
  },
  /**
   * Drop this slide from the registry when it is torn down (e.g. removed by
   * Livewire/wire:navigate while still open) and restore the body scroll-lock
   * if no other overlay remains, preventing an orphaned scroll-lock.
   *
   * @return {void}
   */
  destroy() {
    unregister_ui_element(this.id);

    if (window.__tsui_elements.length === 0) {
      overflow(false, 'slide', overflowing);
    }
  },
  /** @return {Boolean} Whether this slide is the topmost UI element. */
  get top_ui() {
    return top_ui_element(this.id);
  },
});
