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

      this.sync = `${value}:${this.minutes}`;
    });
    this.$watch('minutes', (value) => this.sync = `${this.hours}:${value}`);

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

    // When period is not enabled, remove period from
    // the model to avoid printing it in the input
    this.model = this.model.replace(' AM', '').replace(' PM', '');

    this.$refs.input.value = this.model;
  },
  test() {
    const moment = window.moment;

    console.log(moment(this.model, 'h:mm A').format('HH:mm'));
  },
  set sync(value) {
    this.model = this.$refs.input.value = `${value} ${this.interval}`;
  },
});
