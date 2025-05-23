export default (model, property, locale) => {
  return ({
    model: model,
    input: '',
    locale: locale,
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
     * Format the input value based on locale
     *
     * @returns {void}
     */
    format(value = null) {
      let current = '';

      if (value) {
        current = value.replace(/[^\d]/g, '');
      } else {
        // current = this.$refs[property].value.replace(/[^\d]/g, '');
        current = this.input;
      }

      if (current === '') return;

      current = parseFloat(current) / 100; // Convert to currency

      this.input = new Intl.NumberFormat(this.locale, {
        // TODO it should be options (like: decimal, position, etc)
        minimumFractionDigits: 2,
        maximumFractionDigits: 4
      }).format(current);
    },
  });
};
