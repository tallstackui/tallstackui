import {error as alert} from '../../helpers';

export default (
    model,
    id,
    length,
    clear,
    numbers,
    letters,
) => ({
  model: model,
  id: id,
  length: length,
  clear: clear,
  pasting: false,
  observer: null,
  observing: false,
  error: false,
  numbers: numbers,
  letters: letters,
  init() {
    if (this.model) {
      if (typeof this.model !== 'string' && typeof this.model !== 'number') {
        return alert('The [wire:model] property value must be a string or a number');
      }

      this.model = this.model.toString();
    }

    this.observation();

    this.$watch('error', async () => this.observed());
  },
  /**
   * @returns {void}
   */
  observation() {
    this.errors();

    const errors = this.validate;

    if (errors === null || errors === undefined) return;

    this.observer = new MutationObserver(this.errors.bind(this));

    this.observer.observe(errors, {
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
    const errors = this.validate;

    this.error = Boolean(errors?.innerText === 'true');
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
    return document.getElementById(`pin-${id}-${index}`);
  },
  /**
   * @param event {ClipboardEvent}
   * @returns {void}
   */
  paste(event) {
    event.preventDefault();

    const data = event.clipboardData.getData('text');

    if (!data) return;

    // We use basic regex to avoid paste
    // values different from the mask
    if (this.numbers && !/^\d+$/.test(data)) return;
    if (this.letters && !/^[a-zA-Z]+$/.test(data)) return;

    for (let index = 0; index <= this.length; index++) {
      const input = this.input(index+1);

      if (!input || !data[index]) continue;

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

    this.focus(1);
  },
  /**
   * The hidden div element used to validate the inputs.
   *
   * @returns {HTMLElement|null}
   */
  get validate() {
    return document.getElementById(id);
  },
});
