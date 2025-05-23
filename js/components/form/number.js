export default (model, min, max, delay, step) => ({
  model: model,
  min: min,
  max: max,
  interval: null,
  delay: delay,
  init() {
    if (this.defined) {
      this.disableMinus = this.atMinus;
      this.disablePlus = this.atPlus;
    }

    this.$watch('model', (value) => {
      if (isNaN(value) || !value) return;

      this.$refs.input.value = this.model = value;
    });
  },
  /**
   * Increment the value.
   *
   * @return {void}
   */
  increment() {
    const update = (value) => {
      const input = parseFloat(this.$refs.input.step) || 1;
      const count = value / input;

      if (!Number.isInteger(count)) {
        const current = parseFloat(this.$refs.input.value) || 0;
        this.$refs.input.value = (current + value).toFixed(2);
      } else {
        this.$refs.input.stepUp(count);
      }

      this.$refs.input.dispatchEvent(new Event('change'));
      this.update();
    };

    const current = this.model !== null ? parseFloat(this.model) : null;
    const max = this.max;

    if (current !== null && max !== null && current >= max) {
      this.disablePlus = true;

      return;
    }

    if (max !== null) {
      if (current !== null && current + step < max) {
        update(step);

        return;
      }

      if (max > step) {
        update(step);

        return;
      }

      if (max === 0) {
        update(0);

        return;
      }
    }

    update(step);
  },
  /**
   * Decrement the value.
   *
   * @return {void}
   */
  decrement() {
    const update = (value) => {
      const input = parseFloat(this.$refs.input.step) || 1;
      const count = value / input;

      if (!Number.isInteger(count)) {
        const current = parseFloat(this.$refs.input.value) || 0;
        this.$refs.input.value = (current - value).toFixed(2);
      } else {
        this.$refs.input.stepDown(count);
      }

      this.$refs.input.dispatchEvent(new Event('change'));
      this.update();
    };

    const current = this.model !== null ? parseFloat(this.model) : null;
    const min = this.min;

    if (current !== null && min !== null && current <= min) {
      this.disableMinus = true;
      return;
    }

    if (min !== null && current !== null && (current - step) > min) {
      update(step);

      return;
    } else if (min !== null && min < step) {
      if (current !== null) {
        update((current - step) >= 0 ? step : 0);

        return;
      } else if (min === 0) {
        update(0);

        return;
      }
    }

    update(step);
  },
  /**
   * Update the value of the model.
   *
   * @return {void}
   */
  update() {
    this.model = this.$refs.input.value;

    if (this.min !== null) {
      this.disableMinus = this.defined && this.atMinus;
    }

    if (this.max !== null) {
      this.disablePlus = this.defined && this.atPlus;
    }
  },
  /**
   * Performs validations on the input value when blur effect occurs.
   */
  validate() {
    const value = this.$refs.input.value;

    if (this.min !== null && value < this.min) {
      this.$refs.input.value = this.model = null;
    }

    if (this.max !== null && value > this.max) {
      this.$refs.input.value = this.model = null;
    }

    this.disablePlus = this.atPlus;
    this.disableMinus = this.atMinus;
  },
  /**
   * Check if the model is defined.
   *
   * @return {Boolean}
   */
  get defined() {
    return this.model === 0 || Boolean(this.model);
  },
  /**
   * Check if the model is at the minimum value.
   *
   * @return {Boolean}
   */
  get atMinus() {
    return this.min !== null && (this.model <= this.min);
  },
  /**
   * Check if the model is at the maximum value.
   *
   * @return {Boolean}
   */
  get atPlus() {
    return this.max !== null && (this.model >= this.max);
  },
  /**
   * Disable the minus button.
   *
   * @return {void}
   */
  set disableMinus(disabled) {
    this.$refs.minus.disabled = disabled;
  },
  /**
   * Disable the plus button.
   *
   * @return {void}
   */
  set disablePlus(disabled) {
    this.$refs.plus.disabled = disabled;
  },
});
