export default (model, ids) => ({
    model: model,
    ids: ids,
    /**
     * Check if all rows are selected
     *
     * @returns {Boolean}
     */
    fully() {
        return this.ids.every(id => this.model.includes(id));
    },
    /**
     * Select a row
     *
     * @param {Boolean} checked
     * @param {*} content
     */
    select(checked, content) {
        this.$dispatch('row-model', { row: content, selected: checked });

        this.$nextTick(() => {
            return this.fully()
                ? this.$refs.checkbox.checked = true
                : this.$refs.checkbox.checked = false
        })
    },
    /**
     * Select all rows
     *
     * @param {Boolean} checked
     */
    all(checked = true) {
        return checked ? this.push() : this.remove()
    },
    /**
     * Push selected rows
     *
     * @returns {void}
     */
    push() {
        this.model.push(...this.ids.filter(i => !this.model.includes(i)))
    },
    /**
     * Remove selected rows
     *
     * @returns {void}
     */
    remove() {
        this.model =  this.model.filter(i => !this.ids.includes(i) )
    },
});
