export default {
  open: true,
  mobile: false,
  collapsible: false,
  _media: null,
  _mediaHandler: null,
  init() {
    const media = window.matchMedia('(max-width: 768px)');

    this.mobile = media.matches;

    this._mediaHandler = (e) => {
      this.mobile = e.matches;

      if (this.mobile) {
        this.open = true;
      }
    };

    media.addEventListener('change', this._mediaHandler);
    this._media = media;

    const sidebar = localStorage.getItem('side-bar');

    this.open = Boolean(sidebar !== null ? JSON.parse(sidebar) : true);

    if (this.mobile) {
      this.open = false;
    }
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
