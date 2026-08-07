import { wireChange } from '../../../js/helpers';

export default (
  model = null,
  options = [],
  preview = false,
  vertical = false,
  loop = true,
  disabled = false,
  livewire = false,
  value = null,
  change = null
) => ({
  model: model,
  options: options,
  preview: preview,
  vertical: vertical,
  loop: loop,
  disabled: disabled,
  livewire: livewire,
  change: change,
  items: [],
  slot: 0,
  dragging: false,
  jumping: false,
  delta: 0,
  origin: 0,
  size: 0,
  init() {
    this.loop = this.loop && this.options.length > 1;

    // Looping clones the last option before the first and the first after
    // the last, so crossing an edge animates into the clone and settle()
    // then teleports to its real twin without a visible jump.
    this.items = this.loop
      ? [this.options[this.options.length - 1], ...this.options, this.options[0]]
      : this.options;

    this.slot = Math.max(this.locate(this.livewire ? this.model : value), 0) + (this.loop ? 1 : 0);

    this.$watch('model', (current) => {
      const located = this.locate(current);

      if (located !== -1) {
        this.slot = located + (this.loop ? 1 : 0);
      }
    });
  },
  // Percentages resolve against the track, whose computed size always
  // matches one viewport, so each 100% (or a third on preview, centered
  // by one extra third) walks exactly one slot.
  get transform() {
    const axis = this.vertical ? 'translateY' : 'translateX';
    const size = this.preview ? 100 / 3 : 100;
    const centering = this.preview ? 100 / 3 : 0;

    return `${axis}(calc(${centering}% - ${this.slot * size}% + ${this.delta}px))`;
  },
  next() {
    this.navigate(1);
  },
  previous() {
    this.navigate(-1);
  },
  navigate(steps) {
    this.settle();

    this.to(this.slot + steps);
  },
  to(target) {
    if (this.disabled || this.options.length === 0) {
      return;
    }

    target = Math.max(0, Math.min(target, this.items.length - 1));

    if (target === this.slot) {
      return;
    }

    const direction = target > this.slot ? 'next' : 'prev';

    this.slot = target;

    const index = this.loop ? (target - 1 + this.options.length) % this.options.length : target;
    const option = this.options[index];

    this.model = option.value;

    if (!this.livewire && this.$refs.input) {
      this.$refs.input.value = option.value;
    }

    this.$root.dispatchEvent(
      new CustomEvent('swap', {
        detail: { value: option.value, label: option.label, index: index, direction: direction },
      })
    );

    wireChange(this.change, this.model);
  },
  start(event) {
    if (this.disabled || this.options.length < 2 || event.button > 0) {
      return;
    }

    // The template of the x-for makes items unreliable to measure, so the
    // step size comes from the viewport, of which every item is a fraction.
    const viewport = this.$refs.viewport.getBoundingClientRect();
    const size = this.vertical ? viewport.height : viewport.width;

    this.size = this.preview ? size / 3 : size;

    if (this.size === 0) {
      return;
    }

    this.dragging = true;
    this.delta = 0;
    this.origin = this.vertical ? event.clientY : event.clientX;

    this.$refs.viewport.setPointerCapture?.(event.pointerId);
  },
  move(event) {
    if (!this.dragging) {
      return;
    }

    event.preventDefault();

    let delta = (this.vertical ? event.clientY : event.clientX) - this.origin;

    // Rubber band resistance beyond the first and last slots.
    const limit =
      delta > 0 ? this.slot * this.size : (this.items.length - 1 - this.slot) * this.size;
    const magnitude = Math.abs(delta);

    if (magnitude > limit) {
      delta = Math.sign(delta) * (limit + (magnitude - limit) * 0.25);
    }

    this.delta = delta;
  },
  end() {
    if (!this.dragging) {
      return;
    }

    const steps = this.size > 0 ? Math.round(-this.delta / this.size) : 0;

    this.dragging = false;
    this.delta = 0;

    if (steps !== 0) {
      this.navigate(steps);
    }
  },
  // Resting on a clone is visually identical to resting on its real twin,
  // so the teleport can happen after the transition or lazily before the
  // next navigation, whichever comes first.
  settle() {
    if (!this.loop) {
      return;
    }

    if (this.slot === 0) {
      this.jump(this.items.length - 2);
    } else if (this.slot === this.items.length - 1) {
      this.jump(1);
    }
  },
  jump(slot) {
    this.jumping = true;
    this.slot = slot;

    requestAnimationFrame(() =>
      requestAnimationFrame(() => {
        this.jumping = false;
      })
    );
  },
  locate(current) {
    if (current === null || current === undefined) {
      return -1;
    }

    return this.options.findIndex((option) => String(option.value) === String(current));
  },
});
