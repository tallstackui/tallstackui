import {error, warning, wireChange} from '../../helpers';
import {body} from './helpers';

export default (
    model = null,
    request,
    selectable = {},
    options = [],
    multiple = false,
    placeholder = 'Select an option',
    searchable = false,
    common = true,
    required = false,
    livewire,
    property,
    value,
    limit = null,
    change = null,
) => ({
  show: false,
  model: model,
  request: request,
  selects: null,
  search: '',
  searchable: searchable,
  multiple: multiple,
  dimensional: !!(selectable?.label && selectable?.value),
  selectable: selectable,
  loading: false,
  placeholder: placeholder,
  internal: false,
  common: common,
  required: required,
  response: [],
  options: options,
  observer: null,
  observing: false,
  livewire: livewire,
  property: property,
  value: value,
  limit: limit,
  change: change,
  image: null,
  async init() {
    // When the component is not being used in livewire.
    if (!this.livewire) {
      if (this.common) {
        this.$nextTick(() => this.initAsVanilla());
      } else {
        // For non-common type we need to use await to wait for the
        // component to be mounted and then initialize it.
        await this.$nextTick(() => this.initAsVanilla());
      }
    }

    const label = this.livewire ? 'wire:model' : 'value';

    if (this.multiple && this.model && this.model.constructor !== Array) {
      return warning(`The [${label}] must be an array when multiple is set`);
    }

    if (!this.multiple && this.model && this.model.constructor === Array) {
      return warning(`The [${label}] must not be an array when is not multiple`);
    }

    if (this.common && (this.dimensional && this.selectable.constructor === Array && this.selectable?.length === 0)) {
      return warning(`The [${label}] must be defined`);
    }

    if (this.common) {
      return this.initAsCommon();
    }

    await this.initAsRequest();
  },
  /**
   * Initialize the component as Blade vanilla
   * @returns {void}
   */
  initAsVanilla() {
    if (!this.value) {
      return;
    }

    this.input = this.model = this.value;
  },
  /**
   * Initialize the component as common
   * @returns {void}
   */
  initAsCommon() {
    this.observation();

    this.hydrate();

    this.$watch('show', async (value) => {
      if (!value || !this.searchable) {
        return;
      }

      setTimeout(() => this.$refs.search.focus(), 100);
    });

    this.$watch('options', async () => this.observed());

    // This watch aims to monitor external changes to the property
    // linked with `model` for situations where changes were made
    // out of the component to the variable that is linked to the `model`
    this.$watch('model', (value, old) => {
      // When the value is null we clear the select. This is necessary due
      // situations where we are binding the same model in live entangle
      if (value === null) {
        this.reset(true);
        return;
      }

      // This is used to avoid the need of hydrate the selects when
      // the changes are made internally, such as select options.
      if (this.internal) {
        this.internal = false;
        return;
      }

      if (!value || value === old) {
        return;
      }

      this.hydrate(value);
    });
  },
  /**
   * Initialize the component as request
   * @returns {Promise<void>}
   */
  async initAsRequest() {
    this.$watch('show', async (value, old) => {
      if (value === old) {
        return;
      }

      if (!value) {
        this.search = '';

        return;
      }

      await this.makeRequest();

      setTimeout(() => this.$refs.search.focus(), 100);
    });

    this.$watch('search', async () => this.makeRequest());

    // We only make the request when rendering
    // the component if the model is defined.
    if (this.model?.length > 0) {
      await this.makeRequest();

      this.hydrate();
    }

    // This watch aims to monitor external changes to the property
    // linked with `model` for situations where changes were made
    // out of the component to the variable that is linked to the `model`
    this.$watch('model', (value, old) => {
      // When the value is null we clear the select. This is necessary due
      // situations where we are binding the same model in live entangle
      if (!value) {
        this.reset(true);
        return;
      }

      // This is used to avoid the need of hydrate the selects when
      // the changes are made internally, such as select options.
      if (value === old || this.internal) {
        this.internal = false;
        return;
      }

      // If the response is empty, we need to wait for the request.
      if (this.response.length === 0) {
        return;
      }

      this.hydrate(value);
    });
  },
  /** @returns {void} */
  async makeRequest() {
    this.loading = true;

    this.response = [];

    if (this.request.params?.constructor === Array) {
      return error('The [params] must be an array with key and value pairs');
    }

    // When using request parameters we evaluate this through the ref which
    // stores the parameters to allow us to hydrate this when changes are made.
    this.request.params &&= Alpine.evaluate(this, this.$refs.params.innerText);

    const {url, init} = body(this.request, this.search, this.model ?
        (this.model.constructor === Array ? this.model : [this.model]) :
        []);

    try {
      const response = await fetch(url, init);

      const data = await response.json();

      this.response = data.map((option) => {
        if (!option[this.selectable.label]) {
          throw new Error('The [select.label] was not found in the response');
        }

        return {
          ...option,
          [this.selectable.label]: option[this.selectable.label].toString(),
        };
      });
    } catch (e) {
      error(e.message);
    } finally {
      this.loading = false;
    }
  },
  /**
   * Select the `option`
   *
   * @param option {Object}
   * @return {void}
   */
  select(option) {
    if (option.disabled) {
      return;
    }

    this.internal = true;

    // When already selected, then we just clear the select.
    if (this.selected(option)) {
      this.clear(option);
      this.input = this.model;

      return;
    }

    if (this.limit !== null && (this.multiple && this.quantity >= this.limit)) {
      return;
    }

    if (this.multiple) {
      this.selects = !this.empty ?
          [...this.selects, option] :
          [option];

      this.model = this.dimensional ?
          this.selects.map((selected) => selected[this.selectable.value]) :
          this.selects;

      this.search = '';
    } else {
      this.selects = [option];

      this.model = this.dimensional ? option[this.selectable.value] : option;
      this.placeholder = this.dimensional ? option[this.selectable.label] : option;
      this.image = option.image ?? null;
    }

    this.show = this.quantity === this.available?.length ? false : this.multiple;
    this.search = '';
    this.input = this.model;

    this.$refs.button.dispatchEvent(new CustomEvent('select', {
      detail: {
        select: option,
      },
    }));

    wireChange(this.change, this.model);
  },
  /**
   * Check if the `option` is selected
   *
   * @param option
   * @returns {boolean}
   */
  selected(option) {
    if (this.empty || this.available?.length === 0) return false;

    return this.multiple ?
        this.selects?.some((selected) => JSON.stringify(selected) === JSON.stringify(option)) :
        JSON.stringify(this.selects[0] ?? this.selects) === JSON.stringify(option);
  },
  /**
   * @param selected {Object|null}
   * @returns {void}
   */
  clear(selected = null) {
    if (selected) {
      this.$refs.button.dispatchEvent(new CustomEvent('remove', {detail: {select: selected}}));

      if (this.multiple) {
        if (this.required && this.quantity === 1) {
          this.show = false;
          return;
        }

        this.selects = this.selects.filter((option) => this.dimensional ?
            option[this.selectable.value] !== selected[this.selectable.value] :
            option !== selected);

        this.model = this.dimensional ?
            this.selects.map((selected) => selected[this.selectable.value]) :
            this.selects;
      } else {
        if (this.required) {
          this.show = false;
          return;
        }

        // We clear the entire select property if it is not a multiple,
        // because if it is not a multiple, it will be a single selection.
        this.selects = [];
      }

      if (this.quantity > 0) {
        return;
      }

      this.clear();
    }

    this.$refs.button.dispatchEvent(new CustomEvent('erase', {detail: {selects: this.model}}));

    this.reset();
  },
  /**
   * Reset properties.
   *
   * @param ignore {Boolean} - If true, will not interact with `show` property
   */
  reset(ignore = false) {
    this.input = null;
    this.model = null;
    this.placeholder = placeholder;
    this.image = null;
    this.search = '';
    this.$nextTick(() => this.selects = []);

    if (ignore) {
      return;
    }

    this.show = false;
  },
  /**
   * Observe the options element to sync the options
   * @returns {void}
   */
  observation() {
    this.sync();

    if (!this.$refs.options) {
      return;
    }

    this.observer = new MutationObserver(this.sync.bind(this));

    this.observer.observe(this.$refs.options, {
      subtree: true,
      characterData: true,
    });
  },
  /**
   * Control the observation
   * @returns {Promise<void>}
   */
  async observed() {
    if (this.observer && !this.observing) {
      this.observer.disconnect();

      this.observing = true;
    }

    await this.$nextTick();

    this.observing = false;

    this.observation();
  },
  /**
   * Sync the options
   * @returns {void}
   */
  sync() {
    if (!this.$refs.options) {
      return;
    }

    this.options = Alpine.evaluate(this, this.$refs.options.innerText);
  },
  /**
   * Hydrate the selects according to model.
   * @param value {*}
   * @returns {void}
   */
  hydrate(value = null) {
    this.model = value ?? this.model;

    if (!this.common) {
      this.selects = this.available.filter((option) => {
        return this.multiple ?
            this.model?.includes(option[this.selectable.value]) :
            this.compare(this.model, option[this.selectable.value]);
      });

      if (!this.multiple) {
        this.placeholder = this.selects[0]?.[this.selectable.label] ?? placeholder;
      }

      return;
    }

    if (this.multiple) {
      this.selects = this.available.filter((option) => this.dimensional ?
          this.model?.includes(option[this.selectable.value]) :
          this.model?.includes(option));

      return;
    }

    this.selects = this.available.find((option) => this.dimensional ?
        this.compare(this.model, option[this.selectable.value]) :
        this.compare(this.model, option));

    if (this.selects) {
      this.selects = [this.selects];

      this.placeholder = this.dimensional ?
          this.selects[0]?.[this.selectable.label] ?? placeholder :
          this.selects[0] ?? placeholder;
      this.image = this.selects[0]?.image ?? null;
    } else {
      this.selects = [];
    }
  },
  /**
   * Compare the model and data using the same type
   * @param model {*}
   * @param data {*}
   * @return {boolean}
   */
  compare(model, data) {
    if (typeof data === 'string') {
      model = model?.toString();
    }

    if (typeof data === 'number') {
      model = parseInt(model);
    }

    return model === data;
  },
  /**
   * Normalize the string removing accents and special characters
   * @param string {String}
   * @return {String}
   */
  normalize(string) {
    return string.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
  },
  /**
   * Set the input value when is not Livewire
   * @param data {*}
   */
  set input(data) {
    if (this.livewire) {
      return;
    }

    const input = document.getElementsByName(this.property)[0];

    if (!input) {
      return;
    }

    // If the value is null (undefined) we set the input as empty,
    // otherwise we stringify if is string with comma or an object
    input.value = !data ?
        '' :
        (typeof data === 'string' && data.indexOf(',') !== - 1 || typeof data === 'object' ? JSON.stringify(data) : data);
  },
  /**
   * The `selects` quantity
   *
   * @returns {Number}
   */
  get quantity() {
    return this.selects?.length ?? 0;
  },
  /**
   * Check if the `selects` is empty
   *
   * @returns {Boolean}
   */
  get empty() {
    return !this.selects || this.quantity === 0;
  },
  /**
   * Available options to select
   *
   * @returns {Array}
   */
  get available() {
    const available = this.common ? this.options : this.response;

    if (this.search === '') {
      return available;
    }

    const search = this.normalize(this.search.toLowerCase());

    return available.filter((option) => {
      const label = this.normalize(option[selectable.label].toString().toLowerCase());

      const description = option.description ?
        this.normalize(option.description.toString().toLowerCase()) :
        null;

      return this.dimensional ?
        (label.indexOf(search) !== -1 || (this.common && description && description.indexOf(search) !== -1)) :
          this.normalize(option.toString().toLowerCase()).indexOf(search) !== -1;
    });
  },
});
