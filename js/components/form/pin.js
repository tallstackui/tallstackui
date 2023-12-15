export default (model, prefix, prefixed, length, clear) => ({
  model: model,
  prefix: prefix,
  prefixed: prefixed,
  hash: Math.random().toString(36).substring(2, 15),
  length: length,
  clear: clear,
  pasting: false,
  init() {
    this.length = this.model.length;

    this.$nextTick(() => {
      if (!this.model) {
        return;
      }

      for (let index = 0; index < this.length; index++) {
        this.input(index).value = this.model[index];
      }

      this.length = this.model.length;
    });

    this.$watch('model', (value) => {
      if (!value || !this.prefixed) {
        return;
      }

      this.model = `${this.prefix}${value}`;
    });
  },
  /**
     * @param index {Number}
     * @returns {void}
     */
  focus(index) {
    this.input(index).focus();
  },
  /**
     * @param index {Number}
     * @return {void}
     */
  left(index) {
    if (index === 0) return;

    this.focus(index - 1);
  },
  /**
     * @param index {Number}
     * @return {void}
     */
  right(index) {
    if (index === this.length - 1) return;
    this.focus(index + 1);
  },
  /**
     * @param index {Number}
     * @return {void}
     */
  go(index) {
    if (this.pasting) {
      this.pasting = false;
      this.sync();

      return;
    }

    const input = this.input(index);

    if (input.value && index !== (this.length - 1)) {
      if (this.input(index + 1).value !== '') {
        this.focus(index + 1);
        return;
      }

      this.focus(index + 1);
    }

    this.sync();
  },
  /**
     * @param index {Number}
     * @returns {void}
     */
  back(index) {
    const current = this.input(index);

    if (current.value !== '') {
      current.value = '';
      return;
    }

    const previous = this.input(index - 1);
    previous.value = '';
    this.focus(index - 1);

    this.sync();
  },
  /**
     * @returns {void}
     */
  sync() {
    let code = '';

    for (let index = 0; index < this.length; index++) {
      code += this.input(index).value;
    }

    this.model = code;
  },
  /**
     * @param index
     * @returns {HTMLElement}
     */
  input(index) {
    return document.getElementById(`pin-${this.hash}-${index}`);
  },
  /**
     * @param event {ClipboardEvent}
     * @returns {void}
     */
  paste(event) {
    event.preventDefault();

    const data = event.clipboardData.getData('text');

    if (data.length !== this.length) return;

    for (let index = 0; index < this.length; index++) {
      this.input(index).value = data[index];
    }

    this.sync();
  },
  /**
     * @returns {void}
     */
  erase() {
    for (let index = 0; index < this.length; index++) {
      this.input(index).value = '';
    }

    this.sync();
  },
  /**
     * @param index
     * @return {string}
     */
  key(index) {
    return `pin-${this.hash}-${index}`;
  },
});
