export default (
  model,
  decimals,
  precision,
  clearable,
  mutate,
  decimal,
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
  decimal: decimal,
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
    let value;

    if (this.decimal) {
      value = this.numeric();
    } else if (this.mutate) {
      value = this.input;
    } else {
      value = this.input.replace(/\D/g, '');
    }

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
   * Strip the locale-specific group separator and normalize the decimal
   * separator to `.` so the resulting string is directly castable to a
   * number on the server.
   *
   * @returns {string}
   */
  numeric() {
    const input = String(this.input ?? '');

    if (input === '') {
      return '';
    }

    const parts = new Intl.NumberFormat(this.locale).formatToParts(12345.6);
    const group = parts.find((part) => part.type === 'group')?.value ?? ',';
    const decimal = parts.find((part) => part.type === 'decimal')?.value ?? '.';

    return input.split(group).join('').replace(decimal, '.');
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
