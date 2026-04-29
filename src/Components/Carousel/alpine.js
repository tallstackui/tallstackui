import {
  overflow,
  unique,
  register_ui_element,
  unregister_ui_element,
  top_ui_element,
} from '../../../js/helpers';

export default (images, cover = 1, autoplay, interval, withoutLoop, shuffle, clickable) => ({
  id: unique(),
  images: images,
  time: interval,
  current: cover,
  interval: null,
  paused: false,
  expanded: null,
  init() {
    if (shuffle) this.shuffle();
    if (autoplay) this.play();

    if (!clickable) return;

    this.$watch('expanded', (value) => {
      overflow(value !== null, 'carousel');

      value !== null
        ? register_ui_element(this.id, 'carousel')
        : unregister_ui_element(this.id);

      this.$refs.carousel.dispatchEvent(
        new CustomEvent(value !== null ? 'expand' : 'collapse', {
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
   * Open the lightbox with the given image.
   *
   * @param {Object} image
   * @returns {void}
   */
  expand(image) {
    if (!clickable) return;

    this.expanded = image;
  },
  /**
   * Close the lightbox.
   *
   * @returns {void}
   */
  close() {
    this.expanded = null;
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

    if (this.current > 1) {
      this.current = this.current - 1;

      this.event('previous');

      return;
    }

    this.current = this.images.length;

    this.event('previous');
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
