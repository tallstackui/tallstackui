import {
  overflow,
  unique,
  register_ui_element,
  unregister_ui_element,
  top_ui_element,
} from '../../../js/helpers';

export default (images, navigable, withoutLoop) => ({
  id: unique(),
  images: images,
  expanded: null,
  expandedIndex: null,
  init() {
    this.$watch('expanded', (value, previous) => {
      const opening = previous === null && value !== null;
      const closing = previous !== null && value === null;

      if (!opening && !closing) {
        return;
      }

      overflow(opening, 'gallery');

      opening ? register_ui_element(this.id, 'gallery') : unregister_ui_element(this.id);

      this.$refs.gallery.dispatchEvent(
        new CustomEvent(opening ? 'expand' : 'collapse', {
          detail: { image: value },
        })
      );
    });
  },
  /**
   * Drop this gallery from the registry when it is torn down (e.g. removed by
   * Livewire/wire:navigate while the lightbox is open) and restore the body
   * scroll-lock if no other overlay remains, preventing an orphaned scroll-lock.
   *
   * @return {void}
   */
  destroy() {
    unregister_ui_element(this.id);

    if (window.__tsui_elements.length === 0) {
      overflow(false, 'gallery');
    }
  },
  /** @return {Boolean} Whether this gallery is the topmost UI element. */
  get top_ui() {
    return top_ui_element(this.id);
  },
  /**
   * Whether the lightbox can step backward from the current expanded image.
   *
   * @return {Boolean}
   */
  get expandedHasPrevious() {
    if (!navigable || this.expandedIndex === null) {
      return false;
    }

    return !withoutLoop || this.expandedIndex > 1;
  },
  /**
   * Whether the lightbox can step forward from the current expanded image.
   *
   * @return {Boolean}
   */
  get expandedHasNext() {
    if (!navigable || this.expandedIndex === null) {
      return false;
    }

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
    this.expanded = image;
    this.expandedIndex = index;
  },
  /**
   * Close the lightbox.
   *
   * @returns {void}
   */
  close() {
    this.expanded = null;
    this.expandedIndex = null;
  },
  /**
   * Advance the lightbox to the next image.
   *
   * @returns {void}
   */
  expandNext() {
    if (!navigable || this.expandedIndex === null) {
      return;
    }

    if (withoutLoop && this.expandedIndex === this.images.length) {
      return;
    }

    const next = this.expandedIndex < this.images.length ? this.expandedIndex + 1 : 1;

    this.expandedIndex = next;
    this.expanded = this.images[next - 1];

    this.$refs.gallery.dispatchEvent(
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
    if (!navigable || this.expandedIndex === null) {
      return;
    }

    if (withoutLoop && this.expandedIndex === 1) {
      return;
    }

    const previous = this.expandedIndex > 1 ? this.expandedIndex - 1 : this.images.length;

    this.expandedIndex = previous;
    this.expanded = this.images[previous - 1];

    this.$refs.gallery.dispatchEvent(
      new CustomEvent('previous', {
        detail: { current: previous, image: this.expanded },
      })
    );
  },
});
