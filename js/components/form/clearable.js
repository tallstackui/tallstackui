export default (property) => ({
  clearable: false,
  init() {
    this.$nextTick(() => (this.clearable = this.$refs[property].value !== ''));

    this.$refs[property].addEventListener(
      'input',
      () => (this.clearable = this.$refs[property].value !== '')
    );
  },
  /**
   * Clear the input value
   *
   * @returns {void}
   */
  clear() {
    this.$refs[property].value = '';

    this.clearable = false;

    this.$refs[property].dispatchEvent(new Event('input'));
  },
});
