export default (model, ids, selectable) => ({
    selection: model,
    pageIds: ids,
    isSelectable: selectable,
    isPageFullSelected() {
        return [...this.selection]
            .sort((a, b) => b - a)
            .toString()
            .includes([...this.pageIds].sort((a, b) => b - a).toString())
    },
    toggleCheck(checked, content) {
        this.$dispatch('row-selection', { row: content, selected: checked });
        this.handleCheckAll()
    },
    toggleCheckAll(checked) {
        checked ? this.pushIds() : this.removeIds()
    },
    toggleExpand(key) {
        this.selection.includes(key)
            ? this.selection = this.selection.filter(i => i !== key)
            : this.selection.push(key)
    },
    pushIds() {
        this.selection.push(...this.pageIds.filter(i => !this.selection.includes(i)))
    },
    removeIds() {
        this.selection =  this.selection.filter(i => !this.pageIds.includes(i) )
    },
    handleCheckAll() {
        this.$nextTick(() => {
            this.isPageFullSelected()
                ? this.$refs.mainCheckbox.checked = true
                : this.$refs.mainCheckbox.checked = false
        })
    }
});
