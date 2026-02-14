export default (model, quantity) => ({
  rate: model,
  quantity: quantity,
  /**
   * Set the rating value and dispatch evaluation to the Livewire component.
   *
   * @param {String} method
   * @param {Number} evaluate
   * @return {void}
   */
  evaluate(method, evaluate) {
    this.rate = evaluate;

    this.$el.dispatchEvent(
      new CustomEvent('evaluate', { detail: { evaluate: { method, rate: this.rate } } })
    );

    this.$wire.call(method, this.rate);
  },
});
