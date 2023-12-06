import {warning} from '../../helpers';

export default (value, min = null, max = null, delay, disabled) => ({
  value: value,
  min: min,
  max: max,
  atMax: false,
  atMin: false,
  interval: null,
  delay: delay,
  disabled: disabled,
  init() {
    this.value = this.value ?? 0;
    if ((this.max !== null && this.max !== null) && (this.min > this.max || this.value > this.max)) {
      warning('The min value of the number input must be less than the max value.');

      return;
    }

    if ((this.max !== null && this.max !== null) && (this.max < this.min || this.value < this.min)) {
      warning('The max value of the number input must be greater than the min value.');

      return;
    }

    this.$watch('value', (value) => {
      if (isNaN(value) || value === 0 || !value) {
        return;
      }

      this.value = value;
      this.$refs.input.value = value;
    });
  },
  increment() {
    const inputValue = +this.$refs.input.value;
    if (!isNaN(inputValue) && (this.max === null || inputValue < this.max)) {
      this.$refs.input.stepUp();
      this.update();
    }
  },
  decrement() {
    const inputValue = +this.$refs.input.value;
    if (!isNaN(inputValue) && (this.min === null || inputValue > this.min)) {
      this.$refs.input.stepDown();
      this.update();
    }
  },
  update() {
    const inputValue = +this.$refs.input.value;
    this.value = inputValue;

    if (this.min !== null) {
      this.$refs.minus.disabled = inputValue === this.min;
    }

    if (this.max !== null) {
      this.$refs.plus.disabled = inputValue === this.max;
    }
  },
  validate() {
    const current = this.$refs.input.value;

    if (this.min !== null && current < this.min) {
      this.$refs.input.value, this.value = null;
    }

    if (this.max !== null && current > this.max) {
      this.$refs.input.value, this.value = null;
    }
  },
});
