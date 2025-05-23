export default (
    model,
    decimals,
    precision,
    clearable,
    locale
) => ({
    model: model,
    input: '',
    decimals: decimals,
    precision: precision,
    clearable: clearable,
    locale: locale,
    init() {
        if (this.model) {
            this.input = this.model;

            this.$nextTick(() => this.format());
        }

        this.$watch('input', (value) => this.format(value));
    },
    /**
     * Format the input value.
     *
     * @returns {void}
     */
    format(value = null) {
        let current = value ? value.replace(/\D/g, '') : this.input;

        if (current === '') {
            return;
        }

        current = parseFloat(current) / 100;

        this.input = new Intl.NumberFormat(this.locale, {
            minimumFractionDigits: this.decimals,
            maximumFractionDigits: this.precision,
        }).format(current);
    },
    /**
     * Sync the input value with the model.
     *
     * @returns {void}
     */
    sync() {
        this.$nextTick(() => (this.model = this.input.replace(/\D/g, '')));
    },
    /**
     * Clear the input.
     *
     * @returns {void}
     */
    clear() {
        this.input = '';

        this.model = null;
    },
});
