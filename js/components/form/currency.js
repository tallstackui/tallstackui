export default (locale) => ({
  locale: locale,
  init() {
    this.$refs.input.addEventListener('input', () => {
      this.format();
    });
  },
  /**
   * Format the input value based on locale
   *
   * @returns {void}
   */
  format() {
    let value = this.$refs.input.value.replace(/[^\d]/g, '');
    if (value === '') return;

    value = parseFloat(value) / 100; // Convert to currency

    this.$refs.input.value = new Intl.NumberFormat(this.locale, {
      minimumFractionDigits: 2,
      maximumFractionDigits: 4
    }).format(value);
  },
});