export default (selected) => ({
  selected: selected,
  steps: [],
  init() {
    this.$watch('selected', () => this.$el.dispatchEvent(new CustomEvent('change', {detail: {step: this.selected}})));
  },
  finish() {
    this.$el.dispatchEvent(new CustomEvent('finish', {detail: {step: this.selected}}));
  },
});
