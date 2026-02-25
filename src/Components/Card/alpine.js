export default (minimize = null) => ({
  show: true,
  minimize: minimize ?? false,
  init() {
    this.$watch('minimize', (value) => {
      this.$el.dispatchEvent(new CustomEvent(value ? 'minimize' : 'maximize'));
    });

    this.$watch('show', (value) => {
      if (!value) {
        this.$el.dispatchEvent(new CustomEvent('close'));
      }
    });
  },
});
