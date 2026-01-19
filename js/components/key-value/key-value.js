export default (model, id, limit, addable, deleteMethod) => ({
  model: model,
  rows: [],
  component: null,
  init() {
    this.component = Livewire.find(id).__instance;

    if (this.model.length > 0) {
      this.$nextTick(() => {
        this.model = this.model.map((row) => ({
          index: Math.random().toString(36).substring(2, 12),
          key: row.key,
          value: row.value,
        }));

        this.rows = this.model;
      });
    }
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
          rows: this.rows,
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
    const rows = this.rows;

    this.rows = this.rows.filter((_, i) => i !== index);

    this.$el.dispatchEvent(
      new CustomEvent('remove', {
        detail: {
          rows: this.rows,
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
    this.rows = this.rows.map((row) => ({
      key: row.key,
      value: row.value,
    }));

    this.model = this.rows;
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
