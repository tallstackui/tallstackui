import {error, warning} from '../../helpers';
import {body} from './helpers';

/**
 * @param model {Object|String} - The model object or the value
 * @param request {Object|String} - The request object or the url
 * @param selectable {Object} - The label and value of the select when dimensional is true
 * @param options {Array} - The options of the select when is common
 * @param multiple {Boolean} - If true, the select will be multiple
 * @param dimensional {Boolean} - Multidimensional array
 * @param placeholder {String} - The placeholder of the select
 * @param searchable {Boolean} - If true, the search input will be shown
 * @param common {Boolean} - If true, the options will be taken from the options variable
 */
export default (
    model = null,
    request,
    selectable = {},
    options = [],
    multiple = false,
    dimensional = false,
    placeholder = 'Select an option',
    searchable = false,
    common = true,
) => ({
  show: false,
  model: model,
  request: request,
  selects: null,
  search: '',
  searchable: searchable,
  multiple: multiple,
  dimensional: dimensional,
  selectable: selectable,
  loading: false,
  placeholder: placeholder,
  internal: false,
  common: common,
  response: [],
  options: options,
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
   * @returns {Promise<void>}
   */
  initAsCommon() {
    this.initOptionsObserver();

    if (this.multiple) {
      this.selects = this.available.filter((option) => this.dimensional ?
              this.model?.includes(option[this.selectable.value]) :
              this.model?.includes(option));
    } else {
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

    this.$watch('model', (value, old) => {
      // When the value is null we clear the select. This is necessary due
      // situations where we are binding the same model in live entangle
      if (value === null) {
        this.reset(true);
        return;
      }

      this.show = this.quantity === this.availables?.length ? false : this.multiple;

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
        // eslint-disable-next-line max-len
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

        this.placeholder = this.dimensional ?
            this.selects[0]?.[this.selectable.label] ?? placeholder :
            (!this.empty ? this.selects : placeholder);
      }
    });
  },
  initOptionsObserver() {
    this.syncJsonOptions();

    const observer = new MutationObserver(this.syncJsonOptions.bind(this));

    observer.observe(this.$refs.options, {
      subtree: true,
      characterData: true,
    });
  },
  syncJsonOptions() {
    this.setOptions(window.Alpine.evaluate(this, this.$refs.options.innerText));
  },
  setOptions(options) {
    this.options = options;
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

      this.loading = true;

      if (!value) {
        this.search = '';

        return;
      }

      await this.sendRequest();
      setTimeout(() => this.$refs.search.focus(), 100);
    });

    this.$watch('search', async () => {
      this.loading = true;
      await this.sendRequest();
    });

    if (this.model) {
      await this.sendRequest();

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

      this.show = this.quantity === this.availables?.length ? false : this.multiple;

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
  /** @returns {Promise<void>} */
  async sendRequest() {
    this.response = [];

    if (this.request.params?.constructor === Array) {
      return error('The [params] must be an array with key and value pairs');
    }

    // eslint-disable-next-line max-len
    const {url, init} = body(this.request, this.search, this.model ? (this.model.constructor === Array ? this.model : [this.model]) : []);

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

      this.show = false;
      this.search = '';
    } else {
      this.selects = [option];

      if (this.dimensional) {
        this.model = option[this.selectable.value];
        this.placeholder = option[this.selectable.label];
      } else {
        this.model = option;
        this.placeholder = option;
      }
    }

    this.search = '';
  },
  selected(option) {
    if (this.empty || this.available.length === 0) return false;

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
    this.selects = [];
    this.search = '';

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
    return this.availableOptions();
  },
  availableOptions() {
    const available = this.common ? this.options : this.response;

    if (this.search === '') {
      return available;
    }

    const search = this.search.toLowerCase();

    return available.filter((option) => {
      return dimensional ?
          option[selectable.label].toString().toLowerCase().indexOf(search) !== -1 :
          option.toString().toLowerCase().indexOf(search) !== -1;
    });
  },
});
