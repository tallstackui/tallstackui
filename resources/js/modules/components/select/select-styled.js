import { warning } from "../../helpers";
import { options as filtered, selected as selecteds } from "./helpers";

export default (
    model = null,
    searchable = false,
    dimensional = false,
    selectable = {},
    options = [],
    placeholder = 'Select an option'
) => ({
    show : false,
    model : model,
    selecteds : null,
    search : '',
    searchable : searchable,
    dimensional : dimensional,
    selectable : selectable,
    placeholder : '',
    init() {
        this.placeholder = placeholder;

        if (this.model !== null && this.model.constructor === Array) {
            return warning('The wire:model can\'t be an array.');
        }

        if (this.model && this.options.length > 0) {
            this.selecteds = this.dimensional
                ? this.options.filter(option => {
                    return this.model.constructor === Array
                        ? this.model.includes(option[this.selectable.value])
                        : this.model === option[this.selectable.value];
                })
                : this.options.find(option => option === this.model);

            if (this.selecteds) {
                this.placeholder = this.dimensional
                    ? this.selecteds[0][this.selectable.label]
                    : this.selecteds;
            }
        }
    },
    select (option) {
        if (this.selecteds && this.selected(option)) {
            this.clear();
            return;
        }

        if (this.selected(option)) {
            this.clear(option);
            return;
        }

        this.selecteds = this.dimensional ? [option] : option;

        if (this.dimensional) {
            this.model = option[this.selectable.value];
            this.placeholder = option[this.selectable.label];
        } else {
            this.model = option;
            this.placeholder = option;
        }

        this.show = false;
        this.search = '';
    },
    selected (option) {
        if (!this.selecteds) return false;

        if (!this.dimensional) {
            return this.selecteds === option;
        }

        return selecteds(this.selecteds, option);
    },
    clear (selected = null) {
        if (selected) {
            this.selecteds = this.selecteds.filter(option => option !== selected);
            this.model = this.selecteds.map(selected => selected[this.selectable.value]);

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
    get empty () {
        return !this.selecteds || this.selecteds?.length === 0;
    },
    get options () {
        if (this.search === '') {
            return options;
        }

        return filtered(this.search, this.dimensional, this.selectable, options);
    }
});
