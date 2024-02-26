import {error} from '../../helpers';

export default (
    model,
    range,
    multiple,
    format,
    dates,
    disable,
    livewire,
    property,
    value,
) => ({
  picker: {
    common: false,
    year: false,
    month: false,
  },
  format: format,
  model: model,
  day: '',
  month: '',
  year: '',
  days: [],
  blanks: [],
  range: {
    year: {
      min: dates.year.min,
      max: dates.year.max,
      start: 0,
      first: 0,
      last: 0,
    },
  },
  date: {
    min: dates.date.min,
    max: dates.date.max,
    start: null,
    end: null,
  },
  disable: disable,
  interval: null,
  livewire: livewire,
  property: property,
  value: value,
  init() {
    const dayjs = this.dayjs;

    if (!dayjs) return error('The dayjs library is not available. Please, review the docs.');

    this.date.min = dates.date.min ? dayjs(dates.date.min) : null;
    this.date.max = dates.date.max ? dayjs(dates.date.max) : null;

    this.reset();
    this.map();
    this.hydrate();
  },
  hydrate() {
    if (!this.livewire && !this.model && this.value) this.model = this.value;

    const dayjs = this.dayjs;

    if (range && this.model) { // refactor
      const start = this.model[0] ? dayjs(this.model[0]).$d : null;
      const end = this.model[1] ? dayjs(this.model[1]).$d : null;

      this.date.start = start;
      this.date.end = end;

      if (!start && !end) this.model = null;

      this.picker.common = false;

      return this.sync();
    }

    if (multiple) {
      this.picker.common = false;

      return this.sync();
    }

    this.date.start = this.model ? dayjs(this.model).$d : null;
    this.picker.common = false;

    this.sync();
  },
  sync() {
    if (!this.model) return;

    if (multiple) {
      this.input = this.model.map((date) => this.formatted(date)).join(', ');

      return;
    }

    const start = this.formatted(this.date.start);
    const end = this.formatted(this.date.end);

    if (range) {
      this.model = [
        this.formatted(this.date.start, 'YYYY-MM-DD'),
        this.date.end !== null ? this.formatted(this.date.end, 'YYYY-MM-DD') : null,
      ];

      return this.input = `${start} - ${end}`;
    }

    this.input = start;
    this.picker.common = false;
  },
  // refactor
  select(event, day) {
    event.preventDefault();

    const date = this.instance(day);

    if (multiple) {
      // refactor
      this.model = this.model ?
            this.model.includes(date.format('YYYY-MM-DD')) ?
                this.model.filter((day) => day !== date.format('YYYY-MM-DD')) :
                [...this.model, date.format('YYYY-MM-DD')] :
                [date.format('YYYY-MM-DD')];

      return this.sync();
    }

    if (range) {
      if (this.date.start && (!this.date.end && date > this.date.start)) {
        this.date.end = date;
      } else {
        this.date.start = date;
        this.date.end = null;
      }

      this.picker.common = this.date.start !== null && this.date.end === null;
      this.model = [];

      return this.sync();
    }

    this.date.start = date;
    this.date.end = null;
    this.model = date.format('YYYY-MM-DD');

    this.sync();
  },
  map() {
    const start = this.instance('01');

    const month = start.endOf('month').date();
    const week = start.day();

    this.blanks = Array.from({length: week}, (key, value) => value + 1);

    this.days = Array.from({length: month}, (key, value) => {
      const date = start.add(value, 'day');

      return {
        instance: date,
        day: date.date(),
        disabled: this.disabled(date.toDate()),
      };
    });
  },
  helper(type) {
    let dayjs = this.dayjs();

    if (type === 'yesterday' || type === 'tomorrow') {
      dayjs = dayjs.add(type === 'yesterday' ? -1 : 1, 'day');
    }

    const current = dayjs.format('YYYY-MM-DD');
    this.model = current;

    this.date.start = dayjs.startOf('day').toDate();
    this.date.end = null;

    this.reset();
    this.input = dayjs.format(this.format);
    this.map();

    // Checks if there is a disabled date and if it corresponds to the selected date and clears the value if true
    this.days.forEach((date) => {
      if (current === date.instance.format('YYYY-MM-DD') && date.disabled) {
        this.input = '';
      }
    });
  },
  selected(day) {
    if (!this.model) return false;

    return this.model.includes(this.instance(day).format('YYYY-MM-DD'));
  },
  /**
   * Checks if the given date is between the range date.
   *
   * @param {string} date
   * @returns boolean
   */
  between(date) {
    if (!range || !this.date.end) return false;

    const current = this.dayjs(date);

    const start = this.dayjs(this.date.start);
    const end = this.dayjs(this.date.end);

    return (current.isAfter(start) && current.isBefore(end)) || current.isSame(start) || current.isSame(end);
  },
  /**
   * Checks if the date is today
   *
   * @param date
   * @return {Boolean}
   */
  today(date) {
    return Boolean(this.dayjs().isSame(this.instance(date), 'day'));
  },
  /**
   * Checks if the date is disabled
   *
   * @param date
   * @return {Boolean}
   */
  disabled(date) {
    return (this.date.min && date <= this.date.min) ||
           (this.date.max && date >= this.date.max) ||
           this.disable.includes(this.formatted(date, 'YYYY-MM-DD'));
  },
  previousMonth() {
    this.month = (this.month === 0) ? 11 : this.month - 1;

    if (this.month === 11) this.year--;

    this.map();
  },
  nextMonth() {
    this.month = (this.month + 1) % 12;

    if (this.month === 0) this.year++;

    this.map();
  },
  selectMonth(event, month) {
    event.stopPropagation();

    this.month = month;
    this.picker.month = false;

    this.map();
  },
  previousYear(event) {
    event.stopPropagation();

    if (this.range.year.min !== null && this.range.year.first <= this.range.year.max) return;

    this.range.year.start -= 19;
  },
  nextYear(event) {
    event.stopPropagation();

    if (this.range.year.min !== null && this.range.year.last >= this.range.year.max) return;

    this.range.year.start += 19;
  },
  selectYear(event, year) {
    event.stopPropagation();

    this.year = year;
    this.picker.year = false;

    this.map();
  },
  yearRange() {
    const start = this.range.year.start;

    const min = this.range.year.min ?? -Infinity;
    const max = this.range.year.max ?? Infinity;

    const range = Array.from({length: 20}, (key, index) => {
      const year = start + index;
      const disabled = year < min || year > max;

      return {year, disabled};
    });

    this.range.year.first = range[0]?.year;
    this.range.year.last = range[range.length - 1]?.year;

    return range;
  },
  /**
   * Reset all properties
   */
  clear() {
    this.input = this.model = this.date.start = this.date.end = null;
  },
  /**
   * Reset the day, month and year to the current date.
   *
   * @return {void}
   */
  reset() {
    const date = this.dayjs();

    this.day = date.date();
    this.month = date.month();
    this.year = date.year();
  },
  /**
   * Format the date.
   *
   * @param date
   * @param {Null|String} format
   * @return {String}
   */
  formatted(date, format = null) {
    return this.dayjs(date).format(format ?? this.format);
  },
  /**
   * Create a new instance of the Dayjs library optionally passing the day.
   *
   * @param {String|Null} day
   * @return {String}
   */
  instance(day = null) {
    return this.dayjs(`${this.year}-${this.month + 1}-${day ?? this.day}`);
  },
  /**
   * Set the value of the input.
   *
   * @param {*} value
   */
  set input(value) {
    this.$refs.input.value = value;

    if (this.livewire) return;

    const input = document.getElementsByName(this.property)[0];

    if (!input) return;

    input.value = !this.model ? '' :
        (typeof this.model === 'string' ? this.model : JSON.stringify(this.model));
  },
  /**
   * Get the quantity of the selected dates.
   * @return {Number}
   */
  get quantity() {
    return this.model?.length ?? 0;
  },
  /**
   * Get the period of the week and month.
   *
   * @return {{week: *, month: *}}
   */
  get period() {
    const dayjs = this.dayjs;

    return {
      week: dayjs.weekdaysShort().map((days) => days.charAt(0).toUpperCase() + days.slice(1)),
      month: dayjs.months().map((month) => month.charAt(0).toUpperCase() + month.slice(1)),
    };
  },
  /**
   * Get the dayjs library.
   *
   * @return {Dayjs}
   */
  get dayjs() {
    return window.dayjs;
  },
});
