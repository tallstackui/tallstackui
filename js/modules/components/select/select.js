import {error, warning} from '../../helpers';
import {body, sync} from './helpers';

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
  selecteds: null,
  search: '',
  searchable: searchable,
  multiple: multiple,
  dimensional: dimensional,
  selectable: selectable,
  loading: false,
  placeholder: placeholder,
  cleanup: null,
  internal: false,
  common: common,
  response: [],
  async init() {
    if (this.model === undefined) {
      return error('The [wire:model] is undefined');
    }

    if (this.multiple && this.model && this.model.constructor !== Array) {
      return warning('The [wire:model] must be an array');
    }

    if (!this.multiple && this.model && this.model.constructor === Array) {
      return warning('The [wire:model] must not be an array when is not multiple');
    }

    if (this.common && (this.dimensional && this.selectable.constructor === Array && this.selectable.length === 0)) {
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
    if (this.multiple) {
      this.selecteds = this.dimensional ?
          this.options.filter((option) => this.model?.includes(option[this.selectable.value])) :
          this.options.filter((option) => this.model?.includes(option));

      if (!this.empty) {
        this.placeholder = this.dimensional ?
            this.selecteds[0][this.selectable.label] :
            this.selecteds;
      }
    } else {
      this.selecteds = this.dimensional ?
          this.options.find((option) => this.model === option[this.selectable.value]) :
          this.options.find((option) => this.model === option);

      if (!this.empty) {
        this.selecteds = this.dimensional ?
            [this.selecteds] :
            this.selecteds;
      } else {
        this.selecteds = [];
      }

      this.placeholder = this.dimensional ?
          this.selecteds[0]?.[this.selectable.label] ?? placeholder :
          (!this.empty ? this.selecteds : placeholder);
    }

    if (this.selecteds === undefined) {
      this.selecteds = [];
      this.placeholder = placeholder;
    }

    this.$watch('show', async (value) => {
      if (!value) {
        if (this.cleanup) {
          this.cleanup();
          this.cleanup = null;
        }
        return;
      }

      if (value) {
        if (!this.cleanup) {
          this.cleanup = sync(this.$root, this.$refs.select);
        }

        if (this.searchable) {
          setTimeout(() => this.$refs.search.focus(), 100);
        }
      }
    });

    this.$watch('model', (value, old) => {
      if (this.internal) {
        this.internal = false;
        return;
      }

      if (!value || value === old) {
        return;
      }

      if (this.multiple) {
        this.selecteds = this.dimensional ?
            this.options.filter((option) => value?.includes(option[this.selectable.value])) :
            this.options.filter((option) => value?.includes(option));
      } else {
        this.selecteds = this.dimensional ?
            this.options.find((option) => value.toString() === option[this.selectable.value].toString()) :
            this.options.find((option) => value.toString() === option.toString());

        this.selecteds = this.dimensional ?
            [this.selecteds] :
            this.selecteds;

        this.placeholder = this.dimensional ?
            this.selecteds[0]?.[this.selectable.label] ?? placeholder :
            (!this.empty ? this.selecteds : placeholder);
      }
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

      this.loading = true;

      if (!value) {
        this.search = '';

        if (this.cleanup) {
          this.cleanup();
          this.cleanup = null;
        }

        return;
      }

      if (value) {
        if (!this.cleanup) {
          this.cleanup = sync(this.$root, this.$refs.select);
        }

        setTimeout(() => this.$refs.search.focus(), 100);
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

      this.selecteds = this.options.filter((option) => {
        return this.multiple ?
            this.model.includes(option[this.selectable.value]) :
            this.model === option[this.selectable.value];
      });

      if (!this.multiple) {
        this.placeholder = this.selecteds[0][this.selectable.label] ?? placeholder;
      }
    }

    this.$watch('model', (value, old) => {
      if (value === old || this.internal) {
        this.internal = false;
        return;
      }

      if (this.multiple) {
        this.selecteds = this.options.filter((option) => {
          return value?.includes(option[this.selectable.value]);
        });
      } else {
        this.selecteds = this.options.filter((option) => {
          return value?.toString() === option[this.selectable.value].toString();
        });

        if (this.quantity > 0) {
          this.placeholder = this.selecteds[0][this.selectable.label] ?? placeholder;
        }
      }
    });
  },
  /** @returns {Promise<void>} */
  async sendRequest() {
    this.response = [];

    // eslint-disable-next-line max-len
    const {url, init} = body(this.request, this.search, this.model ? (this.model.constructor === Array ? this.model : [this.model]) : []);

    try {
      const response = await fetch(url, init);

      const data = await response.json();

      this.response = data.map((option) => ({
        ...option,
        [this.selectable.label]: option[this.selectable.label].toString(),
      }));
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
      this.selecteds = !this.empty ?
                [...this.selecteds, option] :
                [option];

      this.model = this.dimensional ?
                this.selecteds.map((selected) => selected[this.selectable.value]) :
                this.selecteds;

      this.show = false;
      this.search = '';
    } else {
      this.selecteds = !this.common && !this.empty ?
        [...this.selecteds, option] :
        [option];

      if (this.dimensional) {
        this.model = option[this.selectable.value];
        this.placeholder = option[this.selectable.label];
      } else {
        this.model = option;
        this.placeholder = option;
      }
    }

    this.show = this.quantity === this.options.length ? false : this.multiple;
    this.search = '';
  },
  selected(option) {
    if (this.empty) return false;

    return this.multiple ?
      this.selecteds.some((selected) => JSON.stringify(selected) === JSON.stringify(option)) :
      JSON.stringify(this.selecteds[0] ?? this.selecteds) === JSON.stringify(option);
  },
  /**
   * @param selected {Object|null}
   * @returns {void}
   */
  clear(selected = null) {
    if (selected) {
      if (this.multiple) {
        this.selecteds = this.dimensional ?
                    this.selecteds.filter((option) => option[this.selectable.value] !== selected[this.selectable.value]) :
                    this.selecteds.filter((option) => option !== selected);

        this.model = this.dimensional ?
                    this.selecteds.map((selected) => selected[this.selectable.value]) :
                    this.selecteds;
      } else {
        this.selecteds = [];
      }

      if (this.quantity > 0) {
        return;
      }

      this.clear();
    }

    this.model = null;
    this.placeholder = placeholder;
    this.selecteds = [];
    this.search = '';
    this.show = false;
  },
  /** @returns {Boolean} */
  get quantity() {
    return this.selecteds?.length;
  },
  /** @returns {Boolean} */
  get empty() {
    return !this.selecteds || this.selecteds.length === 0;
  },
  /** @returns {Array} */
  get options() {
    const availables = this.common ? options : this.response;

    if (this.search === '') {
      return availables;
    }

    const search = this.search.toLowerCase();

    return availables.filter((option) => {
      return dimensional ?
          option[selectable.label].toString().toLowerCase().indexOf(search) !== -1 :
          option.toString().toLowerCase().indexOf(search) !== -1;
    });
  },
});
