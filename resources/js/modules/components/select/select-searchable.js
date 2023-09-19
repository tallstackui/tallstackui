import { warning } from "../../helpers";
import { options as filtered, selected as selecteds } from "./helpers";
import axios from "axios";

export default (
    request,
    model = null,
    dimensional = false,
    selectable = {},
    multiple = false,
    placeholder = 'Select an option'
) => ({
    show : false,
    request : request,
    model : model,
    selecteds : [],
    search : '',
    dimensional : dimensional,
    selectable : selectable,
    multiple : multiple,
    placeholder : placeholder,
    response : [],
    loading : false,
    init() {
        if (this.multiple && this.model.constructor !== Array) {
            return warning('The wire:model must be an array');
        }

        //show element loading when request is sent
        this.loading = true;

        this.$watch('show', async (value) => {
            if (!value) {
                return;
            }

            this.response = [];
            this.loading = true;

            const response = await this.send();

            this.response = this.dimensional
                ? response.map(option => {
                    return {
                        ...option,
                        [this.selectable.label]: option[this.selectable.label].toString()
                    }
                })
                : response.map(option => option.toString());

            this.loading = false;
        });

        this.$watch('search', async () => {
            this.loading = true;

            this.response = [];
            const response = await this.send();

            this.response = this.dimensional
                ? response.map(option => {
                    return {
                        ...option,
                        [this.selectable.label]: option[this.selectable.label].toString()
                    }
                })
                : response.map(option => option.toString());

            this.loading = false;
        });
    },
    async send() {
        return (await axios.get(this.request)).data
    },
    select (option) {
        if (this.selected(option)) {
            this.clear(option);
            return;
        }

        this.selecteds = [...this.selecteds, option];

        if (this.dimensional) {
            this.model = this.multiple
                ? this.selecteds.map(selected => selected[this.selectable.value])
                : option[this.selectable.value];
            this.placeholder = option[this.selectable.label];
        } else {
            this.model = this.selecteds.construct === Array && this.multiple
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
    get options() {
        if (this.search === '') {
            return this.response;
        }

        return filtered(this.search, this.dimensional, this.selectable, this.response);
    }
});
