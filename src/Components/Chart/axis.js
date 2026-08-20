const GAP = 8;

const SLANT = Math.SQRT1_2;

export default (options) => ({
  fit: options.fit ?? 'thin',
  step: 1,
  height: null,
  observer: null,
  mutations: null,
  init() {
    // Observed rather than measured once: inside a lazy Livewire component
    // the axis has no width until the placeholder is swapped out.
    this.observer = new ResizeObserver(() => this.measure());
    this.observer.observe(this.$el);

    // A Livewire morph replaces the labels but keeps the wrapper, so the
    // count the step was computed from goes stale.
    this.mutations = new MutationObserver(() => this.measure());
    this.mutations.observe(this.$el, { childList: true });

    document.fonts?.ready.then(() => this.measure());
  },
  destroy() {
    this.observer?.disconnect();
    this.mutations?.disconnect();
  },
  get wrapper() {
    return this.height === null ? {} : { height: `${this.height}px` };
  },
  measure() {
    const width = this.$el.clientWidth;
    const labels = [...this.$el.children];

    if (!width || !labels.length) {
      this.step = 1;

      return;
    }

    // Hidden labels keep their box (visibility, not display), so the widest
    // one is measurable whether it is shown or not.
    const widest = Math.max(...labels.map((label) => label.offsetWidth));
    const line = Math.max(...labels.map((label) => label.offsetHeight));

    // Labels centre on their own x, so neighbours only clear each other while
    // the distance between the shown ones exceeds what each one takes up.
    const footprint = {
      // Slanted, a label takes up its line height along the axis, not its length.
      rotate: line / SLANT + 2,
      thin: widest + GAP,
      stagger: widest + GAP,
    }[this.fit];

    const rows = this.fit === 'stagger' ? 2 : 1;

    this.step = Math.max(1, Math.ceil((footprint * Math.ceil(labels.length / rows)) / width));

    this.height = {
      // The slanted label hangs below its anchor by its own projected length.
      rotate: Math.ceil((widest + line) * SLANT) + 4,
      stagger: line * 2 + 6,
      thin: null,
    }[this.fit];
  },
  shown(index) {
    const slot = this.fit === 'stagger' ? Math.floor(index / 2) : index;

    return slot % this.step === 0;
  },
  style(index) {
    if (this.fit === 'rotate') {
      return { transform: 'translateX(-100%) rotate(-45deg)', transformOrigin: 'top right' };
    }

    if (this.fit === 'stagger' && index % 2 === 1) {
      return { top: '14px' };
    }

    return {};
  },
});
