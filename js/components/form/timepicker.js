import {error, warning} from '../../helpers';

// TODO: only set AM/PM when its defined.
export default (model, period, fullTime) => ({
  model: model,
  show: false,
  hours: '0',
  minutes: '0',
  interval: 'AM',
  init() {
    if (this.dayjs === undefined) {
      return error('The dayjs library is not available. Please, review the docs.');
    }

    if (this.model) {
      this.parse();
    }

    this.$watch('hours', (value) => {
      const hours = parseInt(value);

      // When fullTime and the selected hour is greater
      // than 12 we change the interval to PM
      if (fullTime && hours > 12) {
        this.interval = 'PM';
      } else if (fullTime && hours < 12) {
        this.interval = 'AM';
      }

      this.sync();
    });

    this.$watch('minutes', () => this.sync());

    this.$watch('interval', (value) => {
      this.hours = parseInt(this.hours);

      if (fullTime) {
        // Advancing or retreating the hour when the interval changes
        if (value === 'PM' && this.hours < 12) {
          this.hours = parseInt(this.hours) + 12;
        } else if (value === 'AM' && this.hours >= 12) {
          this.hours = parseInt(this.hours) - 12;
        }
      }

      this.sync();
    });
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

    if (period && interval === undefined) {
      warning('Unable to parse the interval [AM/PM] for the timepicker component.');
    }

    period = interval !== undefined;

    this.sync(false);
  },
  /**
   * Set the current time.
   * @return {void}
   */
  current() {
    const dayjs = this.dayjs();

    if (!dayjs) {
      return error('The dayjs library is not available. Please, review the docs.');
    }

    const hours = dayjs.hour();
    const minutes = dayjs.minute();

    this.interval = hours >= 12 ? 'PM' : 'AM';
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

    if (fullTime && this.interval) {
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
    return window.dayjs;
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
