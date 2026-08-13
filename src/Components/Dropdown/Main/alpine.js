export default (animate, hover = false) => ({
  show: false,
  animate: animate,
  hover: hover,
  timeout: null,
  scroll: null,
  init() {
    this.scroll = () => {
      const element = this.$refs.dropdown?.getBoundingClientRect();
      this.show =
        element?.bottom < 0 || (element?.top > window.innerHeight && this.show) ? false : this.show;
    };

    window.addEventListener('scroll', this.scroll);
  },
  destroy() {
    clearTimeout(this.timeout);

    window.removeEventListener('scroll', this.scroll);
  },
  // The pointerType guard restricts hover to real pointers: on touch the tap
  // fires pointerenter right before click, and the two would cancel each other.
  enter(event) {
    if (!this.hover || event.pointerType !== 'mouse') {
      return;
    }

    clearTimeout(this.timeout);

    if (this.show) {
      return;
    }

    this.show = true;

    this.$refs.dropdown.dispatchEvent(new CustomEvent('open', { detail: { status: true } }));
  },
  // The delay is a grace period to cross the offset gap between the trigger
  // and the floating panel without collapsing the dropdown mid-way.
  leave(event) {
    if (!this.hover || event.pointerType !== 'mouse') {
      return;
    }

    clearTimeout(this.timeout);

    this.timeout = setTimeout(() => {
      if (!this.show) {
        return;
      }

      this.show = false;

      this.$refs.dropdown.dispatchEvent(new CustomEvent('open', { detail: { status: false } }));
    }, 300);
  },
});
