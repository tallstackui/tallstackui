export default (model, ids, selectable) => ({
    model: model,
    ids: ids,
    init() {
        if (selectable) this.checked();
    },
    /**
     * Check if all rows are selected
     *
     * @returns {Boolean}
     */
    fully() {
        return this.ids.every(id => this.model.includes(id));
    },
    /**
     * Mark the "main" checkbox as checked.
     */
    checked() {
        this.$nextTick(() => {
            return this.fully()
                ? this.$refs.checkbox.checked = true
                : this.$refs.checkbox.checked = false
        });
    },
    /**
     * Select a row
     *
     * @param {Boolean} checked
     * @param {*} content
     */
    select(checked, content) {
        this.checked();

        this.$dispatch('select', { row: content });
    },
    /**
     * Select all rows
     *
     * @param {Boolean} checked
     * @param {Array} ids
     * @returns {void}
     */
    all(checked = true, ids) {
        // We need to receive Blade ids for situations where we are dealing
        // with pagination and data changes from page to page. With this
        // approach, we ensure that selecting all from the current page works correctly.
        this.ids = ids;

        return checked ? this.push() : this.remove()
    },
    /**
     * Push selected rows
     *
     * @returns {void}
     */
    push() {
        this.model.push(...this.ids.filter(index => !this.model.includes(index)))
    },
    /**
     * Remove selected rows
     *
     * @returns {void}
     */
    remove() {
        this.model =  this.model.filter(index => !this.ids.includes(index) )
    },
});
