export default () => ({
  init() {
    setTimeout(() => {
      this.resize();
    }, 100);
  },
  resize() {
    if (!this.$el.value) {
      return;
    }

    this.$el.style.height = '0px';
    this.$el.style.height = this.$el.scrollHeight + 'px';
  },
});
