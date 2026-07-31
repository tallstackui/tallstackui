export default (model, selectable) => ({
  // Outside the Livewire context there is nothing to entangle.
  model: model ?? [],
  rows: [],
  _observer: null,
  init() {
    if (!selectable) {
      return;
    }

    this.checked();

    // Watching covers the individual rows, "select all" and any external
    // change to the entangled property through a single path.
    this.$watch('model', () => this.$dispatch('selected', { rows: this.model }));

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
   * Navigate through the query string. Used by the filter when the
   * table renders outside the Livewire context.
   *
   * @param {Object} params
   * @param {String|null} anchor
   * @returns {void}
   */
  navigate(params, anchor = null) {
    const url = new URL(window.location.href);

    Object.entries(params).forEach(([key, value]) => {
      if (value === null || value === undefined || value === '') {
        url.searchParams.delete(key);

        return;
      }

      url.searchParams.set(key, value);
    });

    // A new filter always sends the results back to the first page.
    url.searchParams.delete('page');

    url.hash = anchor ?? '';

    window.location.assign(url);
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
