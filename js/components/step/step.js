export default (selected, navigate) => ({
  selected: selected,
  navigate: navigate,
  steps: [],
  finish() {
    this.$el.dispatchEvent(new CustomEvent('finish', {detail: {step: this.selected}}));
  },
  change(increment) {
    this.selected += increment ? 1 : -1;
    this.$refs.buttons.dispatchEvent(new CustomEvent('change', {detail: {step: this.selected}}));
  },
});
