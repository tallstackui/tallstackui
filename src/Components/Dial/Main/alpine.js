export default (hover) => ({
  show: false,
  hover: hover,
  _timer: null,
  /**
   * Toggle the dial visibility. Only works when hover mode is off.
   *
   * @return {void}
   */
  toggle() {
    if (!this.hover) this.show = !this.show;
  },
  /**
   * Show the dial on mouse enter. Only works when hover mode is on.
   *
   * @return {void}
   */
  enter() {
    if (!this.hover) return;
    clearTimeout(this._timer);
    this.show = true;
  },
  /**
   * Hide the dial on mouse leave with a short delay. Only works when hover mode is on.
   *
   * @return {void}
   */
  leave() {
    if (!this.hover) return;
    this._timer = setTimeout(() => {
      this.show = false;
    }, 150);
  },
});
