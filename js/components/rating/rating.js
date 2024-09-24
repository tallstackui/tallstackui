export default (model, quantity) => ({
    rate: model,
    quantity: quantity,
    evaluate(method, evaluate) {
        this.rate = evaluate;

        this.$el.dispatchEvent(new CustomEvent('evaluate', {detail: {evaluate: { method, rate: this.rate }}}));

        this.$wire.call(method, this.rate);
    }
});
