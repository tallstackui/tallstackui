export default (multiple = false) => ({
  items: [],
  multiple: multiple,
  /**
   * Toggle an accordion item by id.
   * In single-open mode, opening one closes the others.
   *
   * @param {string} id
   * @return {void}
   */
  toggle(id) {
    const item = this.items.find((i) => i.id === id);

    if (!item) return;

    window.dispatchEvent(new CustomEvent('tallstackui:floating-flush'));

    if (!this.multiple && !item.open) {
      this.items.forEach((i) => {
        if (i.id !== id) i.open = false;
      });
    }

    item.open = !item.open;

    this.$refs.wrapper.dispatchEvent(
      new CustomEvent(item.open ? 'open' : 'close', { detail: { id } })
    );
  },
  /**
   * Check if an item is currently open.
   *
   * @param {string} id
   * @return {boolean}
   */
  isOpen(id) {
    return this.items.find((i) => i.id === id)?.open ?? false;
  },
  /**
   * Open every item.
   *
   * @return {void}
   */
  openAll() {
    this.items.forEach((i) => (i.open = true));
  },
  /**
   * Close every item.
   *
   * @return {void}
   */
  closeAll() {
    window.dispatchEvent(new CustomEvent('tallstackui:floating-flush'));

    this.items.forEach((i) => (i.open = false));
  },
});
