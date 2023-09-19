import { warning, error } from "../../helpers";
import { options as filtered, body } from "./helpers";
import axios from "../../axios";

export default (
    request,
    model = null,
    selectable = {},
    multiple = false,
    placeholder = 'Select an option'
) => ({
    show : false,
    request : request,
    model : model,
    selecteds : [],
    search : '',
    selectable : selectable,
    multiple : multiple,
    placeholder : placeholder,
    response : [],
    loading : true,
    async init() {
        if (this.multiple && (this.model !== null && this.model.constructor !== Array)) {
            return warning('The wire:model must be an array');
        }

        this.$watch('show', async (value) => {
            this.loading = true;

            if (!value) {
                return;
            }

            await this.send();
        });

        this.$watch('search', async (value) => {
            if (value === '') {
                this.loading = false;

                return;
            }

            this.loading = true;
            await this.send();
        });

        if (this.model) {
            await this.send();

            this.selecteds = this.options.filter(option => {
                    return this.multiple
                        ? this.model.includes(option[this.selectable.value])
                        : this.model === option[this.selectable.value];
                });

            if (!this.multiple) {
                this.placeholder = this.selecteds[0][this.selectable.label];
            }
        }
    },
    async send() {
        this.response = [];

        const request = body(this.request, this.search)

        try {
            const response = await axios(request);

            this.response = response.data.map(option => {
                return {
                    ...option,
                    [this.selectable.label]: option[this.selectable.label].toString()
                }
            });

            this.loading = false;
        } catch (e) {
            error(e.message);
        }
    },
    select (option) {
        if (this.selected(option)) {
            this.clear(option);
            return;
        }

        this.selecteds = this.multiple
            ? [...this.selecteds, option]
            : [option];

        this.model = this.multiple
            ? this.selecteds.map(selected => selected[this.selectable.value])
            : option[this.selectable.value];

        if (!this.multiple) {
            this.placeholder = option[this.selectable.label];
        }

        this.search = '';
        this.show = this.multiple;
    },
    selected (option) {
        if (this.empty) return false;

        return this.multiple
            ? this.selecteds.some(selected => JSON.stringify(selected) === JSON.stringify(option))
            : JSON.stringify(this.selecteds[0]) === JSON.stringify(option);
    },
    clear (selected = null) {
        if (selected) {
            this.selecteds = this.selecteds.filter(option => option[this.selectable.value] !== selected[this.selectable.value]);
            this.model = this.selecteds.map(selected => selected[this.selectable.value]);

            if (this.quantity === 0) {
                this.placeholder = placeholder;
            }

            return;
        }

        this.model = this.multiple ? [] : null;
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

        return filtered(this.search, true, this.selectable, this.response);
    }
});
