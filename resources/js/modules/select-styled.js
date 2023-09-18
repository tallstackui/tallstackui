export default (
    show = false,
    model = null,
    selecteds = null,
    search = '',
    searchable = false,
    dimensional = [],
    selectable = [],
    options = [],
    placeholder = '',
    originalPlaceholder = ''
) => ({
    show : false,
    model : model,
    selecteds : selecteds,
    search : search,
    searchable : searchable,
    dimensional : dimensional,
    selectable : selectable,
    placeholder : placeholder,
    init() {
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

        return this.selecteds.some(selected => {
            const keys   = Object.keys(selected);
            const values = Object.values(selected);

            return keys.every(key => {
                return selected[key] === option[key];
            }) && values.every(value => {
                return selected[value] === option[value];
            });
        });
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
        this.placeholder = originalPlaceholder;
        this.selecteds = [];
        this.search = '';
        this.show = false;
    },
    get quantity() {
        return this.selecteds?.length;
    },
    get empty () {
        return !this.selecteds || this.selecteds?.length === 0;
    },
    get options () {
        const availables = options;

        this.search = this.search.toLowerCase();

        return this.search === ''
            ? availables
            : availables.filter(option => {
                return this.dimensional
                    ? option[this.selectable.label].toString().toLowerCase().indexOf(this.search) !== -1
                    : option.toString().toLowerCase().indexOf(this.search) !== -1;
            });
    }
});
