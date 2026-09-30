export default (model, id, limit, addable, deleteMethod) => ({
  model: model,
  rows: [],
  component: null,
  init() {
    this.component = Livewire.find(id).__instance;

    this.rows = this.hydrate(this.model);

    // The rows are a copy of the model, so a value set outside the component,
    // as the server does on a reset, has to be brought in.
    this.$watch('model', (value) => {
      if (JSON.stringify(this.plain(value ?? [])) === JSON.stringify(this.plain(this.rows))) {
        return;
      }

      this.rows = this.hydrate(value);
    });

    // Mirrored on every change, so an edit reaches the model without Enter.
    this.$watch('rows', () => this.sync());
  },
  /**
   * Build the rows out of the model. The `index` only keys the row for
   * Alpine and never leaves the component.
   *
   * @param {Array|Null} rows
   * @returns {Array}
   */
  hydrate(rows) {
    return (rows ?? []).map((row) => ({
      index: Math.random().toString(36).substring(2, 12),
      key: row.key,
      value: row.value,
    }));
  },
  /**
   * The rows as the model holds them.
   *
   * @param {Array} rows
   * @returns {Array}
   */
  plain(rows) {
    return rows.map((row) => ({ key: row.key, value: row.value }));
  },
  /**
   * Adds a new item to the row's array.
   *
   * @returns {void}
   */
  add() {
    if (limit && this.rows.length >= limit) {
      return;
    }

    this.rows.push({
      index: Math.random().toString(36).substring(2, 12),
      key: '',
      value: '',
    });

    this.$el.dispatchEvent(
      new CustomEvent('add', {
        detail: {
          rows: this.plain(this.rows),
        },
      })
    );

    this.sync();
  },
  /**
   * Removes an item from the row's array.
   *
   * @param {Number} index
   * @return {void}
   */
  remove(index) {
    const rows = this.plain(this.rows);

    this.rows = this.rows.filter((_, i) => i !== index);

    this.$el.dispatchEvent(
      new CustomEvent('remove', {
        detail: {
          rows: this.plain(this.rows),
        },
      })
    );

    if (this.component && deleteMethod) {
      this.component.$wire.call(deleteMethod, index, rows);
    }

    this.sync();
  },
  /**
   * Sync the model with the rows.
   *
   * @returns {void}
   */
  sync() {
    this.model = this.plain(this.rows);
  },
  /**
   * Check if the new rows can be added.
   *
   * @returns {boolean}
   */
  get addable() {
    const value = Number(limit);

    return (
      (limit === null && Boolean(addable) === false) ||
      (limit && this.model.length < value && this.rows.length < value)
    );
  },
});
