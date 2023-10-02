import {warning} from '../../helpers';
import {options as filtered} from './helpers';

export default (
    model = null,
    searchable = false,
    multiple = false,
    dimensional = false,
    selectable = {},
    options = [],
    placeholder = 'Select an option',
) => ({
  show: false,
  model: model,
  selecteds: null,
  search: '',
  searchable: searchable,
  multiple: multiple,
  dimensional: dimensional,
  selectable: selectable,
  loading: false,
  placeholder: '',
  init() {
    this.placeholder = placeholder;

    if (this.multiple && (this.model !== null && this.model.constructor !== Array)) {
      return warning('The wire:model must be an array');
    }

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
      if (!value || !this.searchable) {
        return;
      }

      setTimeout(() => this.$refs.search.focus(), 100);
    });
  },
  select(option) {
    if (this.selected(option)) {
      this.clear(option);

      return;
    }

    if (this.multiple) {
      this.selecteds = this.selecteds ?
                [...this.selecteds, option] :
                [option];

      this.model = this.dimensional ?
                this.selecteds.map((selected) => selected[this.selectable.value]) :
                this.selecteds;

      this.show = false;
      this.search = '';
    } else {
      this.selecteds = [option];

      if (this.dimensional) {
        this.model = option[this.selectable.value];
        this.placeholder = option[this.selectable.label];
      } else {
        this.model = option;
        this.placeholder = option;
      }
    }

    this.show = this.multiple;
    this.search = '';
  },
  selected(option) {
    if (this.empty) return false;

    return this.multiple ?
            this.selecteds.some((selected) => JSON.stringify(selected) === JSON.stringify(option)) :
            JSON.stringify(this.selecteds[0] ?? this.selecteds) === JSON.stringify(option);
  },
  clear(selected = null) {
    if (selected) {
      this.selecteds = this.multiple ?
                this.selecteds.filter((option) => option !== selected) :
                [];

      this.model = this.multiple ?
                this.selecteds.map((selected) => selected[this.selectable.value]) :
                null;

      if (this.selecteds.length > 0) {
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
  get quantity() {
    return this.selecteds?.length;
  },
  get empty() {
    return !this.selecteds || this.selecteds.length === 0;
  },
  get options() {
    if (this.search === '') {
      return options;
    }

    return filtered(this.search, this.dimensional, this.selectable, options);
  },
});
