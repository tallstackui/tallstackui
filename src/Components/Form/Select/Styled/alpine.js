import { error, wireChange } from '../../../../../js/helpers';
import { body } from '../helpers';

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
  lazy = 10,
  recycle = false
) => ({
  show: false,
  model: model,
  request: request,
  selects: [],
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
  // Performance: caches the `available` getter result to avoid redundant
  // recomputation across multiple accesses within the same interaction cycle.
  _availableCache: [],
  _availableDirty: true,
  // Performance: caches querySelectorAll result for keyboard navigation,
  // invalidated together with `_availableDirty` when options change.
  _navigateOptions: null,
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

    this.$watch('search', () => this.invalidateAvailable());
    this.$watch('lazy', () => this.invalidateAvailable());

    // This watch aims to monitor external changes to the property
    // linked with `model` for situations where changes were made
    // out of the component to the variable that is linked to the `model`
    this.$watch('model', (value, old) => {
      if (value === old) return;

      this.hydrate(value);
    });
  },
  /**
   * Initialize the component as a request.
   *
   * @returns {void}
   */
  async initAsRequest() {
    this.$watch('show', async (value, old) => {
      if (value === old) return;

      if (!value) return (this.search = '');

      if (recycle && this.response.length > 0) {
        this.invalidateAvailable();
        setTimeout(() => this.$refs.search.focus(), 100);

        return;
      }

      await this.makeRequest(false);

      setTimeout(() => this.$refs.search.focus(), 100);
    });

    this.$watch('search', async () => {
      this.invalidateAvailable();

      if (recycle && this.search === '' && this.response.length > 0) {
        return;
      }

      this.makeRequest(false);
    });

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

    if (!recycle) {
      this.response = [];
    }

    this.invalidateAvailable();

    // When using request parameters, we evaluate this through the ref which
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

      this.preNormalize(this.response);
      this.invalidateAvailable();
    } catch (e) {
      error(e.message);
    } finally {
      this.loading = false;
    }
  },
  /**
   * Invalidate the available options cache and the
   * navigation DOM cache. Called when any dependency
   * of the `available` getter changes (search, options,
   * response, lazy) to ensure stale data is never served.
   *
   * @returns {void}
   */
  invalidateAvailable() {
    this._availableDirty = true;
    this._navigateOptions = null;
  },
  /**
   * Pre-compute normalized labels for search filtering.
   * Attaches `__normalized` (and `__normalizedDesc`) to each
   * option object so the `available` getter can compare against
   * pre-computed values instead of calling `normalize()` +
   * `toLowerCase()` per option on every search keystroke.
   *
   * @param {Array} options
   * @returns {void}
   */
  preNormalize(options) {
    if (!options || !Array.isArray(options)) return;

    for (let i = 0; i < options.length; i++) {
      const option = options[i];

      if (!option) continue;

      if (typeof option !== 'object') continue;

      if (this.dimensional) {
        const label = option[this.selectable.label];

        if (label) {
          option.__normalized = this.normalize(label.toString().toLowerCase());
        }

        const desc = option[this.selectable.description];

        if (desc) {
          option.__normalizedDesc = this.normalize(desc.toString().toLowerCase());
        }

        const value = option[this.selectable.value];

        if (Array.isArray(value)) {
          this.preNormalize(value);
        }
      }
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

    if (this.selected(option)) {
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
   * Check if the `option` is selected.
   *
   * @param option {Object|String|Number}
   * @returns {boolean}
   */
  selected(option) {
    if (this.empty || this.available?.length === 0) return false;

    if (this.multiple) {
      return this.selects?.some((selected) => this.compare(selected, option));
    }

    return this.compare(this.selects[0] ?? this.selects, option);
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
      this.$nextTick(() =>
        button.dispatchEvent(new CustomEvent('remove', { detail: { select: selected } }))
      );
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
   * Sync the options through observation. Converts to
   * array once here (instead of `Object.values()` on
   * every `available` access) and pre-normalizes labels.
   *
   * @returns {void}
   */
  sync() {
    if (!this.$refs.options) return;

    const raw = Alpine.evaluate(this, this.$refs.options.innerText);

    this.options = Array.isArray(raw) ? raw : Object.values(raw);

    this.preNormalize(this.options);
    this.invalidateAvailable();
  },
  /**
   * Hydrate the select according to model. Uses a Set of
   * string-coerced model values for O(1) lookups instead
   * of nested `compare()` calls, reducing complexity from
   * O(n*m) to O(n+m) for multiple select.
   *
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

    const items = this._flatItems(this.common && this.lazy ? this.options : this.available);

    if (!items || items.length === 0) {
      this.selects = [];
      return;
    }

    if (!this.common) {
      if (this.multiple && Array.isArray(this.model)) {
        const set = new Set(this.model.map((v) => String(v)));

        this.selects = items.filter(
          (option) => option && set.has(String(option[this.selectable.value]))
        );
      } else {
        const target = String(this.model);

        this.selects = items.filter(
          (option) => option && String(option[this.selectable.value]) === target
        );
      }

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

      const set = new Set(this.model.map((v) => String(v)));

      this.selects = items.filter((option) => {
        if (!option) return false;

        const val = this.dimensional ? option[this.selectable.value] : option;

        return set.has(String(val));
      });

      return;
    }

    const target = String(this.model);

    const selected = items.find((option) => {
      if (!option) return false;

      const val = this.dimensional ? option[this.selectable.value] : option;

      return String(val) === target;
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
   * Flatten grouped options into individual items. Returns
   * the original array unchanged when options are not grouped.
   *
   * @param {Array} items
   * @returns {Array}
   */
  _flatItems(items) {
    if (!items || items.length === 0 || !this.dimensional) return items;

    const first = items[0];

    if (!first || !Array.isArray(first[this.selectable.value])) return items;

    const flat = [];

    for (const group of items) {
      const children = group[this.selectable.value];

      if (Array.isArray(children)) {
        for (const child of children) flat.push(child);
      }
    }

    return flat;
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

    const items = this._flatItems(this.available);

    if (!items || items.length === 0) return;

    const current = this.index ?? -1;
    const max = items.length - 1;

    let next;

    if (key === 'ArrowUp') {
      next = current <= 0 ? max : current - 1;
    } else if (key === 'ArrowDown' || key === 'Tab') {
      next = current >= max ? 0 : current + 1;
    }

    if (!this._navigateOptions) {
      this._navigateOptions = this.$refs.list.querySelectorAll('[role="option"]');
    }

    const options = this._navigateOptions;

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
   * Available options to select. The result is cached via
   * `_availableCache` / `_availableDirty` to avoid redundant
   * recomputation — this getter is accessed multiple times per
   * interaction cycle (hydrate, selected, navigate, template).
   * Search filtering uses pre-normalized labels (`__normalized`)
   * attached by `preNormalize()` instead of recomputing on
   * every access.
   *
   * @returns {Array}
   */
  get available() {
    if (!this._availableDirty) return this._availableCache;

    let available = this.common ? this.options : this.response;

    if (!available) {
      this._availableCache = [];
      this._availableDirty = false;

      return this._availableCache;
    }

    if (this.common) {
      available = this.lazy ? available.slice(0, this.lazy) : available;
    }

    if (this.search === '') {
      this._availableCache = available;
      this._availableDirty = false;

      return this._availableCache;
    }

    const search = this.normalize(this.search.toLowerCase());

    const filter = (option) => {
      if (!option) return false;

      if (this.dimensional) {
        const label = option.__normalized;

        if (!label) return false;

        if (label.indexOf(search) !== -1) return true;

        if (option.__normalizedDesc) {
          return option.__normalizedDesc.indexOf(search) !== -1;
        }

        return false;
      }

      return (
        (option.__normalized || this.normalize(option.toString().toLowerCase())).indexOf(search) !==
        -1
      );
    };

    if (this.common) {
      const grouped =
        this.dimensional &&
        available.length > 0 &&
        available[0] &&
        Array.isArray(available[0][this.selectable.value]);

      if (grouped) {
        const result = [];

        for (const group of available) {
          const children = group[this.selectable.value];

          if (!Array.isArray(children)) continue;

          const filtered = children.filter(filter);

          if (filtered.length > 0) {
            result.push({ ...group, [this.selectable.value]: filtered });
          }
        }

        this._availableCache = result;
      } else {
        const result = available.filter(filter);

        this._availableCache = this.lazy ? result.slice(0, this.lazy) : result;
      }
    } else {
      this._availableCache = unfiltered ? available : available.filter(filter);
    }

    this._availableDirty = false;

    return this._availableCache;
  },
});
