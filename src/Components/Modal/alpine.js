import {
  overflow,
  unique,
  top_ui_element,
  register_ui_element,
  unregister_ui_element,
} from '../../../js/helpers';
export default (state, overflowing) => ({
  id: unique(),
  show: state,
  init() {
    this.$watch('show', (value) => {
      overflow(value, 'modal', overflowing);

      value ? register_ui_element(this.id, 'modal') : unregister_ui_element(this.id);

      this.$el.dispatchEvent(new CustomEvent(value ? 'open' : 'close'));
    });
  },
  /**
   * Drop this modal from the registry when it is torn down (e.g. removed by
   * Livewire/wire:navigate while still open) and restore the body scroll-lock
   * if no other overlay remains, preventing an orphaned scroll-lock.
   *
   * @return {void}
   */
  destroy() {
    unregister_ui_element(this.id);

    if (window.__tsui_elements.length === 0) {
      overflow(false, 'modal', overflowing);
    }
  },
  /** @return {Boolean} Whether this modal is the topmost UI element. */
  get top_ui() {
    return top_ui_element(this.id);
  },
});
