export default {
  open: true,
  mobile: false,
  collapsible: false,
  _media: null,
  _mediaHandler: null,
  /**
   * Whether the sidebar is rendered as a rail: collapsible, closed and out of the mobile drawer.
   *
   * @return {Boolean}
   */
  get collapsed() {
    return this.collapsible && !this.open && !this.mobile;
  },
  init() {
    const media = window.matchMedia('(max-width: 768px)');

    this.mobile = media.matches;

    this._mediaHandler = (event) => {
      this.mobile = event.matches;

      // Leaving mobile hides the drawer through CSS alone, so the layout is
      // told to close it: a drawer left open would hold the scroll lock.
      if (!event.matches) {
        window.dispatchEvent(
          new CustomEvent('tallstackui-menu-mobile', { detail: { status: false } })
        );
      }
    };

    media.addEventListener('change', this._mediaHandler);
    this._media = media;

    const sidebar = localStorage.getItem('side-bar');

    // The open state is the desktop preference alone: the mobile drawer
    // always renders expanded, which the collapsed getter takes care of.
    this.open = Boolean(sidebar !== null ? JSON.parse(sidebar) : true);
  },
  /**
   * Toggle the sidebar.
   *
   * @param {Boolean|Null} value
   *
   * @return {void}
   */
  toggle(value = null) {
    this.open = value ?? !this.open;

    localStorage.setItem('side-bar', JSON.stringify(this.open));
  },
};
