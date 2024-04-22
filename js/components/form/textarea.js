export default (color) => ({
  init() {
    setTimeout(() => this.resize(), 100);
  },
  counter() {
    const max = this.$refs.textarea.maxLength;
    const length = this.$refs.textarea.value.length;

    if (length === 0) {
        return this.$refs.counter.innerText = '';
    }

    const colors = color.split(' ');

    if (length >= max) {
      colors.forEach((color) => this.$refs.counter.classList.add(color));
    }

    if (length === (max - 1)) {
      colors.forEach((color) => {
        if (!this.$refs.counter.classList.contains(color)) return;

        this.$refs.counter.classList.remove(color)
      });
    }

    if (max !== undefined && max !== -1) {
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
