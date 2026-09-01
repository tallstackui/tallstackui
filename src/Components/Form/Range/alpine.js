import { lockable } from '../../../../js/helpers';
import { close, open } from '../../Tooltip/alpine';

export default (model, initial, min, max, step, disabled, readonly, tooltip) => ({
  model: model,
  start: initial[0],
  end: initial[1],
  tooltip: tooltip,
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
  // A native thumb never leaves the input, so its center runs from half a
  // thumb in to half a thumb short of the full width, not from 0% to 100%.
  anchorStyle(value) {
    const percent = this.percent(value);
    const thumb = this.$refs.start.offsetHeight;

    return { left: `calc(${percent}% + ${(0.5 - percent / 100) * thumb}px)` };
  },
  moveStart(event) {
    this.start = Math.min(Number(event.target.value), this.end);

    // The native input keeps whatever the pointer reached, so it has to be
    // written back or the thumb visually runs past the one it was clamped to.
    event.target.value = this.start;

    this.bubble('start');
  },
  moveEnd(event) {
    this.end = Math.max(Number(event.target.value), this.start);

    event.target.value = this.end;

    this.bubble('end');
  },
  // The thumb is a pseudo-element, so the balloon anchors on an invisible
  // marker kept over it and reuses the page tooltip instead of its own bubble.
  bubble(thumb) {
    if (!this.tooltip) {
      return;
    }

    open(this.$refs[thumb === 'start' ? 'tooltipStart' : 'tooltipEnd'], String(this[thumb]));
  },
  // Wired to change, not input, so a drag costs one round trip and not one per pixel.
  sync() {
    if (this.tooltip) {
      close(this.$refs.tooltipStart);
      close(this.$refs.tooltipEnd);
    }

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
