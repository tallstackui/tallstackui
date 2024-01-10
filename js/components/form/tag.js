export default (
    model,
    livewire,
    property,
    value,
) => ({
  model: model,
  livewire: livewire,
  property: property,
  value: value,
  tag: '',
  init() {
    this.model = !this.livewire ? this.value : this.model;

    if (this.model?.length > 0) {
      this.$refs.input.value = '';
    }

    if (!this.livewire) {
      this.$nextTick(() => this.input = this.model);
    }

    this.$watch('model', (value) => this.input = value);
  },
  add(event) {
    if (event.key !== 'Enter' && event.key !== ',') return;

    const tag = this.tag.trim();

    if (!tag || this.model?.includes(tag)) {
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
    if (index < 0 || this.model?.length <= index) return;

    this.model.splice(index, 1);
  },
  erase() {
    this.model = [];
  },
  /**
   * Set the input value when is not Livewire
   * @param data {*}
   */
  set input(data) {
    if (this.livewire) return;

    const input = document.getElementsByName(this.property)[0];

    if (!input) return;

    data = data?.filter((value) => value !== '');

    input.value = !data || data.length === 0 ?
        '' :
        (typeof data === 'string' && data.indexOf(',') !== - 1 || typeof data === 'object' ? JSON.stringify(data) : data);
  },
});
