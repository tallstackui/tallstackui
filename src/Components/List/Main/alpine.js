export default ({ items = [], chunk = null } = {}) => ({
  search: '',
  items: items,
  chunk: chunk,
  // The first slice size, also used as the increment of every `more()` call.
  step: chunk,
  /**
   * Lazy mode renders the rows from `items` through `x-for`, while the
   * default mode renders them on the server and registers them one by one.
   *
   * @return {boolean}
   */
  get lazy() {
    return this.step !== null;
  },
  /**
   * Register an item rendered by `<x-list.items>` so the parent
   * can answer search/empty-state questions reactively. Lazy mode
   * already holds every item, so there is nothing to register.
   *
   * @param {string} name
   * @param {string} caption
   * @return {void}
   */
  register(name, caption) {
    if (this.lazy) {
      return;
    }

    this.items.push({ name: name ?? '', caption: caption ?? '' });
  },
  /**
   * Decide whether a row should be visible given the current search term.
   * Matches against name and caption, case-insensitive.
   *
   * @param {string} name
   * @param {string} caption
   * @return {boolean}
   */
  match(name, caption) {
    const term = (this.search ?? '').trim().toLowerCase();

    if (term === '') {
      return true;
    }

    return (
      (name ?? '').toLowerCase().includes(term) || (caption ?? '').toLowerCase().includes(term)
    );
  },
  /**
   * @return {Array<{name: string, caption: string}>}
   */
  get results() {
    return this.items.filter(({ name, caption }) => this.match(name, caption));
  },
  /**
   * @return {Array<{name: string, caption: string}>}
   */
  get visible() {
    return this.lazy ? this.results.slice(0, this.chunk) : this.results;
  },
  /**
   * True when at least one item matches the current search.
   * Empty list (no items at all) returns false so the empty state shows.
   *
   * @return {boolean}
   */
  get hasResults() {
    return this.items.some(({ name, caption }) => this.match(name, caption));
  },
  /**
   * Keeps growing while the sentinel stays in view, which happens when the
   * rendered slice is shorter than the scroll container and `x-intersect`
   * would never fire again.
   *
   * @return {Promise<void>}
   */
  async more() {
    const total = this.results.length;

    if (!this.lazy || this.chunk >= total) {
      return;
    }

    this.chunk = Math.min(this.chunk + this.step, total);

    await this.$nextTick();
    await new Promise((resolve) => requestAnimationFrame(resolve));

    if (this.chunk < total && !this.overflowing()) {
      this.more();
    }
  },
  /**
   * Overflow is what pushes the sentinel out of view and hands the next slice
   * back to `x-intersect`. Until then nothing can scroll, so nothing would ever
   * trigger `more()` again and the list would stall half-filled.
   *
   * @return {boolean}
   */
  overflowing() {
    const scroll = this.$refs.scroll;

    return !scroll || scroll.scrollHeight > scroll.clientHeight;
  },
});
