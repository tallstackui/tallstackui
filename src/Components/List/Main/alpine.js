export default () => ({
  search: '',
  items: [],
  /**
   * Register an item rendered by `<x-list.items>` so the parent
   * can answer search/empty-state questions reactively.
   *
   * @param {string} name
   * @param {string} caption
   * @return {void}
   */
  register(name, caption) {
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
   * True when at least one registered item matches the current search.
   * Empty list (no items registered) returns false so the empty state shows.
   *
   * @return {boolean}
   */
  get hasResults() {
    if (this.items.length === 0) {
      return false;
    }

    const term = (this.search ?? '').trim().toLowerCase();

    if (term === '') {
      return true;
    }

    return this.items.some(({ name, caption }) => this.match(name, caption));
  },
});
