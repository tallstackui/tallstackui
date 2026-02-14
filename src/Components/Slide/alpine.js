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
  /** @return {Boolean} Whether this slide is the topmost UI element. */
  get top_ui() {
    return top_ui_element(this.id);
  },
});
