export default (
  model,
  decimals,
  precision,
  clearable,
  mutate,
  livewire,
  property,
  value,
  locale
) => ({
  model: model,
  input: '',
  decimals: decimals,
  precision: precision,
  clearable: clearable,
  mutate: mutate,
  livewire: livewire,
  property: property,
  value: value,
  locale: locale,
  init() {
    if (!this.livewire) this.model = this.value;

    if (this.model) {
      this.input = this.model;

      this.$nextTick(() => this.format());
    }

    this.$watch('model', (value) => {
      if (value === null || value === undefined || value === '') {
        this.clear();

        return;
      }

      this.format(value);

      this.sync();
    });

    this.$watch('input', (value) => this.format(value));
  },
  /**
   * Format the input value.
   *
   * @returns {void}
   */
  format(value = null) {
    let current = value ?? this.input;

    if (!current && current !== 0) return;

    let number;

    if (typeof current === 'number') {
      number = current;
    } else {
      const digits = String(current).replace(/\D/g, '');

      number = parseFloat(digits) / Math.pow(10, this.decimals);
    }

    if (isNaN(number)) {
      this.input = '';

      return;
    }

    this.input = new Intl.NumberFormat(this.locale, {
      minimumFractionDigits: this.decimals,
      maximumFractionDigits: Math.max(this.decimals, this.precision),
    }).format(number);
  },
  /**
   * Sync the input value with the model.
   *
   * @returns {void}
   */
  sync() {
    const value = this.mutate ? this.input : this.input.replace(/\D/g, '');

    if (this.livewire) {
      this.$nextTick(() => (this.model = value));

      return;
    }

    const input = document.getElementsByName(this.property)[0];

    if (!input) {
      return;
    }

    input.value = value;
  },
  /**
   * Clear the input.
   *
   * @returns {void}
   */
  clear() {
    this.input = '';

    this.model = null;
  },
});
