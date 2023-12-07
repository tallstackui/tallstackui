import {warning} from '../../helpers';

export default (value, min, max, delay) => ({
  value: value,
  min: min,
  max: max,
  interval: null,
  delay: delay,
  init() {
    if (this.defined) {
      if (this.limiters) {
        if (this.min > this.max || this.value > this.max) {
          warning('The min value of the number input must be less than the max value.');

          return;
        }

        if (this.max < this.min || this.value < this.min) {
          warning('The max value of the number input must be greater than the min value.');

          return;
        }
      }

      this.disableMinus = this.defined && this.atMinus;
      this.disablePlus = this.defined && this.atPlus;
    }

    this.$watch('value', (value) => {
      if (isNaN(value) || !value) {
        return;
      }

      this.$refs.input.value = this.value = value;
    });
  },
  increment() {
    if (this.limiters) {
      if (this.defined && this.atPlus) {
        return;
      }

      this.disablePlus = this.defined && this.atPlus;
      this.value ||= this.min;
      this.$refs.input.value ||= this.min;
    }

    this.$refs.input.stepUp();
    this.$refs.input.dispatchEvent(new Event('input'));
    this.update();
  },
  decrement() {
    if (this.limiters) {
      if (this.defined && this.atMinus) {
        return;
      }

      this.disableMinus = this.defined && this.atMinus;
      this.value ||= this.min;
      this.$refs.input.value ||= this.min;
    }

    this.$refs.input.stepDown();
    this.$refs.input.dispatchEvent(new Event('input'));
    this.update();
  },
  update() {
    this.value = this.$refs.input.value;

    if (!this.limiters) {
      return;
    }

    this.disableMinus = this.defined && this.atMinus;
    this.disablePlus = this.defined && this.atPlus;
  },
  validate() {
    const value = this.$refs.input.value;

    if (this.min && value < this.min) {
      this.$refs.input.value, this.value = null;
    }

    if (this.max && value > this.max) {
      this.$refs.input.value, this.value = null;
    }

    this.disablePlus = this.atPlus;
    this.disableMinus = this.atMinus;
  },
  get defined() {
    return Boolean(this.value);
  },
  get atMinus() {
    return this.min && (this.value <= this.min);
  },
  get atPlus() {
    return this.max && (this.value >= this.max);
  },
  set disableMinus(disabled) {
    this.$refs.minus.disabled = disabled;
  },
  set disablePlus(disabled) {
    this.$refs.plus.disabled = disabled;
  },
  get limiters() {
    return this.min !== null || this.max !== null;
  },
});
