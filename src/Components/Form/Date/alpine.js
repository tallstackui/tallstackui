import { datetime, isDatetime, localize } from '../../../../js/helpers/date';
import { lockable, wireChange } from '../../../../js/helpers';

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
  disabled = false,
  readonly = false,
  change = null,
  start = 5,
  only = null,
  weekdays = false,
  weekends = false,
  typeable = false
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
  ...lockable(disabled, readonly),
  interval: null,
  livewire: livewire,
  property: property,
  monthYearOnly: monthYearOnly,
  value: value,
  calendar: calendar,
  start: start,
  only: only,
  weekends: weekends,
  weekdays: weekdays,
  typeable: typeable,
  init() {
    this.translations();

    if (this.monthYearOnly) this.picker.month = true;

    this.date.min = dates.date.min ? datetime(dates.date.min) : null;
    this.date.max = dates.date.max ? datetime(dates.date.max) : null;

    if (!this.livewire && !this.model && this.value) this.model = this.value;

    this.reset();
    this.map();
    this.$nextTick(() => this.hydrate());

    // Prevents more than two dates from being defined in
    // the model when it comes to interval mode, because
    // when this happens, the other dates are displayed
    // selected in the calendar.
    if (range && this.model && this.model.constructor === Array && this.model.length > 2) {
      this.model = this.model.filter((value, key) => key < 2);
    }

    this.$watch('show', (value) => {
      if (!value || this.picker.year || this.picker.month) return;

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

    // Before the rotation below, because the format tokens index the weekdays
    // by what the date reports, which always counts from Sunday.
    localize({ months: this.calendar['months'], weekdays: this.calendar['week'] });

    // Reorder the week days according to the start day
    if (this.start > 0) {
      const days = [...this.calendar['week']];

      const first = days.slice(0, this.start);

      const second = days.slice(this.start);

      this.calendar['week'] = [...second, ...first];
    }
  },
  /**
   * Hydrate the need stuff in the bootstrap.
   *
   * @return {void}
   */
  hydrate() {
    if (range && this.model) {
      const one = this.model[0];

      // The two (model.1) can be empty /null in
      // a situation where only the start was set.
      let two = this.model[1];
      two = two === 'null' ? null : two;

      const start = one ? datetime(one).toDate() : null;
      const end = two ? datetime(two).toDate() : null;

      this.date.start = start;
      this.date.end = end;

      // This code was necessary to use the component outside the Livewire
      // to set the model to null when no date was defined by default,
      // preventing the request from sending an empty array.
      if (!start && !end) this.model = null;

      this.sync();

      return this.refresh();
    }

    if (multiple) {
      // ... same as above!
      this.model = this.quantity === 0 ? null : this.model;

      this.sync();

      return this.refresh();
    }

    this.date.start = this.model ? datetime(this.model).toDate() : null;

    this.sync();
    this.refresh();
  },
  /**
   * Sync the input.
   *
   * @return {void}
   */
  sync() {
    if (!this.model) {
      this.input = '';

      return;
    }

    this.$el.dispatchEvent(
      new CustomEvent('select', { detail: { type: this.type, date: this.model } })
    );

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
        this.resetPicker({ month: true });
      },
      false: () => {
        this.input = start;
        this.resetPicker();
      },
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
    if (this.locked()) {
      return;
    }

    event.preventDefault();

    const date = this.instance(day);
    const formatted = date.format('YYYY-MM-DD');

    if (multiple) {
      // This code is basically: when the model already
      // has the date we remove it, otherwise we add it.
      this.model = this.model
        ? this.model.includes(formatted)
          ? this.model.filter((day) => day !== formatted)
          : [...this.model, formatted]
        : [formatted];

      this.sync();

      return this.refresh();
    }

    if (range) {
      const condition = this.date.start && !this.date.end && date > this.date.start;

      this.date.end = condition ? date : null;

      if (!condition) this.date.start = date;

      this.model = [];
      this.show = this.date.start !== null && this.date.end === null;

      this.sync();

      return this.refresh();
    }

    this.date.start = date;
    this.date.end = null;
    this.model = date.format('YYYY-MM-DD');

    this.sync();
    this.refresh();

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
    const count = (week - this.start + 7) % 7;

    this.blanks = Array.from({ length: count }, (key, value) => value + 1);

    const todayStr = datetime().format('YYYY-MM-DD');
    const startTime = this.date.start ? new Date(this.date.start).getTime() : null;
    const endTime = this.date.end ? new Date(this.date.end).getTime() : null;
    const rangeStart = range && this.date.start ? datetime(this.date.start) : null;
    const rangeEnd = range && this.date.end ? datetime(this.date.end) : null;
    const selectedSet = multiple && this.model ? new Set(this.model) : null;

    this.days = Array.from({ length: month }, (key, value) => {
      const date = start.add(value, 'day');
      const formatted = date.format('YYYY-MM-DD');
      const timestamp = date.toDate().getTime();

      const isBetween =
        rangeStart && rangeEnd
          ? date.isBetween(rangeStart, rangeEnd) || date.isSame(rangeStart) || date.isSame(rangeEnd)
          : false;

      return {
        instance: date,
        day: date.date(),
        formatted: formatted,
        disabled: this.disabled(date),
        isToday: formatted === todayStr,
        isSelected: this.isSelected(formatted, selectedSet),
        isBetween: isBetween,
        isStart: startTime !== null && timestamp === startTime,
        isEnd: endTime !== null && timestamp === endTime,
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

    let date = datetime();

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
   * Checks if the given formatted date is selected.
   *
   * @param {String} formatted
   * @param {Set|null} selectedSet
   * @returns {Boolean}
   */
  isSelected(formatted, selectedSet = null) {
    if (!this.model) return false;

    if (selectedSet) return selectedSet.has(formatted);

    return this.model.includes(formatted);
  },
  /**
   * Refresh pre-computed metadata on existing day
   * objects after selection changes, avoiding a full
   * map() recomputation.
   *
   * @return {void}
   */
  refresh() {
    if (!this.days || this.days.length === 0) {
      return;
    }

    const startTime = this.date.start ? new Date(this.date.start).getTime() : null;
    const endTime = this.date.end ? new Date(this.date.end).getTime() : null;
    const rangeStart = range && this.date.start ? datetime(this.date.start) : null;
    const rangeEnd = range && this.date.end ? datetime(this.date.end) : null;
    const selectedSet = multiple && this.model ? new Set(this.model) : null;

    for (let i = 0; i < this.days.length; i++) {
      const day = this.days[i];
      const timestamp = day.instance.toDate().getTime();

      day.isSelected = this.isSelected(day.formatted, selectedSet);
      day.isBetween =
        rangeStart && rangeEnd
          ? day.instance.isBetween(rangeStart, rangeEnd) ||
            day.instance.isSame(rangeStart) ||
            day.instance.isSame(rangeEnd)
          : false;
      day.isStart = startTime !== null && timestamp === startTime;
      day.isEnd = endTime !== null && timestamp === endTime;
    }
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
    const parsed = isDatetime(date) ? date : datetime(date);
    const day = parsed.day();

    return (
      (this.date.min && parsed.isBefore(this.date.min)) ||
      (this.date.max && parsed.isAfter(this.date.max)) ||
      (this.weekdays && (day === 0 || day === 6)) ||
      (this.weekends && day !== 0 && day !== 6) ||
      (this.only && day !== parseInt(this.only)) ||
      this.disable.includes(parsed.format('YYYY-MM-DD'))
    );
  },
  /**
   * Navigate to the previous month
   *
   * @return {void}
   */
  previousMonth() {
    if (this.range.year.min && this.month === 0 && this.year <= this.range.year.min) {
      return;
    }

    this.month = this.month === 0 ? 11 : this.month - 1;

    if (this.month === 11) this.year--;

    this.map();
  },
  /**
   * Navigate to the next month
   *
   * @return {void}
   */
  nextMonth() {
    if (this.range.year.max && this.month === 11 && this.year >= this.range.year.max) {
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
      this.range.year.start = this.year - 11;
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
      this.date.start = datetime(`${this.year}-${this.month + 1}`).toDate();
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

    const range = Array.from({ length: 20 }, (key, index) => {
      const year = start + index;
      const disabled = year < min || year > max;

      return { year, disabled };
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
    if (this.locked()) {
      return;
    }

    const model = this.model;

    this.input = this.model = this.value = this.date.start = this.date.end = null;

    this.$el.dispatchEvent(new CustomEvent('clear', { detail: { type: this.type, date: model } }));
  },
  /**
   * Reset the day, month, and year to the current date.
   *
   * @return {void}
   */
  reset() {
    const current = Array.isArray(this.model) ? this.model[0] : this.model;

    const date = current ? datetime(current) : datetime();

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
    return datetime(date).format(format ?? this.format);
  },
  /**
   * Create a new date instance optionally passing the day.
   *
   * @param {String|Null} day
   * @return {DateTime}
   */
  instance(day = null) {
    return datetime(`${this.year}-${this.month + 1}-${day ?? this.day}`);
  },
  /**
   * Build the input mask array from the date format string.
   * Each entry is either { type: 'digit' } or { type: 'literal', char }.
   *
   * @return {{ type: string, char?: string }[]}
   */
  buildMask() {
    const mask = [];
    let i = 0;

    while (i < this.format.length) {
      if (this.format.slice(i, i + 4) === 'YYYY') {
        for (let j = 0; j < 4; j++) mask.push({ type: 'digit' });
        i += 4;
      } else if (
        this.format.slice(i, i + 2) === 'MM' ||
        this.format.slice(i, i + 2) === 'DD'
      ) {
        for (let j = 0; j < 2; j++) mask.push({ type: 'digit' });
        i += 2;
      } else {
        mask.push({ type: 'literal', char: this.format[i] });
        i++;
      }
    }

    return mask;
  },
  /**
   * Apply the format mask to the input as the user types.
   *
   * @param {InputEvent} event
   * @return {void}
   */
  applyMask(event) {
    const input = event.target;
    const isDeleting =
      event.inputType === 'deleteContentBackward' ||
      event.inputType === 'deleteContentForward';
    const digits = input.value.replace(/\D/g, '');
    const mask = this.buildMask();

    let result = '';
    let di = 0;

    for (let i = 0; i < mask.length && di < digits.length; i++) {
      if (mask[i].type === 'literal') {
        result += mask[i].char;
      } else {
        result += digits[di++];
      }
    }

    if (!isDeleting && result.length < mask.length) {
      const next = mask[result.length];
      if (next && next.type === 'literal') {
        result += next.char;
      }
    }

    input.value = result;
  },
  /**
   * Parse the typed value against the component format and update the model.
   * Restores the previous valid date when the typed value is invalid.
   *
   * @return {void}
   */
  parseTyped() {
    const value = this.$refs.input.value;

    if (!value || !value.trim()) {
      this.clear();
      return;
    }

    const parsed = this.parseFromFormat(value);

    if (!parsed || !parsed.isValid() || this.disabled(parsed)) {
      this.input = this.date.start ? this.formatted(this.date.start) : '';
      return;
    }

    this.date.start = parsed.toDate();
    this.date.end = null;
    this.model = parsed.format('YYYY-MM-DD');

    this.reset();
    this.map();
    this.sync();
    this.refresh();

    wireChange(change, this.model);
  },
  /**
   * Parse a display-formatted date string back to a DateTime instance
   * using this.format as the template.
   *
   * @param {string} value
   * @return {import('../../../../js/helpers/date').DateTime|null}
   */
  parseFromFormat(value) {
    const tokenOrder = [];
    let regexStr = '';
    let i = 0;

    while (i < this.format.length) {
      if (this.format.slice(i, i + 4) === 'YYYY') {
        tokenOrder.push('YYYY');
        regexStr += '(\\d{4})';
        i += 4;
      } else if (this.format.slice(i, i + 2) === 'MM') {
        tokenOrder.push('MM');
        regexStr += '(\\d{1,2})';
        i += 2;
      } else if (this.format.slice(i, i + 2) === 'DD') {
        tokenOrder.push('DD');
        regexStr += '(\\d{1,2})';
        i += 2;
      } else {
        regexStr += this.format[i].replace(/[.*+?^${}()|[\]\\]/, '\\$&');
        i++;
      }
    }

    const match = new RegExp(`^${regexStr}$`).exec(value);
    if (!match) return null;

    let year, month, day;

    tokenOrder.forEach((token, idx) => {
      const val = parseInt(match[idx + 1], 10);
      if (token === 'YYYY') year = val;
      else if (token === 'MM') month = val;
      else if (token === 'DD') day = val;
    });

    if (!year || !month || !day) return null;

    return datetime(
      `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`
    );
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

    input.value = !this.model
      ? ''
      : typeof this.model === 'string'
        ? this.model
        : JSON.stringify(this.model);
  },
  /**
   * Get the type of the date calendar.
   *
   * @return {String}
   */
  get type() {
    return multiple ? 'multiple' : range ? 'range' : 'single';
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
