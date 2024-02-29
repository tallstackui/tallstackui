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
      this.model = typeof this.value === 'string' ? [this.value] : this.value;

      this.$nextTick(() => this.input = this.model);
    }

    this.$watch('model', (value) => this.input = value);

    this.prefix();
  },
  /**
   * @param event {Event}
   */
  add(event) {
    if (event.key !== 'Enter' && event.key !== ',') return;

    if (this.limit && (this.model?.length >= this.limit)) {
      this.clean();

      return;
    }

    // We need to make sure we remove commas because adding
    // using commas doesn't work on mobile devices, just enter.
    this.tag = this.tag.replace(/,/g, '');

    let tag = this.tag.trim();

    if (!tag || (this.prefixes && tag === this.prefixes)) {
      this.clean();

      return;
    }

    tag = this.prefixes && tag[0] !== this.prefixes ? this.prefixes + tag : tag;

    if (this.model?.includes(tag)) {
      this.clean();

      return;
    }

    this.model = Array.isArray(this.model) ? [...this.model, tag] : [tag];
    this.prefix();

    this.$el.dispatchEvent(new CustomEvent('add', {detail: {tag: tag}}));

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
    if (index < 0 || !this.model || this.model.length <= index) return;

    if (event && event.target.value.trim() !== '') return;

    const removed = this.model.splice(index, 1);

    this.$el.dispatchEvent(new CustomEvent('remove', {detail: {tag: removed[0]}}));
  },
  /**
   * @returns {void}
   */
  erase() {
    this.$el.dispatchEvent(new CustomEvent('erase', {detail: {tags: this.model}}));

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
   * @param {*} value
   */
  set input(value) {
    if (this.livewire) return;

    const input = document.getElementsByName(this.property)[0];

    if (!input) return;

    value = value?.filter((value) => value !== '');

    input.value = !value || value.length === 0 ?
        '' :
        // eslint-disable-next-line max-len
        (typeof value === 'string' && value.indexOf(',') !== - 1 || typeof value === 'object' && value.length > 1 ? JSON.stringify(value) : value);
  },
});
