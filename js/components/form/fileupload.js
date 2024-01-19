export default (id, property, multiple, error, placeholder, placeholders) => ({
  show: false,
  uploading: false,
  error: false,
  warning: error,
  progress: 0,
  property: property,
  multiple: multiple,
  placeholders: placeholders,
  component: null,
  init() {
    this.component = Livewire.find(id).__instance;

    this.$watch('uploading', () => this.placeholder());
  },
  upload() {
    if (!this.$refs.files.files.length) return;

    this.uploading = true;
    this.error = false;

    if (this.multiple) return this.multiples();

    this.single();
  },
  multiples() {
    this.component.$wire.uploadMultiple(
        this.property,
        this.$refs.files.files,
        () => {
          this.uploading = false;
          this.progress = 0;
        },
        () => {
          this.uploading = false;
          this.error = true;
          this.progress = 0;
        },
        (event) => this.progress = event.detail.progress,
    );
  },
  single() {
    this.component.$wire.upload(
        this.property,
        this.$refs.files.files[0],
        () => {
          this.uploading = false;
          this.progress = 0;
        },
        () => {
          this.uploading = false;
          this.error = true;
          this.progress = 0;
        },
        (event) => this.progress = event.detail.progress,
    );
  },
  /**
   * Remove a file through Livewire component.
   * @param method {String}
   * @param original {String}
   * @param temporary {String}
   * @returns {void}
   */
  remove(method, original, temporary) {
    this.component.$wire.call(method, original, temporary);

    this.placeholder();
  },
  /**
   * Set the input placeholder.
   * @returns {void}
   */
  placeholder() {
    setTimeout(() => {
      const property = this.component.$wire.get(this.property);

      if (this.multiple) {
        // For an unknown reason, sometimes the property returns as an object.
        const quantity = typeof property === 'object' ?
          Object.keys(property).length :
          property.length;

        this.input = quantity === 0 ? null : quantity;

        const items = this.$refs.items;
        items?.scrollTo(0, items.scrollHeight);

        return;
      }

      this.input = property ? 1 : null;
    }, 1000);
  },
  set input(value) {
    const input = this.$refs.input;

    if (!value) return input.value = placeholder;

    input.value = this.placeholders[this.multiple ? 'multiple' : 'single'].replace(':count', value);
  },
});
