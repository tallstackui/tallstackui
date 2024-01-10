export default (model) => ({
  model: model,
  tag: '',
  init() {
    if (this.model.length > 0) {
      this.$refs.input.value = '';
    }
  },
  add(event) {
    if (event.key !== 'Enter' && event.key !== ',') return;

    const tag = this.tag.trim();

    if (!tag || this.model.includes(tag)) {
      this.clean();

      return;
    }

    this.model.push(tag);
    this.clean();
  },
  clean() {
    this.$nextTick(() => this.tag = '');
  },
  remove(index) {
    if (index < 0 || this.model.length <= index) return;

    this.model.splice(index, 1);
  },
  erase() {
    this.model = [];
  },
});
