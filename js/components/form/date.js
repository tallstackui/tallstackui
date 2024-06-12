import dayjs from 'dayjs';
import updateLocale from 'dayjs/plugin/updateLocale';
import isBetween from 'dayjs/plugin/isBetween';
import {wireChange} from '../../helpers';

dayjs.extend(updateLocale);
dayjs.extend(isBetween);

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
    monthYearOnly,
    calendar,
    change = null,
) => ({
  show: false,
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
  monthYearOnly: monthYearOnly,
  value: value,
  calendar: calendar,
  init() {
    this.translations();

    if (this.monthYearOnly) this.picker.month = true;

    this.date.min = dates.date.min ? dayjs(dates.date.min) : null;
    this.date.max = dates.date.max ? dayjs(dates.date.max) : null;

    if ((!this.livewire && !this.model) && this.value) this.model = this.value;

    this.reset();
    this.map();
    this.$nextTick(() => this.hydrate());

    // Prevents more than two dates from being defined in
    // the model when it comes to interval mode, because
    // when this happens, the other dates are displayed
    // selected in the calendar.
    if (range && this.model && (this.model.constructor === Array && this.model.length > 2)) {
      this.model = this.model.filter((value, key) => key < 2);
    }

    this.$watch('show', (value) => {
      if (!value || (this.picker.year || this.picker.month)) return;

      this.reset();
      this.map();
    });

    this.$watch('model', () => {
      if (!this.livewire) return;

      this.hydrate();
    });
  },
  /**
   * Translate the calendar.
   *
   * @return {void}
   */
  translations() {
    this.calendar['months'] = Object.values(this.calendar['months']);
    this.calendar['week'] = Object.values(this.calendar['week']);

    dayjs.updateLocale('en', {
      weekdays: this.calendar['week'],
      weekdaysShort: this.calendar['week'].map((day) => day.slice(0, 3)),
      weekdaysMin: this.calendar['week'].map((day) => day.slice(0, 2)),
      months: this.calendar['months'],
      monthsShort: this.calendar['months'].map((month) => month.slice(0, 3)),
    });
  },
  /**
   * Hydrate the need stuff in the bootstrap.
   *
   * @return {void}
   */
  hydrate() {
    if (range && this.model) {
      const one = this.model[0];

      // The two (model.1) can be an empty/null in
      // situation where only the start was set.
      let two = this.model[1];
      two = two === 'null' ? null : two;

      const start = one ? dayjs(one).$d : null;
      const end = two ? dayjs(two).$d : null;

      this.date.start = start;
      this.date.end = end;

      // This code was necessary to use the component outside the Livewire
      // to set the model to null when no date was defined by default,
      // preventing the request from sending an empty array.
      if (!start && !end) this.model = null;

      return this.sync();
    }

    if (multiple) {
      // ... same as above!
      this.model = this.quantity === 0 ? null : this.model;

      return this.sync();
    }

    this.date.start = this.model ? dayjs(this.model).$d : null;

    this.sync();
  },
  /**
   * Sync the input.
   *
   * @return {void}
   */
  sync() {
    if (!this.model) return;

    this.$el.dispatchEvent(new CustomEvent('select', {detail: {type: this.type, date: this.model}}));

    if (multiple) {
      this.input = this.model.map((date) => this.formatted(date)).join(', ');

      return;
    }

    const start = this.formatted(this.date.start);
    const end = this.formatted(this.date.end);

    if (range) {
      this.model[0] = this.formatted(this.date.start, 'YYYY-MM-DD');
      this.model[1] = this.date.end !== null ? this.formatted(this.date.end, 'YYYY-MM-DD') : null;

      this.input = `${start} - ${end === 'Invalid Date' ? '' : end}`;

      return;
    }

    this.show = false;

    const action = {
      true: () => {
        this.model = this.formatted(this.date.start, 'YYYY-MM');
        this.input = this.formatted(this.date.start, 'MMMM YYYY');
        this.resetPicker({month: true});
      },
      false: () => {
        this.input = start;
        this.resetPicker();
      }
    };

    action[this.monthYearOnly]();
  },
  /**
   * Select the date.
   *
   * @param {Event} event
   * @param {String} day
   * @return {*}
   */
  select(event, day) {
    event.preventDefault();

    const date = this.instance(day);
    const formatted = date.format('YYYY-MM-DD');

    if (multiple) {
      // This code is basically: when the model already
      // has the date we remove it, otherwise we add it.
      this.model = this.model ?
          this.model.includes(formatted) ?
              this.model.filter((day) => day !== formatted) :
              [...this.model, formatted] :
          [formatted];

      return this.sync();
    }

    if (range) {
      const condition = this.date.start && !this.date.end && date > this.date.start;

      this.date.end = condition ? date : null;

      if (!condition) this.date.start = date;

      this.model = [];
      this.show = this.date.start !== null && this.date.end === null;

      return this.sync();
    }

    this.date.start = date;
    this.date.end = null;
    this.model = date.format('YYYY-MM-DD');

    this.sync();

    wireChange(change, this.model);
  },
  /**
   * Map the days of the month.
   *
   * @return {void}
   */
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
  /**
   * Set the date using the helper buttons.
   *
   * @param {Event} event
   * @param {String} type
   * @return {void}
   */
  helper(event, type) {
    event.preventDefault();

    let date = dayjs();

    if (type === 'yesterday' || type === 'tomorrow') {
      date = date.add(type === 'yesterday' ? -1 : 1, 'day');
    }

    if (this.disabled(date.format('YYYY-MM-DD'))) return;

    const current = date.format('YYYY-MM-DD');

    this.date.start = date.startOf('day').toDate();
    this.date.end = null;
    this.model = this.type !== 'single' ? [current] : current;

    this.show = false;

    this.reset();
    this.input = date.format(this.format);
    this.map();

    wireChange(change, this.model);
  },
  /**
   * Checks if the given day is selected.
   *
   * @param {String} day
   * @returns boolean
   */
  selected(day) {
    if (!this.model) return false;

    return this.model.includes(this.instance(day).format('YYYY-MM-DD'));
  },
  /**
   * Checks if the given date is between the range date.
   *
   * @param {String} date
   * @returns boolean
   */
  between(date) {
    if (!range || !this.date.end) return false;

    const current = dayjs(date);

    const start = dayjs(this.date.start);
    const end = dayjs(this.date.end);

    return current.isBetween(start, end) || current.isSame(start) || current.isSame(end);
  },
  /**
   * Checks if the date is today
   *
   * @param {String} date
   * @return {Boolean}
   */
  today(date) {
    return Boolean(dayjs().isSame(this.instance(date), 'day'));
  },
  /**
   * Set the calendar to today's date.
   *
   * @return {void}
   */
  now() {
    this.reset();
    this.map();
    this.resetPicker();
  },
  /**
   * Checks if the date is disabled
   *
   * @param date
   * @return {Boolean}
   */
  disabled(date) {
    return (this.date.min && dayjs(date).isBefore(this.date.min)) ||
           (this.date.max && dayjs(date).isAfter(this.date.max)) ||
           this.disable.includes(this.formatted(date, 'YYYY-MM-DD'));
  },
  /**
   * Navigate to the previous month
   *
   * @return {void}
   */
  previousMonth() {
    if (this.range.year.min && (this.month === 0 && this.year <= this.range.year.min)) {
      return;
    }

    this.month = (this.month === 0) ? 11 : this.month - 1;

    if (this.month === 11) this.year--;

    this.map();
  },
  /**
   * Navigate to the next month
   *
   * @return {void}
   */
  nextMonth() {
    if (this.range.year.max && (this.month === 11 && this.year >= this.range.year.max)) {
      return;
    }

    this.month = (this.month + 1) % 12;

    if (this.month === 0) this.year++;

    this.map();
  },
  /**
   * Select the month.
   *
   * @param {Event} event
   * @param {String} month
   * @return {void}
   */
  selectMonth(event, month) {
    event.preventDefault();
    event.stopPropagation();

    this.month = month;
    this.picker.month = false;

    if (this.monthYearOnly) {
      this.picker.year = true;
      this.range.year.start = (this.year - 11)
    }

    this.map();
  },
  /**
   * Navigate to the previous year
   *
   * @param {Event} event
   * @return {void}
   */
  previousYear(event) {
    event.preventDefault();
    event.stopPropagation();

    if (this.range.year.min !== null && this.range.year.first <= this.range.year.min) return;

    this.range.year.start -= 19;
  },
  /**
   * Navigate to the next year
   *
   * @param {Event} event
   * @return {void}
   */
  nextYear(event) {
    event.preventDefault();
    event.stopPropagation();

    if (this.range.year.max !== null && this.range.year.last >= this.range.year.max) return;

    this.range.year.start += 19;
  },
  /**
   * Select the year.
   *
   * @param {Event} event
   * @param {String} year
   * @return {void}
   */
  selectYear(event, year) {
    event.preventDefault();
    event.stopPropagation();

    this.year = year;

    if (this.monthYearOnly) {
      this.date.start = dayjs(`${this.year}-${this.month + 1}`).$d;
      this.model = this.date.start;

      return this.sync();
    }

    this.resetPicker();
    this.map();
  },
  /**
   * Get the range of the years.
   *
   * @return {{year: number, disabled: boolean}[]}
   */
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
   *
   * @return {void}
   */
  clear() {
    const model = this.model;

    this.input = this.model = this.value = this.date.start = this.date.end = null;

    this.$el.dispatchEvent(new CustomEvent('clear', {detail: {type: this.type, date: model}}));
  },
  /**
   * Reset the day, month and year to the current date.
   *
   * @return {void}
   */
  reset() {
    const current = Array.isArray(this.model) ? this.model[0] : this.model;

    const date = current ? dayjs(current) : dayjs();

    this.day = date.date();
    this.month = date.month();
    this.year = date.year();
  },
  /**
   * Reset the picker properties.
   *
   * @param {Object} picker
   * @return {void}
   */
  resetPicker(picker = {}) {
    this.picker = {
      common: picker?.common ?? false,
      year: picker?.year ?? false,
      month: picker?.month ?? false,
    };
  },
  /**
   * Format the date.
   *
   * @param {String} date
   * @param {String|Null} format
   * @return {String}
   */
  formatted(date, format = null) {
    return dayjs(date).format(format ?? this.format);
  },
  /**
   * Create a new instance of the Dayjs library optionally passing the day.
   *
   * @param {String|Null} day
   * @return {dayjs.Dayjs}
   */
  instance(day = null) {
    return dayjs(`${this.year}-${this.month + 1}-${day ?? this.day}`);
  },
  /**
   * Set the value of the input.
   *
   * @param {*} value
   * @return {void}
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
   * Get the type of the date calendar.
   *
   * @return {String}
   */
  get type() {
    return multiple ? 'multiple' : (range ? 'range' : 'single');
  },
  /**
   * Get the quantity of the selected dates.
   *
   * @return {Number}
   */
  get quantity() {
    return this.model?.length ?? 0;
  },
});
