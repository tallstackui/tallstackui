export default (model, limit, lazy, prefixes, livewire, property, value, options = [], listable = false) => ({
  model: model,
  limit: limit,
  lazy: lazy,
  prefixes: prefixes,
  livewire: livewire,
  property: property,
  value: value,
  options: Array.isArray(options) ? options : [],
  listable: listable,
  tag: '',
  show: false,
  highlighted: -1,
  init() {
    if (!this.livewire) {
      this.model = typeof this.value === 'string' ? [this.value] : this.value;

      this.$nextTick(() => (this.input = this.model));
    }

    this.$watch('model', (value) => (this.input = value));

    this.$watch('show', (value) => {
      this.highlighted = -1;

      this.$el.dispatchEvent(new CustomEvent(value ? 'open' : 'close'));
    });

    this.prefix();
  },
  /**
   * The options still worth offering: what was typed narrows them
   * down and what is already a tag drops out.
   */
  get available() {
    const tags = (this.model ?? []).map((tag) => this.strip(tag).toLowerCase());
    const term = this.strip(this.tag.trim()).toLowerCase();

    return this.options.filter(
      (option) =>
        !tags.includes(option.toLowerCase()) &&
        (term === '' || option.toLowerCase().includes(term))
    );
  },
  /**
   * Opens the list, unless the tag limit is already reached.
   */
  open() {
    if (!this.listable || (this.limit && this.model?.length >= this.limit)) {
      return;
    }

    this.show = true;
  },
  /**
   * Adds an option from the list as a tag.
   *
   * @param option {String}
   */
  pick(option) {
    this.tag = option;

    this.add(new KeyboardEvent('keydown', { key: 'Enter' }));

    this.$refs.input.focus();
  },
  /**
   * Keyboard control of the list. Returns whether the event was consumed,
   * so the typed-tag path only runs when the list did not take it.
   *
   * @param event {KeyboardEvent}
   * @returns {Boolean}
   */
  navigate(event) {
    if (!this.listable) {
      return false;
    }

    if (event.key === 'Escape' && this.show) {
      this.show = false;

      return true;
    }

    if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
      event.preventDefault();

      this.open();

      const total = this.available.length;

      if (total === 0) {
        return true;
      }

      const step = event.key === 'ArrowDown' ? 1 : -1;

      this.highlighted = (this.highlighted + step + total) % total;

      return true;
    }

    if (event.key === 'Enter' && this.show && this.highlighted >= 0) {
      event.preventDefault();

      const option = this.available[this.highlighted];

      if (option) {
        this.pick(option);
      }

      return true;
    }

    return false;
  },
  /**
   * Drops the prefix so typing "foo" still matches the "#foo" already stored.
   *
   * @param tag {String}
   * @returns {String}
   */
  strip(tag) {
    return this.prefixes && tag[0] === this.prefixes ? tag.slice(1) : tag;
  },
  /**
   * Adds a tag.
   *
   * @param event {Event}
   */
  add(event) {
    if (event.key !== 'Enter' && event.key !== ',') return;

    // Enter means "add a tag" here. Left alone it also submits the surrounding
    // form, so outside Livewire the first tag would send the page away.
    event.preventDefault();

    if (this.limit && this.model?.length >= this.limit) {
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

    const content = this.prefixes && tag[0] === this.prefixes ? tag.slice(1) : tag;

    if (this.lazy && content.length < this.lazy) {
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

    this.highlighted = -1;

    // Reaching the limit closes the list, the same way it refuses
    // to open once the limit is already reached.
    if (this.limit && this.model.length >= this.limit) {
      this.show = false;
    }

    this.$el.dispatchEvent(new CustomEvent('add', { detail: { tag: tag } }));

    this.clean();
  },
  /**
   * Clear the tags.
   *
   * @returns {void}
   */
  clean() {
    this.$nextTick(() => (this.tag = ''));
  },
  /**
   * Remove a tag.
   *
   * @param index {Number}
   * @param event {Event}
   */
  remove(index, event = null) {
    if (index < 0 || !this.model || this.model.length <= index) return;

    if (event && event.target.value.trim() !== '') return;

    const removed = this.model.splice(index, 1);

    this.$el.dispatchEvent(new CustomEvent('remove', { detail: { tag: removed[0] } }));
  },
  /**
   * Erase the entire input.
   *
   * @returns {void}
   */
  erase() {
    this.$el.dispatchEvent(new CustomEvent('erase', { detail: { tags: this.model } }));

    this.model = [];
  },
  /**
   * Prefix the tag.
   *
   * @returns {void}
   */
  prefix() {
    if (!this.prefixes || !this.model) {
      return;
    }

    this.model = this.model.map((item) =>
      item.indexOf(this.prefixes) === -1 ? this.prefixes + item : item
    );
  },
  /**
   * Set the input value when is not Livewire
   *
   * @param {*} value
   */
  set input(value) {
    if (this.livewire) return;

    const input = document.getElementsByName(this.property)[0];

    if (!input) return;

    value = value?.filter((value) => value !== '');

    input.value =
      !value || value.length === 0
        ? ''
        : (typeof value === 'string' && value.indexOf(',') !== -1) ||
            (typeof value === 'object' && value.length > 1)
          ? JSON.stringify(value)
          : value;
  },
});
