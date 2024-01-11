export default (rules) => ({
  rules: false,
  input: '',
  min: rules.min ?? null,
  symbols: rules.symbols ?? null,
  numbers: rules.numbers ?? null,
  mixed: rules.mixed ?? null,
  results: {
    min: false,
    symbols: false,
    numbers: false,
    mixed: false,
  },
  init() {
    this.$watch('input', (value) => {
      if (!value) {
        this.reset();

        return;
      }

      this.check(value);
    });
  },
  /**
   * @returns {void}
   */
  reset() {
    this.results = {min: false, symbols: false, numbers: false, mixed: false};
  },
  /**
   * @param value {String}
   */
  check(value) {
    this.results.min = this.min && value.length >= this.min;

    if (this.symbols) {
      this.results.symbols = (new RegExp(`[${this.symbols.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&')}]`)).test(value);
    }

    this.results.numbers = this.numbers &&value.match(/\d/) !== null;

    this.results.mixed = this.mixed && value.match(/[a-z]/) && value.match(/[A-Z]/);
  },
});
