export default (property) => ({
  bound: null,
  init() {
    this.bound = this.handle.bind(this);

    this.$refs[property].addEventListener('input', this.bound);
  },
  /**
   * Clean up event listeners when component is destroyed
   *
   * @returns {void}
   */
  destroy() {
    if (this.$refs[property] && this.bound) {
      this.$refs[property].removeEventListener('input', this.bound);
    }
  },
  /**
   * Handle input events and process the value
   *
   * @param {Event} event
   * @returns {void}
   */
  handle(event) {
    if (event._stripping) {
      return;
    }

    const input = event.target;
    const { value, type: type } = input;

    if (!this.process(value)) {
      return;
    }

    const stripped = value.replace(/^0+/, '') || '0';

    if (type === 'number' && input.value !== stripped) {
      this.value(input, stripped);

      return;
    }

    this.update(input, value, stripped);
  },
  /**
   * Determine if the value should be processed
   *
   * @param {String} value
   * @returns {Boolean}
   */
  process(value) {
    if (!value || typeof value !== 'string') return false;

    const zeros = value.startsWith('0') && value.length > 1;
    const decimal = value.startsWith('0.') || value.startsWith('0,');

    return zeros && !decimal;
  },
  /**
   * Update text input with stripped value and maintain cursor position
   *
   * @param {HTMLInputElement} input
   * @param {String} original
   * @param {String} stripped
   */
  update(input, original, stripped) {
    if (original !== stripped && (this.numeric(original) || this.format(original))) {
      const cursorPosition = input.selectionStart;
      const removedZeros = original.length - stripped.length;

      this.value(input, stripped);

      this.position(input, cursorPosition - removedZeros);
    }
  },
  /**
   * Check if a string is numeric
   *
   * @param {String} value
   * @returns {Boolean}
   */
  numeric(value) {
    return /^-?\d+$/.test(value);
  },
  /**
   * Check if a string has a valid number format (including decimals)
   *
   * @param {String} value
   * @returns {Boolean}
   */
  format(value) {
    return /^-?\d+[.,]\d*$/.test(value);
  },
  /**
   * Set the value of an input and trigger an input event
   *
   * @param {HTMLInputElement} input - The input element
   * @param {String} value - The new value
   */
  value(input, value) {
    // Prevent potential infinite loops by checking if value is actually different
    if (input.value === value) {
      return;
    }

    input.value = value;

    const inputEvent = new InputEvent('input', {
      bubbles: true,
      cancelable: true,
      composed: true,
    });

    inputEvent._stripping = true;

    input.dispatchEvent(inputEvent);
  },

  /**
   * Set the cursor position in an input
   *
   * @param {HTMLInputElement} input
   * @param {number} current
   */
  position(input, current) {
    const position = Math.max(0, current);

    if (document.activeElement !== input) {
      return;
    }

    input.setSelectionRange(position, position);
  },
});
