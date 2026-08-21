export default (options) => ({
  active: null,
  hidden: [],
  pointer: { x: 0, y: 0 },
  tip: { width: 0, height: 0 },
  length: options.length ?? 0,
  series: options.series ?? [],
  slices: options.slices ?? [],
  labels: options.labels ?? [],
  scale: options.scale ?? { min: 0, max: 0, flat: true },
  plot: options.plot ?? { bottom: 96, band: 92, width: 100 },
  geometry: options.arc ?? { center: 50, radius: 46, inner: 0 },
  type: options.type ?? 'area',
  slotted: options.slotted ?? false,
  rescale: options.rescale ?? false,
  get crosshair() {
    return this.active === null ? 0 : this.abscissa(this.active);
  },
  get position() {
    const plot = this.$refs.plot;

    if (!plot) {
      return '';
    }

    const width = plot.getBoundingClientRect().width;
    const half = this.tip.width / 2;

    // A pie has no horizontal axis to anchor to, so it trails the pointer.
    const anchor = this.radial
      ? this.pointer.x
      : (this.abscissa(this.active) / this.plot.width) * width;
    const left = Math.max(half, Math.min(width - half, anchor));
    // Clamped by the box height: anchored any higher, the whole tooltip
    // would land above the plot and be clipped by an overflow-hidden card.
    const top = Math.max(this.tip.height, this.pointer.y - 12);

    return `left: ${left}px; top: ${top}px; transform: translate(-50%, -100%)`;
  },
  get radial() {
    return this.type === 'pie' || this.type === 'donut';
  },
  get rows() {
    if (this.active === null) {
      return [];
    }

    if (this.radial) {
      const slice = this.slices[this.active];

      return slice ? [{ name: slice.name, value: slice.value, color: slice.color }] : [];
    }

    return this.series
      .map((entry, index) => ({ entry, index }))
      .filter(({ index }) => this.visible(index))
      .filter(({ entry }) => entry.formatted[this.active] !== undefined && entry.formatted[this.active] !== null)
      .map(({ entry }) => ({
        name: entry.name,
        value: entry.formatted[this.active],
        color: entry.color,
      }));
  },
  get title() {
    if (!this.radial) {
      return this.labels[this.active] ?? '';
    }

    // Hiding a slice redistributes the circle, so the server percentage goes stale.
    const total = this.visibleValues().reduce((carry, value) => carry + value, 0);
    const slice = this.slices[this.active];

    return slice && total > 0 ? `${Math.round((slice.raw / total) * 1000) / 10}%` : '';
  },
  abscissa(index) {
    if (index === null || this.length < 1) {
      return 0;
    }

    if (this.slotted) {
      return ((index + 0.5) * this.plot.width) / this.length;
    }

    return this.length < 2 ? 0 : (index / (this.length - 1)) * this.plot.width;
  },
  // A pie cannot be rescaled by a transform: removing a slice redistributes
  // every remaining angle, so the geometry is mirrored here.
  arc(index) {
    if (!this.visible(index)) {
      return '';
    }

    const values = this.visibleValues();
    const total = values.reduce((carry, value) => carry + value, 0);

    if (total <= 0) {
      return '';
    }

    let angle = -Math.PI / 2;

    for (let position = 0; position < this.slices.length; position++) {
      if (!this.visible(position)) {
        continue;
      }

      const sweep = (Math.max(0, this.slices[position].raw) / total) * 2 * Math.PI;

      if (position === index) {
        return this.sector(angle, angle + sweep);
      }

      angle += sweep;
    }

    return '';
  },
  clear() {
    this.active = null;
  },
  count() {
    return this.radial ? this.slices.length : this.series.length;
  },
  // Beziers are affine invariant, so hiding a series is a transform on the
  // remaining groups rather than a curve recomputed here.
  factors() {
    if (!this.rescale || !this.hidden.length) {
      return null;
    }

    const values = this.series
      .filter((entry, position) => this.visible(position))
      .flatMap((entry) => entry.data)
      .filter((value) => value !== null);

    if (!values.length) {
      return null;
    }

    const min = Math.min(...values);
    const max = Math.max(...values);
    const range = max - min;

    if (range <= 0 || this.scale.max - this.scale.min <= 0) {
      return null;
    }

    const factor = (this.scale.max - this.scale.min) / range;

    return {
      factor,
      offset:
        this.plot.bottom -
        (this.plot.band * (this.scale.min - min)) / range -
        factor * this.plot.bottom,
    };
  },
  locate(event) {
    const rect = this.$refs.plot?.getBoundingClientRect();

    if (!rect || !event) {
      return;
    }

    this.pointer = { x: event.clientX - rect.left, y: event.clientY - rect.top };

    // Measured a tick later: in the same tick x-text has not filled the rows
    // yet, so the box still has the size of an empty tooltip.
    this.$nextTick(() => {
      const tip = this.$refs.tip;

      this.tip = { width: tip?.offsetWidth ?? 0, height: tip?.offsetHeight ?? 0 };
    });
  },
  // Markers are html, so they cannot ride the svg transform.
  marker(index, x, y) {
    const factors = this.visible(index) ? this.factors() : null;

    return `left: ${x}%; top: ${factors ? factors.offset + factors.factor * y : y}%`;
  },
  pick(index, event) {
    this.active = index;

    this.locate(event);
  },
  // On touch, pointerleave fires right after the tap and would blank the
  // tooltip the instant it appeared. Touch closes by tapping outside instead.
  release(event) {
    if (!event || event.pointerType === 'mouse') {
      this.clear();
    }
  },
  sector(from, to) {
    const { center, radius, inner } = this.geometry;
    const point = (angle, distance) =>
      `${this.round(center + Math.cos(angle) * distance)},${this.round(center + Math.sin(angle) * distance)}`;

    // One arc command cannot express a full turn: its ends would coincide.
    if (to - from >= 2 * Math.PI - 1e-9) {
      const half = from + Math.PI;
      const arc = (distance, sweep, angle) =>
        `A${distance},${distance} 0 1,${sweep} ${point(angle, distance)}`;
      const turn = (distance, sweep) =>
        `${arc(distance, sweep, half)} ${arc(distance, sweep, from)}`;

      const ring = `M${point(from, radius)} ${turn(radius, 1)}`;

      return inner <= 0 ? `${ring} Z` : `${ring} M${point(from, inner)} ${turn(inner, 0)} Z`;
    }

    const large = to - from > Math.PI ? 1 : 0;
    const outer = `M${point(from, radius)} A${radius},${radius} 0 ${large},1 ${point(to, radius)}`;

    return inner <= 0
      ? `${outer} L${center},${center} Z`
      : `${outer} L${point(to, inner)} A${inner},${inner} 0 ${large},0 ${point(from, inner)} Z`;
  },
  toggle(index) {
    const position = this.hidden.indexOf(index);

    if (position === -1) {
      // The last visible entry stays: an empty plot has no scale left to rescale to.
      if (this.hidden.length >= this.count() - 1) {
        return;
      }

      this.hidden.push(index);

      return;
    }

    this.hidden.splice(position, 1);
  },
  track(event) {
    const plot = this.$refs.plot;

    if (!plot || this.length < 1) {
      return;
    }

    const rect = plot.getBoundingClientRect();

    // Measured on the event, not in init(): inside a lazy Livewire component
    // the element has no size until the placeholder is swapped out.
    if (!rect.width) {
      return;
    }

    const ratio = (event.clientX - rect.left) / rect.width;

    const index = this.slotted
      ? Math.floor(ratio * this.length)
      : Math.round(ratio * (this.length - 1));

    this.active = Math.max(0, Math.min(this.length - 1, index));

    this.locate(event);
  },
  transform(index) {
    if (!this.visible(index)) {
      return '';
    }

    const factors = this.factors();

    return factors ? `translate(0 ${factors.offset}) scale(1 ${factors.factor})` : '';
  },
  visible(index) {
    return this.hidden.indexOf(index) === -1;
  },
  visibleValues() {
    return this.slices
      .filter((slice, index) => this.visible(index))
      .map((slice) => Math.max(0, slice.raw));
  },
  round(value) {
    return Math.round(value * 100) / 100;
  },
});
