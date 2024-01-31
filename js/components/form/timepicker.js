export default (model, max) => ({
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

      value = value.padStart(2, '0');

      this.sync = `${value}:${this.minutes}`;
    });

    this.$watch('minutes', (value) => {
      value = value.padStart(2, '0');

      this.sync = `${this.hours}:${value}`;
    });

    this.$watch('interval', (value) => {
      if (parseInt(max) > 12) {
        if (value === 'PM' && this.hours < 12) {
          this.hours = parseInt(this.hours) + 12;
        } else if (value === 'AM' && this.hours >= 12) {
          this.hours = parseInt(this.hours) - 12;
        }

        this.hours = this.hours.toString();
      }

      this.sync = `${this.hours}:${this.minutes}`;
    });
  },
  parse() {
    const [time, interval] = this.model.split(' ');
    const [hours, minutes] = time.split(':');

    this.hours = hours;
    this.minutes = minutes;
    this.interval = interval;

    this.$refs.input.value = this.model;
  },
  current() {
    const dayjs = window.dayjs();

    const hours = dayjs.hour();
    const minutes = dayjs.minute();

    this.interval = hours >= 12 ? 'PM' : 'AM';
    this.hours = hours.toString();
    this.minutes = minutes.toString();

    this.hours = this.hours.padStart(2, '0');

    this.sync = `${this.hours}:${this.minutes}`;
  },
  set sync(value) {
    this.model = this.$refs.input.value = `${value} ${this.interval}`;
  },
});
