export default () => ({
  show: false,
  size: 'md',
  width: 'md',
  timeout: null,
  root: null,
  panel: null,
  siblings: null,
  init() {
    const parent = this.$el.closest('[data-tsui-dropdown-size]');

    if (parent) {
      this.size = parent.dataset.tsuiDropdownSize;
      this.width = parent.dataset.tsuiDropdownWidth ?? this.size;
    }

    this.root = this.$el;

    // Nested submenus get the panel of their parent submenu, so a sibling
    // opening by hover never closes the submenus above it.
    this.panel = this.$el.closest('[data-floating]');

    this.siblings = (event) => {
      if (event.detail === this.root) {
        return;
      }

      clearTimeout(this.timeout);

      this.show = false;
    };

    this.panel?.addEventListener('tallstackui-submenu', this.siblings);
  },
  destroy() {
    clearTimeout(this.timeout);

    this.panel?.removeEventListener('tallstackui-submenu', this.siblings);
  },
  // `hover` lives in the parent dropdown scope.
  enter(event) {
    if (!this.hover || event.pointerType !== 'mouse') {
      return;
    }

    clearTimeout(this.timeout);

    if (this.show) {
      return;
    }

    // Without it, a diagonal path towards the open submenu would open every
    // sibling the pointer crosses on the way.
    this.timeout = setTimeout(() => this.open(), 100);
  },
  leave(event) {
    if (!this.hover || event.pointerType !== 'mouse') {
      return;
    }

    clearTimeout(this.timeout);

    this.timeout = setTimeout(() => this.close(), 300);
  },
  open() {
    this.show = true;

    this.panel?.dispatchEvent(new CustomEvent('tallstackui-submenu', { detail: this.root }));

    this.$refs.dropdown.dispatchEvent(new CustomEvent('open', { detail: { status: true } }));
  },
  close() {
    if (!this.show) {
      return;
    }

    this.show = false;

    this.$refs.dropdown.dispatchEvent(new CustomEvent('open', { detail: { status: false } }));
  },
});
