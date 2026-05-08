import {
  overflow,
  unique,
  register_ui_element,
  unregister_ui_element,
  top_ui_element,
} from '../../../js/helpers';

export default (images, cover = 1, autoplay, interval, withoutLoop, shuffle, clickable, navigable) => ({
  id: unique(),
  images: images,
  time: interval,
  current: cover,
  interval: null,
  paused: false,
  expanded: null,
  expandedIndex: null,
  init() {
    if (shuffle) this.shuffle();
    if (autoplay) this.play();

    if (!clickable) return;

    this.$watch('expanded', (value, previous) => {
      const opening = previous === null && value !== null;
      const closing = previous !== null && value === null;

      if (!opening && !closing) return;

      overflow(opening, 'carousel');

      opening ? register_ui_element(this.id, 'carousel') : unregister_ui_element(this.id);

      this.$refs.carousel.dispatchEvent(
        new CustomEvent(opening ? 'expand' : 'collapse', {
          detail: { image: value },
        })
      );
    });
  },
  /** @return {Boolean} Whether this carousel is the topmost UI element. */
  get top_ui() {
    return top_ui_element(this.id);
  },
  /**
   * Whether the lightbox can step backward from the current expanded image.
   *
   * @return {Boolean}
   */
  get expandedHasPrevious() {
    if (!navigable || this.expandedIndex === null) return false;

    return !withoutLoop || this.expandedIndex > 1;
  },
  /**
   * Whether the lightbox can step forward from the current expanded image.
   *
   * @return {Boolean}
   */
  get expandedHasNext() {
    if (!navigable || this.expandedIndex === null) return false;

    return !withoutLoop || this.expandedIndex < this.images.length;
  },
  /**
   * Open the lightbox with the given image.
   *
   * @param {Object} image
   * @param {Number} index 1-based index of the image being expanded.
   * @returns {void}
   */
  expand(image, index) {
    if (!clickable) return;

    this.expanded = image;
    this.expandedIndex = index;
  },
  /**
   * Close the lightbox.
   *
   * @returns {void}
   */
  close() {
    if (this.expandedIndex !== null) {
      this.current = this.expandedIndex;
    }

    this.expanded = null;
    this.expandedIndex = null;
  },
  /**
   * Advance the lightbox to the next image.
   *
   * @returns {void}
   */
  expandNext() {
    if (!navigable || this.expandedIndex === null) return;

    if (withoutLoop && this.expandedIndex === this.images.length) return;

    const next = this.expandedIndex < this.images.length ? this.expandedIndex + 1 : 1;

    this.expandedIndex = next;
    this.expanded = this.images[next - 1];

    this.$refs.carousel.dispatchEvent(
      new CustomEvent('next', {
        detail: { current: next, image: this.expanded },
      })
    );
  },
  /**
   * Step the lightbox to the previous image.
   *
   * @returns {void}
   */
  expandPrevious() {
    if (!navigable || this.expandedIndex === null) return;

    if (withoutLoop && this.expandedIndex === 1) return;

    const previous = this.expandedIndex > 1 ? this.expandedIndex - 1 : this.images.length;

    this.expandedIndex = previous;
    this.expanded = this.images[previous - 1];

    this.$refs.carousel.dispatchEvent(
      new CustomEvent('previous', {
        detail: { current: previous, image: this.expanded },
      })
    );
  },
  /**
   * Shuffle the carousel images.
   *
   * @returns {void}
   */
  shuffle() {
    for (let i = this.images.length - 1; i > 0; i--) {
      const number = Math.floor(Math.random() * (i + 1));

      [this.images[i], this.images[number]] = [this.images[number], this.images[i]];
    }
  },
  /**
   * Start the carousel automation.
   *
   * @returns {void}
   */
  play() {
    this.interval = setInterval(() => {
      if (!this.paused) {
        this.next();
      }
    }, this.time);
  },
  /**
   * Reset the carousel automation.
   *
   * @returns {void}
   */
  reset() {
    if (!autoplay) return;

    clearInterval(this.interval);

    this.time = interval;

    this.play();
  },
  /**
   * Advance to the next carousel image.
   *
   * @returns {void}
   */
  next() {
    if (withoutLoop && this.current === this.images.length) {
      return;
    }

    window.dispatchEvent(new CustomEvent('tallstackui:floating-flush'));

    if (this.current < this.images.length) {
      this.current = this.current + 1;

      this.event('next');

      return;
    }

    this.current = 1;

    this.event('next');
  },
  /**
   * Back to the previous carousel image.
   *
   * @returns {void}
   */
  previous() {
    if (withoutLoop && this.current === 1) {
      return;
    }

    window.dispatchEvent(new CustomEvent('tallstackui:floating-flush'));

    if (this.current > 1) {
      this.current = this.current - 1;

      this.event('previous');

      return;
    }

    this.current = this.images.length;

    this.event('previous');
  },
  /**
   * Jump to a specific carousel slide.
   *
   * @param {Number} index 1-based slide index.
   * @returns {void}
   */
  seek(index) {
    if (this.current === index) return;

    window.dispatchEvent(new CustomEvent('tallstackui:floating-flush'));

    this.current = index;

    this.reset();
  },
  /**
   * Dispatch events.
   *
   * @param {String} type
   */
  event(type) {
    this.$refs.carousel.dispatchEvent(
      new CustomEvent(type, {
        detail: { current: this.current, image: this.images[this.current] },
      })
    );
  },
});
