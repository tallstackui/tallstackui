import {error} from '../../helpers';

export default (
    model,
    range,
    multiple,
    format,
    minDate,
    maxDate,
    minYear,
    maxYear,
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
      min: minYear,
      max: maxYear,
      start: 0,
      first: 0,
      last: 0,
    },
  },
  date: {
    min: minDate,
    max: maxDate,
    start: null,
    end: null,
  },
  multiple: multiple,
  disable: disable,
  interval: null,
  selected: null,
  init() {
    const dayjs = this.dayjs;

    if (!dayjs) {
      return error('The dayjs library is not available. Please, review the docs.');
    }

    this.date.min = minDate ? dayjs(minDate) : null;
    this.date.max = maxDate ? dayjs(maxDate) : null;

    this.month = dayjs().month();
    this.year = dayjs().year();
    this.day = dayjs().day();

    this.calculate();

    // Checks if the model is defined and hydrates according to the mode (range, multiple, default) of the datepicker
    if (this.model) {
      if (range && this.model.length === 2) {
        this.date.start = dayjs(this.model[0]).$d;
        this.date.end = dayjs(this.model[1]).$d;
      } else if (this.multiple) {
        this.selected = this.model;
      } else {
        this.date.start = dayjs(this.model).$d;
        this.value = this.formatted(this.model);
      }

      this.update();
      this.picker.common = false;
    }
  },
  /**
   * Based on the type of datepicker, this function treats the clicked date and applies the appropriate
   * formatting and values.
   * @param {string} day
   */
  clicked(day) {
    const selected = this.dayjs(`${this.year}-${this.month + 1}-${day}`);

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
  /**
   * Checks if the date informed by the model is the same as the loop date
   * @param {string} day
   * @returns boolean
   */
  selectedDate(day) {
    if (!this.model) return false;

    const date = this.dayjs(`${this.year}-${this.month + 1}-${day}`);

    return this.model.includes(date.format('YYYY-MM-DD'));
  },
  /**
   * Checks if the given date is between the range date in order to colorize the range interval
   * @param {string} date
   * @returns boolean
   */
  dateInterval(date) {
    if (!range || !this.date.end) return false;

    const current = this.dayjs(date);
    const start = this.dayjs(this.date.start);
    const end = this.dayjs(this.date.end);

    return current.isAfter(start) &&
           current.isBefore(end) ||
           current.isSame(start) ||
           current.isSame(end);
  },
  /**
   * Generate calendar days based on the selected month and year.
   */
  calculate() {
    const dayjs = this.dayjs;

    const start = dayjs(`${this.year}-${this.month + 1}-01`);

    const month = start.endOf('month').date();
    const week = start.day();

    this.blanks = Array.from({length: week}, (_, index) => index + 1);

    this.days = Array.from({length: month}, (_, index) => {
      const date = start.add(index, 'day');

      return {
        instance: date,
        day: date.date(),
        disabled: this.disabled(date.toDate()),
      };
    });
  },
  /**
   * Logic to make the helper buttons work according to the datepicker type
   * @param {String} type
   */
  change(type) {
    let dayjs = this.dayjs();

    if (type === 'yesterday' || type === 'tomorrow') {
      dayjs = dayjs.add(type === 'yesterday' ? -1 : 1, 'day');
    } else if (type.startsWith('last')) {
      if (range) {
        const days = parseInt(type.replace('last', ''), 10);
        const start = dayjs.subtract(days, 'day').startOf('day');
        const end = dayjs.startOf('day');

        if (!this.disabled(start.toDate()) && !this.disabled(end.toDate())) {
          this.date.start = startDate.toDate();
          this.date.end = end.toDate();

          this.update();

          return;
        }
      } else {
        const subtract = parseInt(type.replace('last', ''), 10);
        dayjs = dayjs.subtract(subtract, 'day');
      }
    }

    const current = dayjs.format('YYYY-MM-DD');
    this.model = current;

    this.month = dayjs.month();
    this.year = dayjs.year();
    this.day = dayjs.date();
    this.value = dayjs.format(this.format);

    this.calculate();

    // Checks if there is a disabled date and if it corresponds to the selected date and clears the value if true
    this.days.forEach((date) => {
      if (current === date.instance.format('YYYY-MM-DD') && date.disabled) {
        this.value = '';
      }
    });
  },
  /**
   * Handles items according to the datepicker type and display format
   */
  update() {
    if (this.multiple) {
      this.model = this.selected;
      this.value = this.model.map((date) => this.formatted(date)).join(', ');

      return;
    }

    const start = this.formatted(this.date.start);
    const end = this.formatted(this.date.end);

    if (range) {
      this.model = [
        this.formatted(this.date.start, 'YYYY-MM-DD'),
        this.date.end !== null ? this.formatted(this.date.end) : null,
      ];

      this.value = start + ' - ' + end;
      this.picker.common = this.date.start !== null;

      return;
    }

    this.model = this.formatted(this.date.start, 'YYYY-MM-DD');

    this.value = start;
    this.picker.common = false;
  },
  /**
   * Checks if the date is today
   *
   * @param date
   * @return {Boolean}
   */
  today(date) {
    return Boolean(this.dayjs().isSame(this.dayjs(`${this.year}-${this.month + 1}-${date}`), 'day'));
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
  previousYearRange(event) {
    event.stopPropagation();

    if (this.range.year.min !== null && this.range.year.first <= this.range.year.max) return;

    this.range.year.start -= 19;
  },
  nextYearRange(event) {
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
  toggleYear() {
    this.picker.year = true;

    this.range.year.start = this.year - 11;
  },
  /**
   * Reset all properties
   */
  clear() {
    this.model = this.value = this.date.start = this.date.end = this.selected = null;
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
