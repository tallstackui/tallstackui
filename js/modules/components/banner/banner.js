export default (animated, wire, text, enter, leave, effect, close) => ({
  show: animated === false && wire === false,
  animated: animated,
  type: 'primary',
  text: text,
  enter: enter,
  leave: leave,
  effect: effect,
  close: close,
  init() {
    if (this.animated) {
      setTimeout(() => this.show = true, this.enter ? this.enter * 1000 : 0);

      if (this.leave) {
        setTimeout(() => this.show = false, this.leave * 1000);
      }
    }

    this.$watch('show', (value) => {
      if (value === false || !wire || !this.leave) {
        return;
      }

      setTimeout(() => this.show = false, this.leave * 1000);
    });
  },
  add(event) {
    this.type = event.detail.type;
    this.text = event.detail.title;
    this.description = event.detail.description;
    this.close = event.detail.close;
    this.enter = event.detail.enter;
    this.leave = event.detail.leave;
    this.effect = event.detail.effect;

    if (!this.enter) {
      this.show = true;
    } else {
      setTimeout(() => this.show = true, this.enter * 1000);
    }
  },
});
