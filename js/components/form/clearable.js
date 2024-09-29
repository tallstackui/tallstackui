export default () => ({
  clearable : false,
  init() {
    this.$nextTick(() => this.clearable = this.$refs.input.value !== '');

    this.$refs.input.addEventListener('input', () => this.clearable = this.$refs.input.value !== '');
  },
  /**
   * Clear the input value
   * @returns {void}
   */
  clear() {
    this.$refs.input.value = '';

    this.clearable = false;
  }
});
