import {warning} from '../../helpers';

export default (value, min, max, delay, disabled) => ({
  value: value,
  min: min,
  max: max,
  interval: null,
  delay: delay,
  disabled: disabled,
  init() {
    if (this.limiters && this.defined) {
      if (this.max && this.min && (this.min > this.max || this.value > this.max)) {
        warning('The min value of the number input must be less than the max value.');

        return;
      }

      if (this.min && this.max && (this.max < this.min || this.value < this.min)) {
        warning('The max value of the number input must be greater than the min value.');

        return;
      }

      this.disablePlus = this.max && (this.value >= this.max);
      this.disableMinus = this.min && (this.value <= this.min);
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
      if (this.atPlus) {
        return;
      }

      this.disablePlus = this.defined && this.max && (this.value >= this.max);
      this.value ||= this.max;
      this.$refs.input.value ||= this.max;
    }

    this.$refs.input.stepUp();
    this.$refs.input.dispatchEvent(new Event('input'));
    this.update();
  },
  decrement() {
    if (this.limiters) {
      if (this.atMinus) {
        return;
      }

      this.disableMinus = this.defined && this.min && (this.value <= this.min);
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

    this.disableMinus = this.value && this.min && (this.value <= this.min);
    this.disablePlus = this.value && this.max && (this.value >= this.max);
  },
  validate() {
    const value = parseInt(this.$refs.input.value);

    if (!this.limiters || (value >= parseInt(this.min) || value <= parseInt(this.max))) {
      return;
    }

    this.value = null;
    this.$refs.input.value = null;
    this.disablePlus = false;
    this.disableMinus = false;
  },
  get defined() {
    return Boolean(this.value);
  },
  get atMinus() {
    return this.value && this.min && (this.value <= this.min);
  },
  get atPlus() {
    return this.value && this.max && (this.value >= this.max);
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
