import { lockable } from '../../../../js/helpers';

export default (model, initial, min, max, step, disabled, readonly) => ({
  model: model,
  start: initial[0],
  end: initial[1],
  dragging: null,
  min: min,
  max: max,
  step: step,
  ...lockable(disabled, readonly),
  init() {
    this.$watch('model', (value) => {
      if (!Array.isArray(value) || value.length !== 2) {
        return;
      }

      const [start, end] = value.map(Number);

      if (start === this.start && end === this.end) {
        return;
      }

      this.start = start;
      this.end = end;
    });
  },
  percent(value) {
    return ((value - this.min) / (this.max - this.min)) * 100;
  },
  moveStart(event) {
    this.dragging = 'start';
    this.start = Math.min(Number(event.target.value), this.end);

    // The native input keeps whatever the pointer reached, so it has to be
    // written back or the thumb visually runs past the one it was clamped to.
    event.target.value = this.start;
  },
  moveEnd(event) {
    this.dragging = 'end';
    this.end = Math.max(Number(event.target.value), this.start);

    event.target.value = this.end;
  },
  // Wired to change, not input, so a drag costs one round trip and not one per pixel.
  sync() {
    this.dragging = null;

    if (this.model === null) {
      return;
    }

    this.model = [this.start, this.end];
  },
  // The inputs overlap, so when the thumbs meet the one on top wins the pointer.
  // At the far right only the starting thumb has anywhere left to go, and at the
  // far left only the ending one does, which is what the halves stand for.
  get startOnTop() {
    return this.percent(this.start) > 50;
  },
  get progressStyle() {
    return {
      left: `${this.percent(this.start)}%`,
      right: `${100 - this.percent(this.end)}%`,
    };
  },
});
