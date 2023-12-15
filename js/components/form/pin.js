export default (model, prefix = null, prefixed, id, length, clear, error = false) => ({
  model: model,
  prefix: prefix,
  prefixed: prefixed,
  id: id,
  length: length,
  clear: clear,
  pasting: false,
  observer: null,
  observing: false,
  error: error,
  init() {
    this.observation();

    if (this.prefixed && this.model?.startsWith(this.prefix)) {
      this.model = this.model.replace(this.prefix, '');
    }

    this.$watch('error', async () => this.observed());
  },
  /**
   * @returns {void}
   */
  observation() {
    this.errors();

    if (!this.$refs.errors) {
      return;
    }

    this.observer = new MutationObserver(this.errors.bind(this));

    this.observer.observe(this.$refs.errors, {
      subtree: true,
      characterData: true,
    });
  },
  /**
   * @returns {Promise<void>}
   */
  async observed() {
    if (this.observer && !this.observing) {
      this.observer.disconnect();

      this.observing = true;
    }

    await this.$nextTick();

    this.observing = false;

    this.observation();
  },
  /**
   * @returns {void}
   */
  errors() {
    if (!this.$refs.errors) {
      return;
    }

    this.error = Boolean(this.$refs.errors.innerText === 'true');
  },
  /**
     * @param index {Number}
     * @returns {void}
     */
  focus(index) {
    this.input(index)?.focus();
  },
  /**
     * @param index {Number}
     * @return {void}
     */
  left(index) {
    if (index === 1) {
      this.focus(this.length);
      return;
    }

    this.focus(index - 1);
  },
  /**
     * @param index {Number}
     * @return {void}
     */
  right(index) {
    if ((index + 1) > this.length) {
      this.focus(1);
      return;
    }

    this.focus(index + 1);
  },
  /**
     * @param index {Number}
     * @return {void}
     */
  keyup(index) {
    if (this.pasting) {
      this.pasting = false;
      this.sync();

      return;
    }

    const input = this.input(index);

    if (input.value && index !== this.length) {
      if (this.input(index + 1)?.value !== '') {
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
  backspace(index) {
    const current = this.input(index);

    if (current.value !== '') {
      current.value = '';
      this.sync();

      return;
    }

    const input = this.input(index - 1);

    if (!input) return;

    const previous = this.input(index - 1);
    previous.value = '';
    this.focus(index - 1);

    this.sync();
  },
  /**
     * @returns {void}
     */
  sync() {
    this.model = '';

    for (let index = 1; index <= this.length; index++) {
      const input = this.input(index);

      if (!input) continue;

      this.model += input.value;
    }
  },
  /**
     * @param index
     * @returns {HTMLElement|null}
     */
  input(index) {
    return document.getElementById(this.key(index));
  },
  /**
   * @param event {ClipboardEvent}
   * @returns {void}
   */
  paste(event) {
    event.preventDefault();

    const data = event.clipboardData.getData('text');

    if (!data) return;

    for (let index = 0; index <= this.length; index++) {
      const input = this.input(index+1);

      if (!input) continue;

      input.value += data[index];
    }

    this.sync();
  },
  /**
   * @returns {void}
   */
  erase() {
    for (let index = 1; index <= this.length; index++) {
      const input = this.input(index);

      if (!input) continue;

      input.value = '';
    }

    this.model = null;
  },
  /**
     * @param index
     * @return {string}
     */
  key(index) {
    return `pin-${id}-${index}`;
  },
});
