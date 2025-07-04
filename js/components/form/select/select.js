import { error, wireChange } from '../../../helpers';
import { body } from './helpers';

export default (
  model = null,
  request = null,
  selectable = {},
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
  unfiltered = false,
  lazy = 10
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
  options: null,
  observer: null,
  observing: false,
  livewire: livewire,
  property: property,
  value: value,
  limit: limit,
  image: null,
  index: null,
  lazy: lazy,
  _normalize: false,
  async init() {
    if (!this.livewire) {
      if (this.common) {
        this.$nextTick(() => this.initAsVanilla());
      } else {
        // For non-common type we need to use await to wait
        // for the component to be mounted and then initialize it.
        await this.$nextTick(() => this.initAsVanilla());
      }
    }

    if (this.common) {
      return this.initAsCommon();
    }

    await this.initAsRequest();

    this.$cleanup = () => {
      if (this.observer) {
        this.observer.disconnect();
        this.observer = null;
      }
    };
  },
  /**
   * Initialize the component as Blade vanilla.
   *
   * @returns {void}
   */
  initAsVanilla() {
    if (!this.value) return;

    this.input = this.model = this.value;
  },
  /**
   * Initialize the component as common select.
   *
   * @returns {void}
   */
  initAsCommon() {
    this.observation();

    this.hydrate();

    this.$watch('show', async (value) => {
      this.index = null;

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
      if (!value) return this.reset(true);

      if (value === old) return;

      this.hydrate(value);
    });
  },
  /**
   * Initialize the component as request.
   *
   * @returns {void}
   */
  async initAsRequest() {
    this.$watch('show', async (value, old) => {
      if (value === old) return;

      if (!value) return (this.search = '');

      await this.makeRequest(false);

      setTimeout(() => this.$refs.search.focus(), 100);
    });

    this.$watch('search', async () => this.makeRequest(false));

    // We only make the request when rendering
    // the component if the model is defined.
    if (this.model || this.model?.length > 0) {
      await this.makeRequest();

      this.hydrate();
    }

    // This watch aims to monitor external changes to the property
    // linked with `model` for situations where changes were made
    // out of the component to the variable that is linked to the `model`
    this.$watch('model', async (value, old) => {
      // When the value is null we clear the select. This is necessary due
      // situations where we are binding the same model in live entangle
      if (!value) return this.reset(true);

      // When the change was not internal and the model was different
      // from the old one, the component could probably be used in a
      // loop, so we make the request to hydrate the selected model.
      if (!this.internal && value !== old) await this.makeRequest();

      // This is used to avoid the need of hydrate the selects when
      // the changes are made internally, such as select options.
      if (value === old || this.internal) return (this.internal = false);

      if (this.response.length === 0) return;

      this.hydrate(value);
    });
  },
  /**
   * Make the request to the server.
   *
   * @param {Boolean} selected - Indicate that the selected should be sent.
   * @return {void}
   */
  async makeRequest(selected = true) {
    this.loading = true;

    this.response = [];

    // When using request parameters we evaluate this through the ref which
    // stores the parameters to allow us to hydrate this when changes are made.
    this.request.params &&= Alpine.evaluate(this, this.$refs.params.innerText);

    const { url, init } = body(
      this.request,
      this.search,
      selected && this.model ? (this.model.constructor === Array ? this.model : [this.model]) : []
    );

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
   * Select the `option`.
   *
   * @param option {Object}
   * @return {void}
   */
  select(option) {
    if (!option || option.disabled) return;

    this.internal = true;

    if (!this.empty && this.available?.length > 0 && this.selects?.includes(option)) {
      this.clear(option);
      this.input = this.model;

      return;
    }

    if (this.limit !== null && this.multiple && this.quantity >= this.limit) return;

    if (this.multiple) {
      this.selects = [...this.selects, option];

      this.model = this.dimensional
        ? this.selects.map((selected) => selected[this.selectable.value])
        : this.selects;
    } else {
      this.selects = [option];

      this.model = this.dimensional ? option[this.selectable.value] : option;
      this.placeholder = this.dimensional ? option[this.selectable.label] || '' : String(option);
      this.image = option[this.selectable.image] ?? null;
    }

    this.show = this.multiple && this.quantity !== this.available?.length;

    this.search = '';

    this.input = this.model;

    const button = this.$refs.button;

    if (button) {
      this.$nextTick(() =>
        button.dispatchEvent(
          new CustomEvent('select', {
            detail: {
              select: option,
            },
          })
        )
      );
    }

    if (change) {
      wireChange(change, this.model);
    }
  },
  /**
   * Clear the `selected` option or all.
   *
   * @param selected {Object|null}
   * @returns {void}
   */
  clear(selected = null) {
    const button = this.$refs.button;

    this.internal = true;

    if (button) {
      this.$nextTick(() => button.dispatchEvent(new CustomEvent('remove', { detail: { select: selected } })));
    }

    if (selected && this.multiple) {
      if (this.required && this.quantity === 1) {
        this.show = false;

        return;
      }

      this.selects = this.selects.filter((option) => {
        if (!option || !selected) return true;

        const value = this.dimensional ? option[this.selectable.value] : option;
        const selecting = this.dimensional ? selected[this.selectable.value] : selected;

        return !this.compare(value, selecting);
      });

      this.model = this.dimensional
        ? this.selects.map((selected) => selected[this.selectable.value])
        : this.selects;

      this.input = this.model;

      return;
    }

    if (this.required) {
      this.show = false;
      return;
    }

    this.selects = [];
    this.reset();

    if (change) {
      wireChange(change, this.model);
    }
  },
  /**
   * Reset properties.
   *
   * @param ignore {Boolean} - If true, will not interact with `show` property
   * @returns {void}
   */
  reset(ignore = false) {
    this.internal = true;

    this.input = null;
    this.model = null;
    this.placeholder = placeholder;
    this.image = null;
    this.search = '';
    this.index = null;

    this.$nextTick(() => {
      this.selects = [];

      if (!ignore) {
        this.show = false;
      }
    });
  },
  /**
   * Observe the options element to sync the options.
   *
   * @returns {void}
   */
  observation() {
    this.sync();

    if (!this.$refs.options) return;

    this.observer = new MutationObserver(this.sync.bind(this));

    this.observer.observe(this.$refs.options, {
      subtree: true,
      characterData: true,
    });
  },
  /**
   * Control the observation.
   *
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
   * Sync the options through observation.
   *
   * @returns {void}
   */
  sync() {
    if (!this.$refs.options) return;

    this.options = Alpine.evaluate(this, this.$refs.options.innerText);
  },
  /**
   * Hydrate the selects according to model.
   * @param value {*}
   * @returns {void}
   */
  hydrate(value = null) {
    this.model = value ?? this.model;

    if (this.model == null) {
      this.selects = [];
      this.placeholder = placeholder;
      this.image = null;
      return;
    }

    if (!this.available || this.available.length === 0) {
      this.selects = [];
      return;
    }

    if (!this.common) {
      this.selects = this.available.filter((option) => {
        if (!option) return false;

        return this.multiple
          ? Array.isArray(this.model) &&
              this.model.some((modelValue) =>
                this.compare(modelValue, option[this.selectable.value])
              )
          : this.compare(this.model, option[this.selectable.value]);
      });

      if (!this.multiple && this.selects.length > 0) {
        this.placeholder = this.selects[0]?.[this.selectable.label] ?? placeholder;
        this.image = this.selects[0]?.[this.selectable.image] ?? null;
      }

      return;
    }

    if (this.multiple) {
      if (!Array.isArray(this.model)) {
        this.selects = [];
        return;
      }

      this.selects = this.available.filter((option) => {
        if (!option) return false;

        return this.dimensional
          ? this.model.some((modelValue) => this.compare(modelValue, option[this.selectable.value]))
          : this.model.some((modelValue) => this.compare(modelValue, option));
      });

      return;
    }

    const selected = this.available.find((option) => {
      if (!option) return false;

      return this.dimensional
        ? this.compare(this.model, option[this.selectable.value])
        : this.compare(this.model, option);
    });

    if (selected) {
      this.selects = [selected];
      this.placeholder = this.dimensional
        ? (selected[this.selectable.label] ?? placeholder)
        : String(selected);
      this.image = selected[this.selectable.image] ?? null;
    } else {
      this.selects = [];
      this.placeholder = placeholder;
      this.image = null;
    }
  },
  /**
   * Compare the model and data using the same type.
   *
   * @param model {*}
   * @param data {*}
   * @return {boolean}
   */
  compare(model, data) {
    if (model === data || (model == null && data == null)) {
      return true;
    }

    if (model == null || data == null) {
      return false;
    }

    if (typeof model === typeof data && typeof model !== 'object') {
      return model === data;
    }

    if (typeof data === 'string') {
      return model.toString() === data;
    }

    if (typeof data === 'number') {
      if (typeof model !== 'number') {
        const num = Number(model);

        return !isNaN(num) && num === data;
      }

      return model === data;
    }

    if (typeof data === 'boolean') {
      return Boolean(model) === data;
    }

    if (typeof data === 'object') {
      if (Array.isArray(data) && Array.isArray(model)) {
        if (data.length !== model.length) {
          return false;
        }

        for (let i = 0; i < data.length; i++) {
          if (!this.compare(model[i], data[i])) {
            return false;
          }
        }

        return true;
      }

      return JSON.stringify(model) === JSON.stringify(data);
    }

    return model === data;
  },
  /**
   * Normalize the string removing accents and special characters.
   *
   * @param string {String}
   * @return {String}
   */
  normalize(string) {
    if (!string) return '';

    if (!this._normalize) this._normalize = new Map();

    if (this._normalize.has(string)) {
      return this._normalize.get(string);
    }

    const normalized = string.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    this._normalize.set(string, normalized);

    if (this._normalize.size > 100) {
      const key = this._normalize.keys().next().value;

      this._normalize.delete(key);
    }

    return normalized;
  },
  /**
   * Navigate between select items.
   *
   * @param {Event} event
   * @return {void}
   */
  navigate(event) {
    const key = event.key;

    if (!this.show) {
      if (key === 'Tab') {
        return;
      }

      if (key === 'ArrowDown') {
        event.preventDefault();

        this.show = true;

        return;
      }
    }

    if (key !== 'ArrowUp' && key !== 'ArrowDown' && key !== 'Tab') return;

    event.preventDefault();

    if (!this.available || this.available.length === 0) return;

    const current = this.index ?? -1;
    const max = this.available.length - 1;

    let next;

    if (key === 'ArrowUp') {
      next = current <= 0 ? max : current - 1;
    } else if (key === 'ArrowDown' || key === 'Tab') {
      next = current >= max ? 0 : current + 1;
    }

    const options = this.$refs.list.querySelectorAll('[role="option"]');

    if (current >= 0 && current < options.length) {
      options[current].removeAttribute('tabindex');
    }

    if (next >= 0 && next < options.length) {
      options[next].setAttribute('tabindex', '0');
      options[next].focus();
    }

    this.index = next;
  },
  /**
   * Incrementally load more options when scrolling
   * @returns {void}
   */
  load() {
    if (!this.options || !this.available) return;

    const length = this.options.length;

    if (length <= this.lazy) return;

    this.lazy = Math.min(this.lazy * 2, length);
  },
  /**
   * Set the input value when is not Livewire.
   *
   * @param {*} value
   */
  set input(value) {
    if (this.livewire) return;

    const input = document.getElementsByName(this.property)[0];

    if (!input) return;

    input.value = !value
      ? ''
      : (typeof value === 'string' && value.indexOf(',') !== -1) || typeof value === 'object'
        ? JSON.stringify(value)
        : value;
  },
  /**
   * The `selects` quantity.
   *
   * @returns {Number}
   */
  get quantity() {
    return this.selects?.length ?? 0;
  },
  /**
   * Check if the `selects` is empty.
   *
   * @returns {Boolean}
   */
  get empty() {
    return !this.selects || this.quantity === 0;
  },
  /**
   * Available options to select.
   *
   * @returns {Array}
   */
  get available() {
    let available = this.common ? this.options : this.response;

    if (!available) return [];

    if (this.common) {
      const values = Object.values(available);

      available = this.lazy ? values.slice(0, this.lazy) : values;
    }

    if (this.search === '') return available;

    const search = this.normalize(this.search.toLowerCase());

    const filter = (option) => {
      if (!option) return false;

      if (this.dimensional) {
        const value = option[this.selectable.label];

        if (!value) return false;

        const label = this.normalize(value.toString().toLowerCase());

        if (label.indexOf(search) !== -1) return true;

        if (option[this.selectable.description]) {
          const description = this.normalize(
            option[this.selectable.description].toString().toLowerCase()
          );

          return description.indexOf(search) !== -1;
        }

        return false;
      }

      return this.normalize(option.toString().toLowerCase()).indexOf(search) !== -1;
    };

    if (this.common) {
      const result = available.filter(filter);

      return this.lazy ? result.slice(0, this.lazy) : result;
    }

    return unfiltered ? available : available.filter(filter);
  },
});
