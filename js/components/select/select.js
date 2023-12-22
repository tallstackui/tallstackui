import {error, warning} from '../../helpers';
import {body} from './helpers';

/**
 * @param model {Object|String} - The model object or the value
 * @param request {Object|String} - The request object or the url
 * @param selectable {Object} - The label and value of the select when dimensional is true
 * @param options {Array} - The options of the select when is common
 * @param multiple {Boolean} - If true, the select will be multiple
 * @param placeholder {String} - The placeholder of the select
 * @param searchable {Boolean} - If true, the search input will be shown
 * @param common {Boolean} - If true, the options will be taken from the options variable
 * @param livewire {Boolean} - If true, the select will be used in livewire
 * @param property {String} - The property name of the input
 * @param value {String|Array} - The value of the input
 */
export default (
    model = null,
    request,
    selectable = {},
    options = [],
    multiple = false,
    placeholder = 'Select an option',
    searchable = false,
    common = true,
    livewire,
    property,
    value,
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
  response: [],
  options: options,
  observer: null,
  observing: false,
  livewire: livewire,
  property: property,
  value: value,
  async init() {
    if (this.multiple && this.model && this.model.constructor !== Array) {
      return warning('The [wire:model] must be an array');
    }

    if (!this.multiple && this.model && this.model.constructor === Array) {
      return warning('The [wire:model] must not be an array when is not multiple');
    }

    if (this.common && (this.dimensional && this.selectable.constructor === Array && this.selectable?.length === 0)) {
      return warning('The [select] must be defined');
    }

    if (this.common) {
      return this.initAsCommon();
    }

    await this.initAsRequest();
  },
  /**
   * Initialize the component as common
   * @returns {void}
   */
  initAsCommon() {
    this.observation();

    if (this.multiple) {
      this.selects = this.available.filter((option) => this.dimensional ?
              this.model?.includes(option[this.selectable.value]) :
              this.model?.includes(option));
    } else {
      console.log(1);
      this.selects = this.available.find((option) => this.dimensional ?
              this.model === option[this.selectable.value] :
              this.model === option,
      );

      if (!this.empty) {
        this.selects = this.dimensional ?
            [this.selects] :
            this.selects;
      } else {
        this.selects = [];
      }
    }

    this.$watch('show', async (value) => {
      if (!value || !this.searchable) {
        return;
      }

      setTimeout(() => this.$refs.search.focus(), 100);
    });

    this.$watch('options', async () => this.observed());

    this.$watch('model', (value, old) => {
      if (!this.livewire) {
        const input = document.getElementsByName(this.property)[0];

        if (!input) {
          return;
        }

        input.value = value;
      }

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

      if (this.multiple) {
        this.selects = this.available.filter((option) => this.dimensional ?
            value?.includes(option[this.selectable.value]) :
            value?.includes(option));
      } else {
        this.selects = this.available.find((option) => this.dimensional ?
                value.toString() === option[this.selectable.value].toString() :
                value.toString() === option.toString());

        this.selects = this.dimensional ?
            [this.selects] :
            this.selects;

        console.log(123);
        this.placeholder = this.dimensional ?
            this.selects[0]?.[this.selectable.label] ?? placeholder :
            (!this.empty ? this.selects : placeholder);
      }
    });

    if (!this.livewire && this.value) {
      this.model = this.value;
    }
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

    this.options = window.Alpine.evaluate(this, this.$refs.options.innerText);
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

    if (this.model) {
      await this.makeRequest();

      this.selects = this.available.filter((option) => {
        return this.multiple ?
            this.model.includes(option[this.selectable.value]) :
            this.model === option[this.selectable.value];
      });

      if (!this.multiple) {
        this.placeholder = this.selects[0][this.selectable.label] ?? placeholder;
      }
    }

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

      if (this.multiple) {
        this.selects = this.available.filter((option) => {
          return value?.includes(option[this.selectable.value]);
        });
      } else {
        this.selects = this.available.filter((option) => {
          return value?.toString() === option[this.selectable.value].toString();
        });

        if (this.quantity > 0) {
          this.placeholder = this.selects[0][this.selectable.label] ?? placeholder;
        }
      }
    });
  },
  /** @returns {void} */
  async makeRequest() {
    this.loading = true;

    this.response = [];

    if (this.request.params?.constructor === Array) {
      return error('The [params] must be an array with key and value pairs');
    }

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
    this.internal = true;

    if (this.selected(option)) {
      this.clear(option);

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
    }

    this.show = this.quantity === this.available?.length ? false : this.multiple;
    this.search = '';
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
      if (this.multiple) {
        this.selects = this.selects.filter((option) => this.dimensional ?
            option[this.selectable.value] !== selected[this.selectable.value] :
            option !== selected);

        this.model = this.dimensional ?
                    this.selects.map((selected) => selected[this.selectable.value]) :
                    this.selects;
      } else {
        this.selects = [];
      }

      if (this.quantity > 0) {
        return;
      }

      this.clear();
    }

    this.reset();
  },
  /**
   * Reset properties.
   *
   * @param ignore {Boolean} - If true, will not interact with `show` property
   */
  reset(ignore = false) {
    this.model = null;
    this.placeholder = placeholder;
    this.search = '';
    this.$nextTick(() => this.selects = []);

    if (ignore) {
      return;
    }

    this.show = false;
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

    const search = this.search.toLowerCase();

    return available.filter((option) => {
      return this.dimensional ?
          option[selectable.label].toString().toLowerCase().indexOf(search) !== -1 :
          option.toString().toLowerCase().indexOf(search) !== -1;
    });
  },
});
