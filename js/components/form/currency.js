export default (
    model,
    decimals,
    precision,
    clearable,
    mutate,
    locale
) => ({
    model: model,
    input: '',
    decimals: decimals,
    precision: precision,
    clearable: clearable,
    mutate: mutate,
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
        let current = value ?? this.input;

        if (!current && current !== 0) return;

        let number;

        if (typeof current === 'number') {
            number = current;
        } else {
            const digits = String(current).replace(/\D/g, '');

            number = parseFloat(digits) / 100;
        }

        this.input = new Intl.NumberFormat(this.locale, {
            minimumFractionDigits: this.decimals,
            maximumFractionDigits: this.precision,
        }).format(number);
    },
    /**
     * Sync the input value with the model.
     *
     * @returns {void}
     */
    sync() {
        this.$nextTick(() => this.model = this.mutate ? this.input : this.input.replace(/\D/g, ''));
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
