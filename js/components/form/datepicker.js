import {error} from '../../helpers';

export default (
    model,
    range,
    multiple,
    format,
    dates,
    disable,
) => ({
  picker: {
    common: false,
    year: false,
    month: false,
  },
  format: format,
  model: model,
  value: '',
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
  multiple: multiple,
  disable: disable,
  interval: null,
  selected: null,
  init() {
    const dayjs = this.dayjs();

    if (!dayjs) return error('The dayjs library is not available. Please, review the docs.');

    this.date.min = dates.date.min ? dayjs(dates.date.min) : null;
    this.date.max = dates.date.max ? dayjs(dates.date.max) : null;

    this.reset(dayjs);
    this.calculate();

    if (this.model) {
      if (range && this.model.length === 2) {
        this.date.start = dayjs(this.model[0]).$d;
        this.date.end = dayjs(this.model[1]).$d;
      } else if (this.multiple) {
        this.selected = this.model;
      } else {
        this.date.start = dayjs(this.model).$d;
        this.input = this.formatted(this.model);
      }

      this.update();
      this.picker.common = false;
    }
  },
  clicked(date) {
    const selected = this.instance(date);

    if (this.multiple) {
      this.selected = this.selected ?
            this.selected.includes(selected.format('YYYY-MM-DD')) ?
                this.selected.filter((date) => date !== selected.format('YYYY-MM-DD')) :
                [...this.selected, selected.format('YYYY-MM-DD')] :
                [selected.format('YYYY-MM-DD')];
    } else if (range) {
      if (this.date.start && !this.date.end && selected > this.date.start) {
        this.date.end = selected;
      } else {
        this.date.start = selected;
        this.date.end = null;
      }
    } else {
      this.date.start = selected;
      this.date.end = null;
    }

    this.update();
  },
  selectedDate(day) {
    if (!this.model) return false;

    return this.model.includes(this.instance(day).format('YYYY-MM-DD'));
  },
  /**
   * Checks if the given date is between the range date.
   *
   * @param {string} date
   * @returns boolean
   */
  intervals(date) {
    if (!range || !this.date.end) return false;

    const current = this.dayjs(date);

    const start = this.dayjs(this.date.start);
    const end = this.dayjs(this.date.end);

    return (current.isAfter(start) &&
           current.isBefore(end)) ||
           current.isSame(start) ||
           current.isSame(end);
  },
  calculate() {
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
  change(type) {
    let dayjs = this.dayjs();

    if (type === 'yesterday' || type === 'tomorrow') {
      dayjs = dayjs.add(type === 'yesterday' ? -1 : 1, 'day');
    }

    const current = dayjs.format('YYYY-MM-DD');
    this.model = current;

    this.date.start = dayjs.startOf('day').toDate();
    this.date.end = null;

    this.reset(dayjs);
    this.input = dayjs.format(this.format);
    this.calculate();

    // Checks if there is a disabled date and if it corresponds to the selected date and clears the value if true
    this.days.forEach((date) => {
      if (current === date.instance.format('YYYY-MM-DD') && date.disabled) {
        this.input = '';
      }
    });
  },
  update() {
    if (this.multiple) {
      this.model = this.selected;
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

      this.input = `${start} - ${end}`;
      this.picker.common = this.date.start !== null;

      return;
    }

    this.model = this.formatted(this.date.start, 'YYYY-MM-DD');

    this.input = start;
    this.picker.common = false;
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

    this.calculate();
  },
  nextMonth() {
    this.month = (this.month + 1) % 12;

    if (this.month === 0) this.year++;

    this.calculate();
  },
  selectMonth(event, month) {
    event.stopPropagation();

    this.month = month;
    this.picker.month = false;

    this.calculate();
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

    this.calculate();
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
  showYearPicker() {
    this.picker.year = true;

    this.range.year.start = this.year - 11;
  },
  /**
   * Reset all properties
   */
  clear() {
    this.model = this.input = this.date.start = this.date.end = this.selected = null;
  },
  /**
   * Reset the date to the current date.
   *
   * @param {Dayjs} dayjs
   */
  reset(dayjs) {
    this.month = dayjs.month();
    this.year = dayjs.year();
    this.day = dayjs.date();
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
    this.value = value;
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
   * @return {Dayjs}
   */
  get dayjs() {
    return window.dayjs;
  },
});
