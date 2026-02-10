export default (hover) => ({
  show: false,
  hover: hover,
  _timer: null,
  toggle() {
    if (!this.hover) this.show = !this.show;
  },
  enter() {
    if (!this.hover) return;
    clearTimeout(this._timer);
    this.show = true;
  },
  leave() {
    if (!this.hover) return;
    this._timer = setTimeout(() => {
      this.show = false;
    }, 150);
  },
});
