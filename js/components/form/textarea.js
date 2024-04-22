export default (color) => ({
  init() {
    setTimeout(() => this.resize(), 100);
  },
  counter() {
    const max = this.$refs.textarea.maxLength;
    const length = this.$refs.textarea.value.length;
    const defined = max !== undefined && max !== -1;

    const colors = color.split(' ');

    const cleanup = () => {
      colors.forEach((color) => {
        if (!this.$refs.counter.classList.contains(color)) return;

        this.$refs.counter.classList.remove(color)
      });
    }

    if (length === 0) {
        cleanup();

        return this.$refs.counter.innerText = '';
    }

    if (defined && length >= max) {
      colors.forEach((color) => this.$refs.counter.classList.add(color));
    }

    if (defined && length === (max - 1)) cleanup();

    if (defined) {
      return this.$refs.counter.innerText = `${length}/${max}`;
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
