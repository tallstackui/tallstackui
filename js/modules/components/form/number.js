import {warning} from '../../helpers';

export default (value, min, max, delay, disabled) => ({
  value: value,
  min: min,
  max: max,
  atMax: false,
  atMin: false,
  interval: null,
  delay: delay,
  disabled: disabled,
  init() {
    this.value = parseInt(this.value ?? 0);
    this.min = parseInt(this.min);
    this.max = parseInt(this.max);

    if (this.min > this.max || this.value > this.max) {
      warning('The min value of the number input must be less than the max value.');

      return;
    }

    if (this.max < this.min || this.value < this.min) {
      warning('The max value of the number input must be greater than the min value.');

      return;
    }

    if (this.value >= this.max) {
      this.atMax = true;
    } else if (this.value <= this.min) {
      this.atMin = true;
    } else if (this.value === 0) {
      this.atMin = true;
    }

    this.$watch('value', (value) => {
      if (isNaN(value) || value === 0 || !value) {
        return;
      }

      this.value = parseInt(value);

      this.$refs.input.value = value;
    });
  },
  increment() {
    if (this.atMax) {
      return;
    }

    if ((this.value+1) >= this.max) {
      this.atMax = true;
    }

    this.$refs.input.stepUp();

    this.atMin = false;
    this.value = parseInt(this.$refs.input.value);

    this.$refs.input.dispatchEvent(new Event('input'));
  },
  decrement() {
    if (this.atMin) {
      return;
    }

    if ((this.value-1) <= this.min) {
      this.atMin = true;
    }

    if (this.value <= 0) {
      this.atMin = true;
      this.$refs.input.value = 0;
      return;
    }

    this.$refs.input.stepDown();

    this.atMax = false;
    this.value = parseInt(this.$refs.input.value);

    this.$refs.input.dispatchEvent(new Event('input'));
  },
  validate() {
    const current = this.$refs.input.value;

    if (current < this.min) {
      this.$refs.input.value = null;
    }

    if (current > this.max) {
      this.$refs.input.value = null;
    }
  },
});
