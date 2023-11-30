export default (animated, wire, text, enter, leave, close) => ({
  show: animated === false && wire === false,
  animated: animated,
  type: 'primary',
  text: text,
  enter: enter,
  leave: leave,
  close: close,
  init() {
    if (this.animated) {
      setTimeout(() => this.show = true, (this.enter ?? 3) * 1000);

      if (this.leave) {
        setTimeout(() => this.show = false, this.leave * 1000);
      }
    }
  },
  add(event) {
    this.type = event.detail.type;
    this.text = event.detail.text;
    this.close = event.detail.close;
    this.enter = event.detail.enter;
    this.leave = event.detail.leave;

    if (!this.enter) {
      this.show = true;
    } else {
      setTimeout(() => this.show = true, this.enter * 1000);
    }

    if (this.leave) {
      setTimeout(() => this.show = false, this.leave * 1000);
    }
  },
});
