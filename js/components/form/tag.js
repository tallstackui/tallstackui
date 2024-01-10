export default (
    model,
    limit,
    prefixes,
    livewire,
    property,
    value,
) => ({
  model: model,
  limit: limit,
  prefixes: prefixes,
  livewire: livewire,
  property: property,
  value: value,
  tag: '',
  init() {
    if (!this.livewire) {
      this.model = this.value;
      this.$nextTick(() => this.input = this.model);
    }

    this.$watch('model', (value) => this.input = value);

    this.prefix();
  },
  /**
   * @param event {Event}
   */
  add(event) {
    if (this.limit && (this.model?.length >= this.limit)) {
      this.clean();

      return;
    }

    if (event.key !== 'Enter' && event.key !== ',') return;

    let tag = this.tag.trim();
    tag = this.prefixes && tag.indexOf(this.prefixes) === -1 ? this.prefixes + tag : tag;

    if (!tag || this.model?.includes(tag)) {
      this.clean();

      return;
    }

    this.model = Array.isArray(this.model) ? [...this.model, tag] : [tag];
    this.prefix();

    this.clean();
  },
  /**
   * @returns {void}
   */
  clean() {
    this.$nextTick(() => this.tag = '');
  },
  /**
   * @param index {Number}
   * @param event {Event}
   */
  remove(index, event = null) {
    if (index < 0 || this.model?.length <= index) return;

    // We only remove using backspace when the input is empty
    if (event && event.target.value.trim() !== '') return;

    this.model.splice(index, 1);
  },
  /**
   * @returns {void}
   */
  erase() {
    this.model = [];
  },
  /**
   * @returns {void}
   */
  prefix() {
    if (!this.prefixes || !this.model) {
      return;
    }

    this.model = this.model.map((item) => (item.indexOf(this.prefixes) === -1 ? this.prefixes + item : item));
  },
  /**
   * Set the input value when is not Livewire
   * @param data {*}
   */
  set input(data) {
    if (this.livewire) return;

    const input = document.getElementsByName(this.property)[0];

    if (!input) return;

    // Ignoring empty arrays
    data = data?.filter((value) => value !== '');

    input.value = !data || data.length === 0 ?
        '' :
        (typeof data === 'string' && data.indexOf(',') !== - 1 || typeof data === 'object' ? JSON.stringify(data) : data);
  },
});
