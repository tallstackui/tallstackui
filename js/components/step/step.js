export default () => ({
  currentStep: 1,
  total: 0,
  init() {
    this.$nextTick(() => {
      this.total = this.$refs.content.children.length;
    });

    this.$watch('currentStep', () => {
      this.$el.dispatchEvent(new CustomEvent('change', {detail: {step: this.currentStep}}));
    });
  },
  finish() {
    this.$el.dispatchEvent(new CustomEvent('finish', {detail: {step: this.currentStep}}));
  },
});
