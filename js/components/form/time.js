import {warning, wireChange} from '../../helpers';
import dayjs from 'dayjs';

export default (model, full, times, livewire, property, value, change = null) => ({
  model: model,
  show: false,
  hours: '00',
  minutes: '00',
  interval: 'AM',
  range: {
    hour: {
      min: times.hour.min,
      max: times.hour.max,
    },
    minute: {
      min: times.minute.min,
      max: times.minute.max,
    },
  },
  livewire: livewire,
  property: property,
  value: value,
  empty: false,
  init() {
    this.model ??= this.value;
    this.empty = this.model === null;
    this.hours = full ? '00' : '01';

    if (!full && this.model && !/(AM|PM)/.test(this.model ?? this.value)) {
      warning('The time format is not complete. Please, include the interval (AM/PM).');
    }

    if (this.model) this.hydrate();

    this.$watch('model', () => this.hydrate());

    this.sync();
  },
  /**
   * Hydrate the need stuff in the bootstrap.
   *
   * @return {void}
   */
  hydrate() {
    if (!this.model) return;

    const [time, interval] = this.model?.split(' ');
    const [hours, minutes] = time?.split(':');

    this.hours = hours;
    this.minutes = minutes;
    this.interval = interval ?? null;
  },
  /**
   * Change the hour and minute.
   *
   * @param {Event} event
   * @param {String} type
   * @return {void}
   */
  change(event, type) {
    const change = {
      hours: () => {
        let value = parseInt(event.target.value);

        // eslint-disable-next-line max-len
        value = this.range.hour.min && value < this.range.hour.min ? this.range.hour.min : (this.range.hour.max && value > this.range.hour.max ? this.range.hour.max : value);

        this.hours = value;

        this.$el.dispatchEvent(new CustomEvent('hour', {detail: {hour: this.formatted.hours}}));
      },
      minutes: () => {
        let value = parseInt(event.target.value);

        // eslint-disable-next-line max-len
        value = this.range.minute.min && value < this.range.minute.min ? this.range.minute.min : (this.range.minute.max && value > this.range.minute.max ? this.range.minute.max : value);

        this.minutes = value;

        this.$el.dispatchEvent(new CustomEvent('minute', {detail: {minute: this.formatted.minutes}}));
      },
    };

    change[type]();
    this.empty = false;

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

    this.show = this.empty = false;

    this.sync();
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

    if (!this.empty) this.$refs.input.value = this.model = value;

    wireChange(change, this.model);

    if (this.livewire || this.empty) return;

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

    this.$refs.format.dispatchEvent(new CustomEvent('interval', {detail: {interval: this.interval}}));

    this.sync();

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
