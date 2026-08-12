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
    // The box hangs above its own anchor, so an anchor closer to the top than
    // the box is tall leaves the whole tooltip outside the plot, where an
    // ancestor with overflow-hidden clips it away.
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
      .filter(({ entry }) => entry.formatted[this.active] !== undefined)
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

    // Recomputed rather than read off the server value: hiding a slice
    // redistributes the whole circle, so the stored percentage goes stale.
    const total = this.visibleValues().reduce((carry, value) => carry + value, 0);
    const slice = this.slices[this.active];

    return slice && total > 0 ? `${Math.round((slice.raw / total) * 1000) / 10}%` : '';
  },
  /**
   * Where an index sits horizontally. A curve puts its first and last point on
   * the edges; a bar owns a slot and is read from the middle of it.
   */
  abscissa(index) {
    if (index === null || this.length < 1) {
      return 0;
    }

    if (this.slotted) {
      return ((index + 0.5) * this.plot.width) / this.length;
    }

    return this.length < 2 ? 0 : (index / (this.length - 1)) * this.plot.width;
  },
  /**
   * Unlike a curve, a pie cannot be rescaled by a transform: removing a slice
   * redistributes every remaining angle, so the geometry is mirrored here.
   */
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
  /**
   * Rescaling a domain is an affine map in y, and cubic Beziers are affine
   * invariant, so hiding a series is a transform on the remaining groups
   * rather than a curve recomputed in JavaScript.
   */
  factors() {
    if (!this.rescale || !this.hidden.length) {
      return null;
    }

    const values = this.series
      .filter((entry, position) => this.visible(position))
      .flatMap((entry) => entry.data);

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

    // Measured a tick later because the size only settles once x-text has
    // filled the rows, and reading it in the same tick returns the size of
    // an empty tooltip. Hidden through visibility so it is measurable at all.
    this.$nextTick(() => {
      const tip = this.$refs.tip;

      this.tip = { width: tip?.offsetWidth ?? 0, height: tip?.offsetHeight ?? 0 };
    });
  },
  // Markers live in html, so they cannot ride the svg transform and have to
  // be mapped through the same affine pair by hand.
  marker(index, x, y) {
    const factors = this.visible(index) ? this.factors() : null;

    return `left: ${x}%; top: ${factors ? factors.offset + factors.factor * y : y}%`;
  },
  pick(index, event) {
    this.active = index;

    this.locate(event);
  },
  /**
   * Only a mouse leaving means the pointer is gone. On touch, pointerleave
   * fires right after the tap, and closing on it would blank the tooltip the
   * instant it appeared. Touch closes by tapping outside instead.
   */
  release(event) {
    if (!event || event.pointerType === 'mouse') {
      this.clear();
    }
  },
  sector(from, to) {
    const { center, radius, inner } = this.geometry;
    const point = (angle, distance) =>
      `${this.round(center + Math.cos(angle) * distance)},${this.round(center + Math.sin(angle) * distance)}`;

    // A single arc command cannot express a full turn, because its start and
    // end points would coincide, so it is split in half.
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
      // Never let the last visible entry be switched off: an empty plot has no
      // scale to rescale to, and a pie would have no circle left to divide.
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

    // Measuring here rather than in init() is what keeps this working inside
    // a lazy Livewire component, where the element has no size until it is
    // swapped in for its placeholder.
    if (!rect.width) {
      return;
    }

    const ratio = (event.clientX - rect.left) / rect.width;

    // A bar owns a slot, so the pointer falls inside one. A curve has points
    // on the edges, so the pointer snaps to the nearest of them.
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
