import {dayjs, error} from '../../helpers';

export default (model, full) => ({
  model: model,
  show: false,
  hours: '0',
  minutes: '0',
  interval: 'AM',
  init() {
    if (!this.dayjs) {
      return error('The dayjs library is not available. Please, review the docs.');
    }

    if (this.model) this.parse();

    this.$watch('hours', () => this.sync());
    this.$watch('minutes', () => this.sync());
    this.$watch('interval', () => this.sync());
  },
  /**
   * Parse the model value when set.
   * @return {void}
   */
  parse() {
    const [time, interval] = this.model.split(' ');
    const [hours, minutes] = time.split(':');

    this.hours = hours;
    this.minutes = minutes;
    this.interval = interval ?? null;

    this.sync(false);
  },
  /**
   * Set the current time.
   * @return {void}
   */
  current() {
    const dayjs = this.dayjs;

    if (!dayjs) {
      return error('The dayjs library is not available. Please, review the docs.');
    }

    const hours = dayjs.hour();
    const minutes = dayjs.minute();

    if (!full) this.interval = hours >= 12 ? 'PM' : 'AM';

    this.hours = hours;
    this.minutes = minutes;

    this.sync();
  },
  /**
   * Sync the input and model.
   * @param {Boolean} model
   */
  sync(model = true) {
    let value = `${this.formatted.hours}:${this.formatted.minutes}`;

    if (!full && this.interval) {
      value = `${value} ${this.interval}`;
    }

    this.$refs.input.value = value;

    if (!model) return;

    this.model = value;
  },
  /**
   * Get the dayjs library.
   * @return {Dayjs}
   */
  get dayjs() {
    return dayjs();
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
