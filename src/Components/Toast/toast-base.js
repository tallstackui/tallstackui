// Geometry of the collapsed pile, in pixels. VISIBLE is how many layers
// keep peeking out before the older toasts fade away behind the pile.
const OFFSET = 16;
const GAP = 12;
const VISIBLE = 3;

// Mirror of Tailwind's md breakpoint. The pile resolves its anchor in JavaScript,
// so top-on-mobile cannot be left to a media query the way the plain list can.
const DESKTOP = '(min-width: 48rem)';

export default (
  flash,
  position = null,
  flashGlobal = false,
  stacked = false,
  topOnMobile = false
) => ({
  show: false,
  toasts: [],
  position: position,
  stacked: stacked,
  topOnMobile: topOnMobile,
  desktop: true,
  expanded: false,
  heights: {},
  init() {
    if (flash) window.onload = () => this.add(flash);
    if (flash)
      document.addEventListener('livewire:navigated', () => this.add(flash), { once: true });

    if (this.stacked && this.topOnMobile) {
      const query = window.matchMedia(DESKTOP);

      this.desktop = query.matches;

      query.addEventListener('change', (event) => (this.desktop = event.matches));
    }
  },
  /**
   * Add a new toast to the list.
   *
   * @param {Event} event
   * @return {void}
   */
  add(event) {
    this.$nextTick(() => (this.show = true));

    if (flash) {
      // Since flash tends to be something to be
      // displayed later, we clear the array before
      // sending to prevent duplication.
      this.flush();

      this.toasts.push(flash);
    }

    if (event.detail) {
      if (event.detail.sole && this.toasts.length > 0) {
        this.flush();
      }

      event.detail.id ??= `${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;

      this.position = event.detail.position ?? this.position;

      this.toasts.push(event.detail);
    }
  },
  /**
   * Remove a toast from the list.
   *
   * @param {Object} toast
   * @return {void}
   */
  remove(toast) {
    this.toasts = this.toasts.filter((element) => element.id !== toast.id);

    delete this.heights[toast.id];

    if (this.toasts.length === 0) {
      this.collapse();
    }
  },
  /**
   * Drop every toast at once, along with the heights they had reported.
   *
   * @return {void}
   */
  flush() {
    this.toasts = [];
    this.heights = {};

    this.collapse();
  },
  /**
   * Store the height a toast reported, so the pile knows how far
   * apart the cards must sit once it expands.
   *
   * @param {Object} detail
   * @return {void}
   */
  register(detail) {
    this.heights[detail.id] = detail.height;
  },
  /**
   * Open the pile back into a list.
   *
   * @return {void}
   */
  expand() {
    if (!this.stacked) {
      return;
    }

    this.expanded = true;
  },
  /**
   * Close the list back into a pile.
   *
   * @return {void}
   */
  collapse() {
    if (!this.stacked) {
      return;
    }

    this.expanded = false;
  },
  /**
   * The measured height of a toast, or zero until it reports one.
   *
   * @param {Object} toast
   * @return {Number}
   */
  height(toast) {
    return this.heights[toast.id] ?? 0;
  },
  /**
   * How many toasts sit in front of the one at the given index. The most
   * recent toast is always the front of the pile, at depth zero.
   *
   * @param {Number} index
   * @return {Number}
   */
  depth(index) {
    return this.toasts.length - 1 - index;
  },
  /**
   * The height the pile occupies. The cards are absolute, so this is what
   * gives the wrapper a hover area to begin with.
   *
   * @return {Number}
   */
  get container() {
    if (!this.stacked || this.toasts.length === 0) {
      return 0;
    }

    if (this.expanded) {
      return this.toasts.reduce((total, toast) => total + this.height(toast) + GAP, 0) - GAP;
    }

    const front = this.toasts[this.toasts.length - 1];

    return this.height(front) + Math.min(this.toasts.length - 1, VISIBLE) * OFFSET;
  },
  /**
   * The opacity of a toast's content. While piled, only the front card shows
   * anything: the ones behind are reduced to their card shape, so the pile
   * reads as stacked paper instead of overlapping paragraphs.
   *
   * @param {Number} index
   * @return {Number}
   */
  content(index) {
    if (this.expanded || this.depth(index) === 0) {
      return 1;
    }

    return 0;
  },
  /**
   * The scale of a toast, shrinking the deeper it sits in the pile.
   *
   * @param {Number} index
   * @return {Number}
   */
  scale(index) {
    if (this.expanded) {
      return 1;
    }

    return 1 - Math.min(this.depth(index), VISIBLE) * 0.05;
  },
  /**
   * Whether the pile grows upward, away from the bottom edge. Follows the
   * position, unless top-on-mobile has pinned it to the top on a narrow screen.
   *
   * @return {Boolean}
   */
  get reversed() {
    if (this.topOnMobile && !this.desktop) {
      return false;
    }

    return this.position.includes('bottom-');
  },
  /**
   * The style of a toast in the pile. Every card is anchored to the edge the
   * position points at and pushed away from it by whatever stands in front:
   * a fixed offset while piled, the real heights once expanded.
   *
   * @param {Number} index
   * @return {Object}
   */
  style(index) {
    const reversed = this.reversed;
    const anchor = reversed ? 'bottom' : 'top';

    const distance = this.expanded
      ? this.toasts.slice(index + 1).reduce((total, toast) => total + this.height(toast) + GAP, 0)
      : Math.min(this.depth(index), VISIBLE) * OFFSET;

    const visible = this.expanded || this.depth(index) < VISIBLE;

    // Both edges are always written, since a toast sent to the opposite
    // position flips the anchor and the previous one has to be undone.
    //
    // visibility rides along with opacity to take the buried cards out of
    // hit-testing and out of the accessibility tree, which opacity alone does
    // not do. It does not cut the fade: CSS Transitions maps every step
    // between the endpoints to visible, so the flip lands at the very end.
    return {
      top: reversed ? 'auto' : '0px',
      bottom: reversed ? '0px' : 'auto',
      zIndex: index,
      opacity: visible ? 1 : 0,
      visibility: visible ? 'visible' : 'hidden',
      transformOrigin: anchor,
      transform: `translateY(${reversed ? -distance : distance}px) scale(${this.scale(index)})`,
    };
  },
  /**
   * The horizontal offset used when the toast enters.
   *
   * @returns {String}
   */
  translation() {
    if (this.position.includes('-center')) {
      return 'sm:translate-x-0';
    }

    return this.position.includes('-left') ? 'sm:-translate-x-2' : 'sm:translate-x-2';
  },
  /**
   * Toast transitions. Piled toasts only fade in: the sliding is already
   * handled by the transform that places them in the pile.
   *
   * @returns {Object}
   */
  get transition() {
    if (flashGlobal) {
      return {};
    }

    if (this.stacked) {
      return {
        'x-transition:enter': 'transition ease-out duration-300',
        'x-transition:enter-start': 'opacity-0',
        'x-transition:enter-end': 'opacity-100',
      };
    }

    return {
      'x-transition:enter': 'transform ease-out duration-300 transition',
      'x-transition:enter-start'() {
        const vertical = this.topOnMobile ? '-translate-y-2' : 'translate-y-2';

        return `${vertical} opacity-0 sm:translate-y-0 ${this.translation()}`;
      },
      'x-transition:enter-end': 'translate-y-0 opacity-100 sm:translate-x-0',
    };
  },
});
