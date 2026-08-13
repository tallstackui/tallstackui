import { error as alert, wireChange } from '../../../../js/helpers';

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
  smart = null
) => ({
  model: model,
  id: id,
  length: length,
  clear: clear,
  observer: null,
  observing: false,
  error: false,
  numbers: numbers,
  letters: letters,
  livewire: livewire,
  property: property,
  value: value,
  smart: smart,
  submitted: false,
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
    if (index + 1 > this.length) {
      this.focus(1);
      return;
    }

    this.focus(index + 1);
  },
  /**
   * Handle the typing. We rely on the input event instead of keyup because
   * keyup only fires when the key is released, which happens after the next
   * keydown when typing fast, making the characters be discarded.
   *
   * @param {Number} index
   * @return {void}
   */
  type(index) {
    const element = this.input(index);

    if (!element) {
      return;
    }

    const characters = element.value.split('').filter((character) => !this.invalidate(character));

    if (characters.length === 0) {
      element.value = '';

      this.syncModel();

      return;
    }

    // This code aims to ensure that typing always occurs starting with the
    // first input, so that the person cannot start typing from the last input.
    const gap = this.gap(index);

    if (gap !== index) {
      element.value = '';

      this.input(gap).value = characters[0];

      this.focus(Math.min(gap + 1, this.length));
      this.syncModel();

      return;
    }

    // More than one character means the person typed faster than the focus
    // was able to move, so we spread the surplus over the next inputs.
    let cursor = index;

    for (const character of characters) {
      const input = this.input(cursor);

      if (!input) {
        break;
      }

      input.value = character;

      cursor++;
    }

    this.focus(Math.min(cursor, this.length));
    this.syncModel();
  },
  /**
   * Get the first empty input placed before the given index.
   *
   * @param {Number} index
   * @return {Number}
   */
  gap(index) {
    for (let previous = 1; previous < index; previous++) {
      if (this.input(previous)?.value === '') {
        return previous;
      }
    }

    return index;
  },
  /**
   * Handle the backspace key. Just like the typing, this runs on keydown
   * because keyup arrives too late when the key is pressed repeatedly.
   *
   * @param {KeyboardEvent} event
   * @param {Number} index
   * @return {void}
   */
  backspace(event, index) {
    event.preventDefault();

    // An empty input means the deletion must happen at the previous one.
    const target = this.input(index)?.value !== '' ? index : index - 1;

    if (target < 1) {
      return;
    }

    // The characters at the right are shifted to the left to avoid gaps
    // between the filled inputs, which would break the model order.
    for (let position = target; position < this.length; position++) {
      this.input(position).value = this.input(position + 1).value;
    }

    this.input(this.length).value = '';

    this.focus(target);
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

    if (this.model && this.model.length === this.length) {
      this.$refs.wrapper.dispatchEvent(
        new CustomEvent('filled', { detail: { model: this.model } })
      );

      if (this.smart && !this.submitted) {
        this.submitted = true;

        this.$nextTick(() => {
          const form = this.$refs.wrapper.closest('form');

          if (!form) {
            return;
          }

          if (typeof form.requestSubmit === 'function') {
            form.requestSubmit();
          } else {
            form.submit();
          }
        });
      }
    } else {
      this.submitted = false;
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

    const data = event.clipboardData.getData('text').trim();

    if (!data) return;

    // We use basic regex to avoid paste
    // values different from the mask
    if (this.invalidate(data)) return;

    const characters = data.slice(0, this.length).split('');

    for (let index = 1; index <= this.length; index++) {
      const input = this.input(index);

      if (!input) continue;

      input.value = characters[index - 1] ?? '';
    }

    this.focus(Math.min(characters.length + 1, this.length));
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
    this.submitted = false;

    this.focus(1);

    if (!internal) {
      this.$refs.wrapper.dispatchEvent(new CustomEvent('clear', { detail: { model: model } }));
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
