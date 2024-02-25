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
  show: false,
  format: format,
  model: model,
  value: '',
  day: '',
  month: '',
  year: '',
  days: [], // days
  blanks: [], // blanks
  showYearPicker: false,
  showMonthPicker: false,
  yearRangeStart: 0, // ??? year: {start, first, last
  yearRangeFirst: 0,
  yearRangeLast: 0,
  startDate: null, // ??? start
  endDate: null, // ??? end
  range: range,
  multiple: multiple,
  minDate: minDate, // ??? date: {min, max}
  maxDate: maxDate, // ??? year: {min, max}
  minYear: minYear,
  maxYear: maxYear,
  disable: disable,
  interval: null,
  selectedDates: null, // selected
  init() {
    const dayjs = this.dayjs;

    if (!dayjs) {
      return error('The dayjs library is not available. Please, review the docs.');
    }

    // this.monthNames = dayjs.months().map((month) => month.charAt(0).toUpperCase() + month.slice(1));
    // this.days = dayjs.weekdaysShort().map((days) => days.charAt(0).toUpperCase() + days.slice(1));

    this.minDate = minDate ? dayjs(minDate) : null;
    this.maxDate = maxDate ? dayjs(maxDate) : null;

    this.month = dayjs().month();
    this.year = dayjs().year();
    this.day = dayjs().day();

    this.calculateDays();

    // Checks if the model is defined and hydrates according to the mode (range, multiple, default) of the datepicker
    if (this.model) {
      if (this.range && this.model.length === 2) {
        Object.assign(this, {startDate: dayjs(this.model[0]).$d, endDate: dayjs(this.model[1]).$d});
      } else if (this.multiple) {
        this.selectedDates = this.model;
      } else {
        this.startDate = dayjs(this.model).$d;
        this.value = dayjs(this.model).format(this.format);
      }

      this.updateInputValue();
      this.show = false;
    }
  },
  /**
   * Based on the type of datepicker, this function treats the clicked date and applies the appropriate
   * formatting and values.
   * @param {string} day
   */
  dayClicked(day) { // ??? clicked
    const selectedDate = this.dayjs(`${this.year}-${this.month + 1}-${day}`);

    if (this.multiple) {
      // Toggle: Add if it doesn't exist, remove if it does
      this.selectedDates = this.selectedDates ?
            this.selectedDates.includes(selectedDate.format('YYYY-MM-DD')) ?
                this.selectedDates.filter((date) => date !== selectedDate.format('YYYY-MM-DD')) :
                [...this.selectedDates, selectedDate.format('YYYY-MM-DD')] :
            [selectedDate.format('YYYY-MM-DD')];
    } else if (this.range) {
      if (this.startDate && !this.endDate && selectedDate > this.startDate) {
        this.endDate = selectedDate;
      } else {
        this.startDate = selectedDate;
        this.endDate = null;
      }
    } else {
      this.startDate = selectedDate;
      this.endDate = null;
    }

    this.updateInputValue();
  },
  /**
   * Checks if the date informed by the model is the same as the loop date
   * @param {string} day
   * @returns boolean
   */
  isSelectedDate(day) {
    if (this.model) {
      const date = this.dayjs(`${this.year}-${this.month + 1}-${day}`);
      return this.model.includes(date.format('YYYY-MM-DD'));
    }

    return false;
  },
  /**
   * Checks if the given date is between the range date in order to colorize the range interval
   * @param {string} date
   * @returns boolean
   */
  dateInterval(date) {
    if (this.range === false || this.endDate === null) return false;

    const currentDate = this.dayjs(date);
    const startDate = this.dayjs(this.startDate);
    const endDate = this.dayjs(this.endDate);

    return currentDate.isAfter(startDate) &&
           currentDate.isBefore(endDate) ||
           currentDate.isSame(startDate) ||
           currentDate.isSame(endDate);
  },
  /**
   * Generate calendar days based on the selected month and year.
   */
  calculateDays() { // calculate
    const dayjs = this.dayjs;

    const daysInMonth = dayjs(`${this.year}-${this.month + 1}-01`).endOf('month').date();
    const dayOfWeek = dayjs(`${this.year}-${this.month + 1}-01`).day();

    this.blanks = Array.from({length: dayOfWeek}, (_, i) => i + 1);

    this.days = Array.from({length: daysInMonth}, (_, day) => {
      const date = dayjs(`${this.year}-${this.month + 1}-${day + 1}`);
      const isDisabled = this.isDateDisabled(date.toDate());
      return {day: day + 1, full: date, isDisabled};
    });
  },
  /**
   * Logic to make the helper buttons work according to the datepicker type
   * @param {string} type
   */
  changeDate(type) {
    let currentDate = this.dayjs();

    if (type === 'yesterday' || type === 'tomorrow') {
      currentDate = currentDate.add(type === 'yesterday' ? -1 : 1, 'day');
    } else if (type.startsWith('last')) {
      if (this.range) {
        const days = parseInt(type.replace('last', ''), 10);
        const startDate = currentDate.subtract(days, 'day').startOf('day');
        const endDate = currentDate.startOf('day');

        if (!this.isDateDisabled(startDate.toDate()) && !this.isDateDisabled(endDate.toDate())) {
          Object.assign(this, {startDate: startDate.toDate(), endDate: endDate.toDate()});
          this.updateInputValue();
          return;
        }
      } else {
        const daysToSubtract = parseInt(type.replace('last', ''), 10);
        currentDate = currentDate.subtract(daysToSubtract, 'day');
      }
    }

    const current = currentDate.format('YYYY-MM-DD');

    this.month = currentDate.month();
    this.year = currentDate.year();
    this.day = currentDate.date();
    this.model = current;
    this.value = currentDate.format(this.format);

    this.calculateDays();

    // Checks if there is a disabled date and if it corresponds to the selected date and clears the value if true
    this.days.forEach((date) => {
      if (current === date.full.format('YYYY-MM-DD') && date.isDisabled) {
        this.value = '';
      }
    });
  },
  /**
   * Handles items according to the datepicker type and display format
   */
  updateInputValue() {
    const startDateFormated = this.startDate ? this.dayjs(this.startDate).format(this.format) : '';
    const endDateFormated = this.endDate ? this.dayjs(this.endDate).format(this.format) : '';

    if (this.multiple) {
      this.model = this.selectedDates;
      this.value = this.model.map((date) => this.dayjs(date).format(this.format)).join(', ');
    } else if (this.range) {
      this.model = [
        this.dayjs(this.startDate).format('YYYY-MM-DD'),
        this.endDate !== null ? this.dayjs(this.endDate).format('YYYY-MM-DD') : null,
      ];
      this.value = startDateFormated + ' - ' + endDateFormated;
      this.show = this.startDate !== null;
    } else {
      this.model = this.startDate ? this.dayjs(this.startDate).format('YYYY-MM-DD') : null;
      this.value = startDateFormated;
      this.show = false;
    }
  },
  isToday(day) {
    const today = this.dayjs();
    const date = this.dayjs(`${this.year}-${this.month + 1}-${day}`);
    return today.isSame(date, 'day');
  },
  isDateDisabled(date) {
    return (this.minDate && date <= this.minDate) ||
            (this.maxDate && date >= this.maxDate) ||
            this.disable.includes(this.dayjs(date).format('YYYY-MM-DD'));
  },
  previousMonth() {
    this.month = (this.month === 0) ? 11 : this.month - 1;

    if (this.month === 11) this.year--;

    this.calculateDays();
  },
  nextMonth() {
    this.month = (this.month + 1) % 12;

    if (this.month === 0) this.year++;

    this.calculateDays();
  },
  previousYearRange(e) {
    e.stopPropagation();

    if (this.minYear !== null && this.yearRangeFirst <= this.minYear) return;

    this.yearRangeStart -= 19;
  },
  nextYearRange(e) {
    e.stopPropagation();

    if (this.maxYear !== null && this.yearRangeLast >= this.maxYear) return;

    this.yearRangeStart += 19;
  },
  generateYearRange() {
    const startYear = this.yearRangeStart;

    const minYear = this.minYear ?? -Infinity;
    const maxYear = this.maxYear ?? Infinity;

    const yearRange = Array.from({length: 20}, (_, index) => {
      const year = startYear + index;
      const disabled = year < minYear || year > maxYear;
      return {year, disabled};
    });

    Object.assign(this, {yearRangeFirst: yearRange[0]?.year, yearRangeLast: yearRange[yearRange.length - 1]?.year});

    return yearRange;
  },
  selectMonth(e, month) {
    e.stopPropagation();

    this.month = month;
    this.showMonthPicker = false;

    this.calculateDays();
  },
  selectYear(e, year) {
    e.stopPropagation();

    this.year = year;
    this.showYearPicker = false;

    this.calculateDays();
  },
  toggleYear() {
    this.showYearPicker = true;

    this.yearRangeStart = this.year - 11;
  },
  /**
   * Reset all properties
   */
  clear() {
    this.model = this.value = this.startDate = this.endDate = this.selectedDates = null;
  },
  // format(date, format)
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
