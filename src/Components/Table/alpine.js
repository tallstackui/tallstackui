export default (model, selectable) => ({
  model: model,
  rows: [],
  _observer: null,
  init() {
    if (!selectable) {
      return;
    }

    this.checked();

    // The list of ids changes whenever Livewire re-renders the table
    // (pagination, filter, search). Re-syncing the "select all" checkbox
    // from a MutationObserver keeps it consistent without depending on
    // Alpine re-initialising the component.
    this._observer = new MutationObserver(() => this.checked());
    this._observer.observe(this.$el, { attributes: true, attributeFilter: ['data-ids'] });
  },
  destroy() {
    this._observer?.disconnect();
  },
  /**
   * Ids of the rows currently rendered. Read from the data-ids attribute
   * so it stays in sync with the server-rendered page.
   *
   * @returns {Array}
   */
  get ids() {
    try {
      return JSON.parse(this.$el.dataset.ids || '[]');
    } catch {
      return [];
    }
  },
  /**
   * Check if all rows are selected
   *
   * @returns {Boolean}
   */
  fully() {
    const ids = this.ids;

    return ids.length > 0 && ids.every((id) => this.model.includes(id));
  },
  /**
   * Mark the "main" checkbox as checked.
   */
  checked() {
    if (!this.$refs.checkbox) {
      return;
    }

    this.$nextTick(() => {
      if (this.$refs.checkbox) {
        this.$refs.checkbox.checked = this.fully();
      }
    });
  },
  /**
   * Select a row
   *
   * @param {*} content
   */
  select(content) {
    this.checked();

    this.$dispatch('select', { row: content });
  },
  /**
   * Select all rows
   *
   * @param {Boolean} checked
   * @param {Array} ids
   * @returns {void}
   */
  all(checked = true, ids) {
    return checked ? this.push(ids) : this.remove(ids);
  },
  /**
   * Push selected rows
   *
   * @param {Array} ids
   * @returns {void}
   */
  push(ids) {
    this.model.push(...ids.filter((id) => !this.model.includes(id)));
  },
  /**
   * Remove selected rows
   *
   * @param {Array} ids
   * @returns {void}
   */
  remove(ids) {
    this.model = this.model.filter((id) => !ids.includes(id));
  },
  /**
   * Redirect to a new page
   *
   * @param {String} url
   * @param {Boolean} blank
   * @returns {void}
   */
  redirect(url, blank) {
    window.open(url, blank ? '_blank' : '_self');
  },
  /**
   * Toggle the expanded state of a row
   *
   * @param {String} id
   */
  toggle(id) {
    const idx = this.rows.indexOf(id);

    if (idx > -1) {
      this.rows.splice(idx, 1);
    } else {
      this.rows.push(id);
    }
  },
  /**
   * Check if a row is expanded
   *
   * @param {String} id
   * @returns {Boolean}
   */
  expanded(id) {
    return this.rows.includes(id);
  },
});
