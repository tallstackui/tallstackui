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
    selecteds : [],
    search : '',
    searchable : searchable,
    dimensional : dimensional,
    selectable : selectable,
    placeholder : placeholder,
    init() {
        if (this.model !== null && this.model.constructor !== Array) {
            return warning('The wire:model must be an array');
        }

        this.selecteds = this.dimensional
            ? this.options.filter(option => this.model?.includes(option[this.selectable.value]))
            : this.options.filter(option => this.model?.includes(option));

        this.$watch('show', value => {
            if (!value && this.search.length > 0) {
                this.search = '';
            }
        });
    },
    select (option) {
        if (this.selected(option)) {
            this.clear(option);
            return;
        }

        this.selecteds = [...this.selecteds, option];

        if (this.dimensional) {
            this.model = this.selecteds.map(selected => selected[this.selectable.value])
            this.placeholder = option[this.selectable.label];
        } else {
            this.model = this.selecteds.construct === Array
                ? [...this.selecteds, option]
                : this.selecteds;
            this.placeholder = option;
        }

        this.search = '';
    },
    selected (option) {
        if (this.empty) return false;

        if (!this.dimensional) {
            return this.selecteds.includes(option);
        }

        return selecteds(this.selecteds, option);
    },
    clear (selected = null) {
        if (selected) {
            this.selecteds = this.selecteds.filter(option => {
                return this.dimensional
                    ? option[this.selectable.value] !== selected[this.selectable.value]
                    : option !== selected;
            });

            this.model = this.dimensional
                ? this.selecteds.map(selected => selected[this.selectable.value])
                : this.selecteds;

            if (this.quantity === 0) {
                this.placeholder = placeholder;
            }

            return;
        }

        this.model = this.dimensional ? [] : null;
        this.selecteds = [];
        this.placeholder = placeholder;
        this.search = '';
        this.show = false;
    },
    get quantity() {
        return this.selecteds?.length;
    },
    get empty () {
        return !this.selecteds || this.selecteds.length === 0;
    },
    get options () {
        if (this.search === '') {
            return options;
        }

        return filtered(this.search, this.dimensional, this.selectable, options);
    }
});
