import {error as alert, wireChange} from '../../helpers';

export default (
    model,
    id,
    length,
    clear,
    numbers,
    letters,
    livewire,
    property,
    value,
    change = null,
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
  livewire: livewire,
  property: property,
  value: value,
  init() {
    if (!this.model && this.value) {
      this.model = this.value;
    }

    if (this.model) {
      if (typeof this.model !== 'string' && typeof this.model !== 'number') {
        return alert('The [wire:model] property value must be a string or a number');
      }

      this.model = this.model.toString();
    }

    this.observation();

    this.$watch('error', async () => this.observed());

    this.$watch('model', (value) => {
      if (this.livewire) {
        // This entire approach here is necessary for situations where
        // the model is possibly receiving effects from external changes,
        // so we use this code to synchronize the changes internally.
        if (!value) {
          this.erase(true);

          return;
        }

        return this.syncInput(value);
      }

      const input = document.getElementsByName(this.property)[0];

      if (!input) {
        return;
      }

      input.value = value;
    });
  },
  /**
   * @return {void}
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
   * @return {void}
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
   * Verify if the input has errors.
   *
   * @return {void}
   */
  errors() {
    const errors = this.validate;

    this.error = Boolean(errors?.innerText === 'true');
  },
  /**
   * Focus on the input element.
   *
   * @param {Number} index
   * @return {void}
   */
  focus(index) {
    this.input(index)?.focus();
  },
  /**
   * Navigate to the left input.
   *
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
   * Navigate to the right input.
   *
   * @param {Number} index
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
   * Handle the keyup event.
   *
   * @param {Number} index
   * @return {void}
   */
  keyup(index) {
    // This code aims to ensure that typing always occurs starting with the
    // first input, so that the person cannot start typing from the last input.
    if (index !== 1) {
      for (let i = 1; i < index; i++) {
        const previous = this.input(i);

        if (previous.value === '') {
          this.focus(i);

          this.input(i).value = this.input(index).value;
          this.input(index).value = '';

          this.syncModel();

          return;
        }
      }
    }

    if (this.pasting) {
      this.pasting = false;
      this.syncModel();

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

    this.syncModel();
  },
  /**
   * Handle the backspace key.
   *
   * @param {Number} index
   * @return {void}
   */
  backspace(index) {
    const current = this.input(index);

    if (current.value !== '') {
      current.value = '';
      this.syncModel();

      return;
    }

    const input = this.input(index - 1);

    if (!input) return;

    const previous = this.input(index - 1);
    previous.value = '';
    this.focus(index - 1);

    this.syncModel();
  },
  /**
   * Sync the input elements with the model.
   *
   * @return {void}
   */
  syncModel() {
    this.model = '';

    for (let index = 1; index <= this.length; index++) {
      const input = this.input(index);

      if (!input) continue;

      this.model += input.value;
    }

    wireChange(change, this.model);

    if (this.model.length === this.length) {
      this.$refs.wrapper.dispatchEvent(new CustomEvent('filled', {detail: {model: this.model}}));
    }
  },
  /**
   * Sync the input elements with the model.
   *
   * @return {void}
   */
  syncInput(value = null) {
    // We don't need to syncModel here because this method
    // is called if the model was changed externally.
    for (let index = 0; index <= value.length; index++) {
      const input = this.input(index + 1);

      if (!input || value[index] === undefined || this.invalidate(value[index])) continue;

      input.value = value[index];
    }

    wireChange(change, this.model);
  },
  /**
   * Retrieve the input element.
   *
   * @param index
   * @return {HTMLElement|null}
   */
  input(index) {
    return document.getElementById(`pin-${id}-${index}`);
  },
  /**
   * Handle the paste event.
   *
   * @param {ClipboardEvent} event
   * @return {void}
   */
  paste(event) {
    event.preventDefault();

    const data = event.clipboardData.getData('text');

    if (!data) return;

    // We use basic regex to avoid paste
    // values different from the mask
    if (this.invalidate(data)) return;

    for (let index = 0; index <= this.length; index++) {
      const input = this.input(index+1);

      if (!input || !data[index]) continue;

      input.value += data[index];
    }

    this.syncModel();
  },
  /**
   * Erase all inputs.
   *
   * @param {Boolean} internal
   * @return {void}
   */
  erase(internal = false) {
    for (let index = 1; index <= this.length; index++) {
      const input = this.input(index);

      if (!input) continue;

      input.value = '';
    }

    const model = this.model;
    this.model = null;

    this.focus(1);

    if (!internal) {
      this.$refs.wrapper.dispatchEvent(new CustomEvent('clear', {detail: {model: model}}));
    }
  },
  /**
   * Verify if the value is invalid according to the mask.
   *
   * @return {Boolean}
   */
  invalidate(value) {
    if (this.numbers && !/^\d+$/.test(value)) return true;

    return this.letters && !/^[a-zA-Z]+$/.test(value);
  },
  /**
   * The hidden div element used to validate the inputs.
   *
   * @return {HTMLElement|null}
   */
  get validate() {
    return document.getElementById(id);
  },
});
