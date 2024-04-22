export default () => ({
  init() {
    setTimeout(() => {
      this.resize();
    }, 100);
  },
  counter() {
    const max = this.$refs.textarea.maxLength;
    const length = this.$refs.textarea.value.length;

    if (max !== undefined && max !== -1) {
      this.$refs.counter.innerText = `${length}/${max}`;
      return;
    }

    this.$refs.counter.innerText = length;
  },
  resize() {
    if (!this.$el.value) {
      return;
    }

    this.$el.style.height = '0px';
    this.$el.style.height = this.$el.scrollHeight + 'px';
  },
});
