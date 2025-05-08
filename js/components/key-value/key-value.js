export default (
    model,
    id,
    limit,
    addable,
    deleteMethod
) => ({
    model: model,
    rows: [],
    component: null,
    init() {
        this.component = Livewire.find(id).__instance;

        this.$nextTick(() => {
            this.model = this.model.map((row) => ({
                index: Math.random().toString(36).substring(2, 12),
                key: row.key,
                value: row.value,
            }))

            this.rows = this.model
        });
    },
    add() {
        if (limit && this.rows.length >= limit) {
            return
        }

        this.rows.push({
            index: Math.random().toString(36).substring(2, 12),
            key: '',
            value: '',
        })

        this.$el.dispatchEvent(new CustomEvent('add', {
            detail: {
                rows: this.rows,
            },
        }))

        this.sync()
    },
    remove(index) {
        const rows = this.rows;

        this.rows = this.rows.filter((_, i) => i !== index)

        this.$el.dispatchEvent(new CustomEvent('remove', {
            detail: {
                rows: this.rows,
            },
        }))

        if (this.component && deleteMethod) {
            this.component.$wire.call(deleteMethod, index, rows);
        }

        this.sync()
    },
    sync() {
        this.rows = this.rows.map(row => ({
            key: row.key,
            value: row.value,
        }))

        this.model = this.rows;
    },
    get addable () {
        const value = Number(limit);

        return ! addable || limit && this.rows.length < value;
    }
})
