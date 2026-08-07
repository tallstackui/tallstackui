import {
  overflow,
  unique,
  top_ui_element,
  register_ui_element,
  unregister_ui_element,
} from '../../../js/helpers';

const DISMISS = 200;

export default (state, overflowing, handle = false) => ({
  id: unique(),
  show: state,
  handle: handle,
  dragging: false,
  dismissing: false,
  delta: 0,
  origin: 0,
  init() {
    this.$watch('show', (value) => {
      overflow(value, 'modal', overflowing);

      value ? register_ui_element(this.id, 'modal') : unregister_ui_element(this.id);

      if (value) {
        this.delta = 0;
      }

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
  /**
   * The inline transform that follows the finger. The `transition: none`
   * while dragging suspends the panel's own `transition-all`, which would
   * otherwise lag the transform behind the pointer.
   *
   * @return {Object}
   */
  get handleStyle() {
    if (this.dismissing) {
      return {
        transform: `translateY(${this.delta}px)`,
        transition: `transform ${DISMISS}ms ease-in`,
      };
    }

    if (this.dragging) {
      return { transform: `translateY(${this.delta}px)`, transition: 'none' };
    }

    return {};
  },
  handleStart(event) {
    if (!this.handle || this.dismissing) {
      return;
    }

    this.dragging = true;
    this.delta = 0;
    this.origin = event.clientY;

    event.target.setPointerCapture?.(event.pointerId);
  },
  handleMove(event) {
    if (!this.dragging) {
      return;
    }

    event.preventDefault();

    const delta = event.clientY - this.origin;

    // Rubber band resistance when pulling upwards.
    this.delta = delta >= 0 ? delta : delta * 0.25;
  },
  handleEnd() {
    if (!this.dragging) {
      return;
    }

    this.dragging = false;

    const panel = this.$refs.panel;

    // Below the threshold the panel snaps back through its own
    // transition; beyond it the inline transform keeps sliding down
    // and the modal is really closed only when it is off-screen.
    if (!panel || this.delta < panel.offsetHeight * 0.25) {
      this.delta = 0;

      return;
    }

    this.dismissing = true;
    this.delta = panel.offsetHeight + 32;

    setTimeout(() => {
      this.show = false;
      this.dismissing = false;
      this.delta = 0;
    }, DISMISS);
  },
});
