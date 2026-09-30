export default () => ({
  clearable: false,
  input: null,
  init() {
    // Looked up through the markup, not `$refs`: the input can be its own
    // Alpine root or carry a custom `x-ref`, and both hide it from here.
    this.input = this.$el.parentElement.querySelector(':scope > input');

    this.$nextTick(() => (this.clearable = this.input.value !== ''));

    this.input.addEventListener('input', () => (this.clearable = this.input.value !== ''));
  },
  /**
   * Clear the input value
   *
   * @returns {void}
   */
  clear() {
    this.input.value = '';

    this.clearable = false;

    this.input.dispatchEvent(new Event('input'));
  },
});
