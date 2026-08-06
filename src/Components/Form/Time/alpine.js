import { wireChange } from '../../../../js/helpers';
import dayjs from 'dayjs';

// Pixels of vertical drag required to move one step.
const sensitivity = 10;

// Accumulated wheel pixels required to move one step,
// matching the delta of one discrete mouse wheel tick.
const threshold = 100;

export default (
  model,
  full,
  times,
  required,
  livewire,
  property,
  value,
  disables = [],
  change = null
) => ({
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
  disables: disables,
  dragging: null,
  scrolling: null,
  init() {
    this.model ??= this.value ?? (required ? dayjs().format('HH:mm A') : null);
    this.empty = this.model === null;
    this.hours = full ? '00' : '01';

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

    const [time, interval] = this.model.split(' ');
    const [hours, minutes] = time ? time.split(':') : ['00', '00'];

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

        value =
          this.range.hour.min && value < this.range.hour.min
            ? this.range.hour.min
            : this.range.hour.max && value > this.range.hour.max
              ? this.range.hour.max
              : value;

        this.hours = value;

        this.$el.dispatchEvent(new CustomEvent('hour', { detail: { hour: this.formatted.hours } }));
      },
      minutes: () => {
        let value = parseInt(event.target.value);

        value =
          this.range.minute.min && value < this.range.minute.min
            ? this.range.minute.min
            : this.range.minute.max && value > this.range.minute.max
              ? this.range.minute.max
              : value;

        this.minutes = value;

        this.$el.dispatchEvent(
          new CustomEvent('minute', { detail: { minute: this.formatted.minutes } })
        );
      },
    };

    change[type]();
    this.empty = false;

    this.sync();
  },
  /**
   * Move the hour or minute one step in the given direction,
   * reusing the range input so min, max and step are respected.
   *
   * @param {String} type
   * @param {Number} direction
   * @return {void}
   */
  adjust(type, direction) {
    const range = this.$refs[type === 'hours' ? 'rangeHours' : 'rangeMinutes'];

    if (direction > 0) {
      range.stepUp();
    } else {
      range.stepDown();
    }

    range.dispatchEvent(new Event('input', { bubbles: true }));
    range.dispatchEvent(new Event('change', { bubbles: true }));
  },
  /**
   * Adjust the time through the mouse wheel. Discrete wheels move one
   * step per tick, while trackpads emit a stream of small pixel deltas
   * that are accumulated to avoid moving the time too fast.
   *
   * @param {WheelEvent} event
   * @param {String} type
   * @return {void}
   */
  scroll(event, type) {
    if (this.scrolling?.type !== type) {
      this.scrolling = { type: type, amount: 0 };
    }

    if (event.deltaMode !== WheelEvent.DOM_DELTA_PIXEL || Math.abs(event.deltaY) >= threshold) {
      this.scrolling.amount = 0;

      this.adjust(type, event.deltaY < 0 ? 1 : -1);

      return;
    }

    this.scrolling.amount += event.deltaY;

    const steps = Math.trunc(this.scrolling.amount / threshold);

    if (steps === 0) {
      return;
    }

    for (let index = 0; index < Math.abs(steps); index++) {
      this.adjust(type, steps < 0 ? 1 : -1);
    }

    this.scrolling.amount -= steps * threshold;
  },
  /**
   * Start dragging the hour or minute numbers.
   *
   * @param {PointerEvent} event
   * @param {String} type
   * @return {void}
   */
  grab(event, type) {
    this.dragging = { type: type, origin: event.clientY };

    event.target.setPointerCapture(event.pointerId);
  },
  /**
   * Adjust the time while the numbers are dragged up or down.
   *
   * @param {PointerEvent} event
   * @return {void}
   */
  drag(event) {
    if (!this.dragging) {
      return;
    }

    const steps = Math.trunc((this.dragging.origin - event.clientY) / sensitivity);

    if (steps === 0) {
      return;
    }

    for (let index = 0; index < Math.abs(steps); index++) {
      this.adjust(this.dragging.type, steps > 0 ? 1 : -1);
    }

    this.dragging.origin -= steps * sensitivity;
  },
  /**
   * Stop dragging the numbers.
   *
   * @return {void}
   */
  release() {
    this.dragging = null;
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

    // The 12-hour slider runs from 1 to 12, so the 24-hour clock reading has to
    // be folded into it: 13 becomes 1 PM and 0 becomes 12 AM.
    this.hours = full ? hours : hours % 12 || 12;
    this.minutes = minutes;

    this.$el.dispatchEvent(
      new CustomEvent('current', {
        detail: { time: { hour: this.hours, minute: minutes, interval: this.interval } },
      })
    );

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

    if (this.empty) return;

    this.input = value;
  },
  /**
   * Change the interval.
   *
   * @param {String} interval
   * @return {void}
   */
  select(interval) {
    this.interval = interval.toUpperCase();

    this.$refs.format.dispatchEvent(
      new CustomEvent('interval', { detail: { interval: this.interval } })
    );

    this.sync();

    this.show = false;
  },
  /**
   * Reset all properties.
   *
   * @return {void}
   */
  clear() {
    if (required) return;

    const model = this.model;

    this.hours = '00';
    this.minutes = '00';
    this.interval = 'AM';

    this.input = this.$refs.input.value = this.model = null;

    this.$el.dispatchEvent(new CustomEvent('clear', { detail: { time: model } }));
  },
  /**
   * Set the input value.
   *
   * @param {String} value
   * @return {void}
   */
  set input(value) {
    const input = document.getElementsByName(this.property)[0];

    if (!input) return;

    input.value = this.value = value;
  },
  /**
   * Get the formatted time.
   *
   * @return {Object}
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
