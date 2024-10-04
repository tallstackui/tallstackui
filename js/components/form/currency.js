export default (locale) => ({
  clearable: false,
  locale: locale,
  init() {
    this.$nextTick(() => {
      this.clearable = this.$refs.input.value !== '';
    });

    this.$refs.input.addEventListener('input', () => {
      this.clearable = this.$refs.input.value !== '';
      this.format();
    });
  },
  /**
   * Clear the input value
   *
   * @returns {void}
   */
  clear() {
    this.$refs.input.value = '';
    this.clearable = false;
    this.$refs.input.dispatchEvent(new Event('input'));
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

    this.$refs.input.value = new Intl.NumberFormat(this.locale).format(value);
  },
});