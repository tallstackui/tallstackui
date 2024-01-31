import {warning} from '../../helpers';

export default (model, period, max) => ({
  model: model,
  show: false,
  hours: '0',
  minutes: '0',
  interval: 'AM',
  init() {
    if (this.model) {
      this.parse();
    }

    this.$watch('hours', (value) => {
      const hours = parseInt(value);

      if (hours >= 12) {
        this.interval = 'PM';
      } else {
        this.interval = 'AM';
      }

      this.sync();
    });

    this.$watch('minutes', () => this.sync());

    this.$watch('interval', (value) => {
      if (parseInt(max) > 12) {
        if (value === 'PM' && this.hours < 12) {
          this.hours = parseInt(this.hours) + 12;
        } else if (value === 'AM' && this.hours >= 12) {
          this.hours = parseInt(this.hours) - 12;
        }
      }

      this.sync();
    });
  },
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
  current() {
    const dayjs = window.dayjs();

    const hours = dayjs.hour();
    const minutes = dayjs.minute();

    this.interval = hours >= 12 ? 'PM' : 'AM';
    this.hours = hours;
    this.minutes = minutes;

    this.sync();
  },
  sync(model = true) {
    let value = `${this.formatted.hours}:${this.formatted.minutes}`;

    if (period && this.interval) {
      value = `${value} ${this.interval}`;
    }

    this.$refs.input.value = value;

    if (!model) return;

    this.model = value;
  },
  get formatted() {
    this.hours = this.hours.toString();
    this.minutes = this.minutes.toString();

    return {
      hours: this.hours.padStart(2, '0'),
      minutes: this.minutes.padStart(2, '0'),
    };
  },
});
