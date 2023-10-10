export default () => ({
  resize() {
    this.$el.style.height = '0px';
    this.$el.style.height = this.$el.scrollHeight + 'px';
  },
});
