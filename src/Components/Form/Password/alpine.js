import { error } from '../../../../js/helpers';

export default (model, rules, typingOnly, value = null, target = null) => ({
  model: model,
  show: false,
  rules: false,
  input: '',
  target: target,
  min: rules.min ?? null,
  symbols: rules.symbols ?? null,
  numbers: rules.numbers ?? null,
  mixed: rules.mixed ?? null,
  caps: false,
  results: {
    min: false,
    symbols: false,
    numbers: false,
    mixed: false,
  },
  typingOnly: typingOnly,
  init() {
    this.password = value;

    this.$watch('model', (value) => (this.input = value ?? ''));

    this.$watch('input', (value) => {
      if (!value) {
        this.reset();

        return;
      }

      this.check(value);
    });
  },
  /**
   * Toggle the visibility of the password.
   *
   * @returns {void}
   */
  toggle() {
    this.show = !this.show;

    this.$el.dispatchEvent(new CustomEvent('reveal', { detail: { status: this.show } }));
  },
  /**
   * @returns {void}
   */
  reset() {
    this.results = { min: false, symbols: false, numbers: false, mixed: false };
  },
  /**
   * Check if the password meets the requirements.
   *
   * @param value {String}
   */
  check(value) {
    this.results.min = this.min && value.length >= this.min;

    if (this.symbols) {
      // prettier-ignore
      /* eslint-disable-next-line no-useless-escape */
      this.results.symbols = (new RegExp(`[${this.symbols.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&')}]`)).test(value);
    }

    this.results.numbers = this.numbers && value.match(/\d/) !== null;

    this.results.mixed = this.mixed && value.match(/[a-z]/) && value.match(/[A-Z]/);
  },
  /**
   * Generate a random password based on the rules.
   *
   * @returns {void}
   */
  generator() {
    this.$refs.generator.classList.add('animate-spin');

    let password = '';

    const lower = 'abcdefghijklmnopqrstuvwxyz';
    const upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const numeric = '0123456789';

    const all =
      lower +
      (this.mixed ? upper : '') +
      (this.numbers ? numeric : '') +
      (this.symbols ? this.symbols : '');

    if (typeof window.TallStackUi?.passwordGenerator === 'function') {
      password = window.TallStackUi.passwordGenerator(
        this.min,
        this.mixed,
        this.numbers,
        this.symbols
      );
    } else {
      password += lower.charAt(this.random(lower.length));

      if (this.mixed) {
        password += upper.charAt(this.random(upper.length));
      }

      if (this.numbers) {
        password += numeric.charAt(this.random(numeric.length));
      }

      if (this.symbols) {
        password += this.symbols.charAt(this.random(this.symbols.length));
      }

      // We just fill the remaining password with random characters from all selected types
      for (let i = password.length; i < this.min; i++) {
        password += all.charAt(this.random(all.length));
      }

      // We just shuffle the password to avoid predictable patterns
      password = this.shuffle(password.split('')).join('');
    }

    this.password = password;

    this.fill(password);

    this.$el.dispatchEvent(new CustomEvent('generate', { detail: { password: password } }));

    setTimeout(() => this.$refs.generator.classList.remove('animate-spin'), 250);
  },
  /**
   * Fill the target element with the generated password.
   *
   * @param {String} password
   * @returns {void}
   */
  fill(password) {
    if (!this.target) {
      return;
    }

    const element =
      document.getElementById(this.target) ?? document.querySelector(`[x-ref="${this.target}"]`);

    if (!element) {
      error(`The generator target [${this.target}] was not found.`);

      return;
    }

    const root = element.closest('[x-data^="tallstackui_formPassword"]');

    // Another password component owns its own state, so we
    // go through the setter to keep the input, the entangled
    // model and the rules checklist in sync.
    if (root) {
      Alpine.$data(root).password = password;

      return;
    }

    element.value = password;

    element.dispatchEvent(new Event('input', { bubbles: true }));
  },
  /**
   * Generate a cryptographically secure random integer in [0, max).
   *
   * @param {Number} max
   * @returns {Number}
   */
  random(max) {
    // Rejection sampling to avoid the modulo bias
    const limit = Math.floor(4294967296 / max) * max;

    const buffer = new Uint32Array(1);

    let result;

    do {
      result = window.crypto.getRandomValues(buffer)[0];
    } while (result >= limit);

    return result % max;
  },
  /**
   * Shuffle the values using the Fisher-Yates algorithm.
   *
   * @param {Array} values
   * @returns {Array}
   */
  shuffle(values) {
    for (let index = values.length - 1; index > 0; index--) {
      const position = this.random(index + 1);

      [values[index], values[position]] = [values[position], values[index]];
    }

    return values;
  },
  /**
   * Handle the paste event to insert the password.
   *
   * @param {ClipboardEvent} event
   * @returns {void}
   */
  paste(event) {
    event.preventDefault();

    const data = event.clipboardData?.getData('text') ?? null;

    if (!data || this.typingOnly) {
      return;
    }

    this.password = data;

    this.$el.dispatchEvent(new CustomEvent('paste', { detail: { password: data } }));
  },
  /**
   * Activate the capslock indicator.
   *
   * @param {Event} event
   */
  indicator(event) {
    // This was necessary to prevent the "$event.getModifierState is not a function." error.
    if (typeof event.getModifierState !== 'function') return;

    this.caps = event.getModifierState('CapsLock');
  },
  /** @param {String} value */
  set password(value) {
    this.input = this.model = value;
  },
});
