export default (model, decimals, precision, locale) => {
  return ({
    model: model,
    input: '',
    locale: locale,
    decimals: decimals,
    precision: precision,
    init() {
      if (this.model) {
        this.input = this.model;

        this.$nextTick(() => this.format());
      }

      this.$watch('input', (value) => {
        this.format(value);

        this.model = this.input;
      });
    },
    /**
     * Format the input value based on locale.
     *
     * @returns {void}
     */
    format(value = null) {
      let current = '';

      if (value) {
        current = value.replace(/[^\d]/g, '');
      } else {
        current = this.input;
      }

      if (current === '') {
        return;
      }

      current = parseFloat(current) / 100;

      this.input = new Intl.NumberFormat(this.locale, {
        minimumFractionDigits: this.decimals,
        maximumFractionDigits: this.precision
      }).format(current);
    },
  });
};
