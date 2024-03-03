import {warning} from '../../helpers';
import dayjs from 'dayjs';

export default (model, full, times, livewire, property, value) => ({
  model: model,
  show: false,
  hours: '0',
  minutes: '0',
  interval: 'AM',
  range: {
    hour: {
      min: times.hour.min,
      max: times.hour.max,
    },
    minutes: {
      min: times.minute.min,
      max: times.minute.max,
    },
  },
  livewire: livewire,
  property: property,
  value: value,
  init() {
    if (!full && (this.model || this.value) && !/(AM|PM)/.test(this.model ?? this.value)) {
      warning('The time format is not complete. Please, include the interval (AM/PM).');
    }

    if (this.model || this.value) this.hydrate();

    this.$watch('hours', (value) => {
      if (this.range.hour.min && value < this.range.hour.min) {
        this.hours = this.range.hour.min;
      }

      if (this.range.hour.max && value > this.range.hour.max) {
        this.hours = this.range.hour.max;
      }

      this.sync();

      this.$el.dispatchEvent(new CustomEvent('hour', {detail: {hour: value}}));
    });

    this.$watch('minutes', (value) => {
      if (this.range.minutes.min && value < this.range.minutes.min) {
        this.minutes = this.range.minutes.min;
      }

      if (this.range.minutes.max && value > this.range.minutes.max) {
        this.minutes = this.range.minutes.max;
      }

      this.sync();

      this.$el.dispatchEvent(new CustomEvent('minute', {detail: {minute: value}}));
    });

    this.$watch('interval', () => this.sync());

    this.$watch('model', () => this.hydrate());
  },
  /**
   * Hydrate the need stuff in the bootstrap.
   *
   * @return {void}
   */
  hydrate() {
    const [time, interval] = (this.model ?? this.value).split(' ');
    const [hours, minutes] = time.split(':');

    this.hours = hours;
    this.minutes = minutes;
    this.interval = interval ?? null;

    this.sync();
  },
  /**
   * Set the current time.
   *
   * @return {void}
   */
  current() {
    const date = dayjs();

    const hours = date.hour();
    const minutes = date.minute();

    if (!full) this.interval = hours >= 12 ? 'PM' : 'AM';

    this.hours = hours;
    this.minutes = minutes;

    this.$el.dispatchEvent(new CustomEvent('current', {detail: {time: {hour: hours, minute: minutes, interval: this.interval}}}));

    this.sync();

    this.show = false;
  },
  /**
   * Sync the input and model.
   *
   * @return {void}
   */
  sync() {
    let value = `${this.formatted.hours}:${this.formatted.minutes}`;

    if (!full && this.interval) {
      value = `${value} ${this.interval}`;
    }

    this.$refs.input.value = value;

    this.model = value;

    if (this.livewire) return;

    const input = document.getElementsByName(this.property)[0];

    if (!input) return;

    input.value = this.value = value;
  },
  /**
   * Change the interval.
   *
   * @param {String} interval
   * @return {void}
   */
  select(interval) {
    this.interval = interval.toUpperCase();

    this.show = false;
  },
  /**
   * Get the formatted time.
   * @return {object}
   */
  get formatted() {
    this.hours = this.hours.toString();
    this.minutes = this.minutes.toString();

    return {
      hours: this.hours.padStart(2, '0'),
      minutes: this.minutes.padStart(2, '0'),
    };
  },
});
