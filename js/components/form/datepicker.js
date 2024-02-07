export default (model, range, multiple, format, minDate, maxDate, minYear, maxYear, disable, days, months) => ({
  open: false,
  format: format,
  model: model,
  value: '',
  month: '',
  year: '',
  day: '',
  daysInMonth: [],
  blankDaysInMonth: [],
  monthNames: months,
  days: days,
  showYearPicker: false,
  showMonthPicker: false,
  yearRangeStart: 0,
  yearRangeFirst: 0,
  yearRangeLast: 0,
  startDate: null,
  endDate: null,
  range: range,
  multiple: multiple,
  minDate: (minDate !== null) ? new Date(minDate) : null,
  maxDate: (maxDate !== null) ? new Date(maxDate) : null,
  minYear: minYear,
  maxYear: maxYear,
  disable: disable,
  interval: null,
  selectedDates: null,
  init() {
    const currentDate = new Date();
    this.month = currentDate.getMonth();
    this.year = currentDate.getFullYear();
    this.day = currentDate.getDay();
    this.calculateDays();

    if (this.model) {
      if (this.range && this.model.length === 2) {
        const startDate = new Date(this.parseDate(this.model[0]));
        const endDate = new Date(this.parseDate(this.model[1]));
        Object.assign(this, {startDate, endDate});
      } else if (this.multiple) {
        this.selectedDates = this.model;
      } else {
        this.startDate = new Date(this.parseDate(this.model));
        this.value = this.formatDisplay(new Date(this.parseDate(this.model)));
        if (this.isDateDisabled(new Date(this.model))) {
          this.clear();
        }
      }

      this.updateInputValue();
      this.open = false;
    }
  },
  parseDate(date) {
    const parts = date.split('-');
    return new Date(parts[0], parts[1] - 1, parts[2]);
  },
  dayClicked(day) {
    const selectedDate = new Date(this.year, this.month, day);
    if (this.isDateDisabled(selectedDate)) {
      return;
    }

    if (this.multiple) {
      if (!this.selectedDates) {
        this.selectedDates = [selectedDate.toISOString().slice(0, 10)];
      } else {
        const index = this.selectedDates.findIndex((date) => date === selectedDate.toISOString().slice(0, 10));

        if (index === -1) {
          this.selectedDates.push(selectedDate.toISOString().slice(0, 10));
        } else {
          this.selectedDates.splice(index, 1);
        }
      }
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
  isSelectedDate(day) {
    if (this.model) {
      const date = new Date(this.year, this.month, day);
      return this.model.includes(date.toISOString().slice(0, 10));
    }

    return false;
  },
  dateInterval(date) {
    return new Date(date) >= new Date(this.startDate) && new Date(date) <= new Date(this.endDate);
  },
  calculateDays() {
    const daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
    const dayOfWeek = new Date(this.year, this.month, 1).getDay();
    const blankdaysArray = [];
    for (let i = 1; i <= dayOfWeek; i++) {
      blankdaysArray.push(i);
    }
    const daysArray = [];
    for (let day = 1; day <= daysInMonth; day++) {
      const date = new Date(this.year, this.month, day);
      const isDisabled = this.isDateDisabled(date);
      daysArray.push({day: day, full: date, isDisabled});
    }
    this.blankDaysInMonth = blankdaysArray;
    this.daysInMonth = daysArray;
  },
  formatDisplay(date) {
    const formattedDay = this.days[date.getDay()];
    const formattedDate = ('0' + date.getDate()).slice(-2);
    const formattedMonth = this.monthNames[date.getMonth()];
    const formattedMonthShortName = this.monthNames[date.getMonth()].substring(0, 3);
    const formattedMonthInNumber = ('0' + (date.getMonth() + 1)).slice(-2);
    const formattedYear = date.getFullYear();

    // Handle predefined formats
    if (this.format === 'M d, Y') {
      return `${formattedMonthShortName} ${formattedDate}, ${formattedYear}`;
    }
    if (this.format === 'MM-DD-YYYY') {
      return `${formattedMonthInNumber}-${formattedDate}-${formattedYear}`;
    }
    if (this.format === 'DD-MM-YYYY') {
      return `${formattedDate}-${formattedMonthInNumber}-${formattedYear}`;
    }
    if (this.format === 'YYYY-MM-DD') {
      return `${formattedYear}-${formattedMonthInNumber}-${formattedDate}`;
    }
    if (this.format === 'D d M, Y') {
      return `${formattedDay} ${formattedDate} ${formattedMonthShortName} ${formattedYear}`;
    }

    return `${formattedMonth} ${formattedDate}, ${formattedYear}`;
  },
  changeDate(type) {
    const currentDate = new Date();

    if (type === 'yesterday') {
      currentDate.setDate(currentDate.getDate() - 1);
    } else if (type === 'tomorrow') {
      currentDate.setDate(currentDate.getDate() + 1);
    } else if (type === 'last7days') {
      if (this.range) {
        this.helperRange(7);
        return;
      } else {
        currentDate.setDate(currentDate.getDate() - 7);
      }
    } else if (type === 'last15days') {
      if (this.range) {
        this.helperRange(15);
        return;
      } else {
        currentDate.setDate(currentDate.getDate() - 15);
      }
    } else if (type === 'last30days') {
      if (this.range) {
        this.helperRange(30);
        return;
      } else {
        currentDate.setDate(currentDate.getDate() - 30);
      }
    }

    // No change needed for 'today', as currentDate is already set to now.

    this.month = currentDate.getMonth();
    this.year = currentDate.getFullYear();
    this.day = currentDate.getDate();
    this.value = this.formatDisplay(currentDate);
    this.calculateDays();

    this.daysInMonth.forEach((date) => {
      const current = currentDate.toISOString().slice(0, 10);
      const selected = date.full.toISOString().slice(0, 10);
      if (current === selected && date.isDisabled === true) {
        this.value = '';
      }
    });
  },
  helperRange(time) {
    const currentDate = new Date();
    const startDate = new Date(currentDate - time * 24 * 60 * 60 * 1000);
    startDate.setHours(0, 0, 0, 0);
    const endDate = new Date(currentDate);
    endDate.setHours(0, 0, 0, 0);

    if (this.isDateDisabled(startDate) || this.isDateDisabled(endDate)) {
      return;
    }

    Object.assign(this, {startDate, endDate});
    this.updateInputValue();
  },
  updateInputValue() {
    const startDateValue = this.startDate ? this.formatDisplay(new Date(this.startDate)) : '';
    const endDateValue = this.endDate ? this.formatDisplay(new Date(this.endDate)) : '';

    if (this.multiple) {
      this.model = this.selectedDates;
      this.value = this.model.join(', ');
    } else if (this.range) {
      this.model = [
        this.startDate.toISOString().slice(0, 10),
                this.endDate !== null ? this.endDate.toISOString().slice(0, 10) : null,
      ];
      this.value = startDateValue + ' - ' + endDateValue;
      this.open = this.startDate !== null;
    } else {
      this.model = this.startDate.toISOString().slice(0, 10);
      this.value = startDateValue;
      this.open = false;
    }
  },
  datePickerAway() {
    if (this.range) {
      if (this.endDate) {
        this.open = false;
      }
    } else {
      this.open = false;
    }
  },
  isToday(day) {
    const today = new Date();
    const date = new Date(this.year, this.month, day);
    return today.toDateString() === date.toDateString();
  },
  isDateDisabled(date) {
    const formattedDate = date.toISOString().slice(0, 10);
    return (this.minDate && date <= this.minDate) ||
            (this.maxDate && date >= this.maxDate) ||
            this.disable.includes(formattedDate);
  },
  previousMonth() {
    if (this.month == 0) {
      this.year--;
      this.month = 12;
    }
    this.month--;
    this.calculateDays();
  },
  nextMonth() {
    if (this.month == 11) {
      this.month = 0;
      this.year++;
    } else {
      this.month++;
    }
    this.calculateDays();
  },
  previousYearRange(e) {
    e.stopPropagation();

    if (this.minYear !== null && this.yearRangeFirst <= this.minYear) {
      return;
    }

    this.yearRangeStart -= 19;
  },
  nextYearRange(e) {
    e.stopPropagation();
    if (this.maxYear !== null && this.yearRangeLast >= this.maxYear) {
      return;
    }

    this.yearRangeStart += 19;
  },
  generateYearRange() {
    const startYear = this.yearRangeStart;
    const endYear = startYear + 19;

    const minYear = this.minYear !== null ? this.minYear : -Infinity;
    const maxYear = this.maxYear !== null ? this.maxYear : Infinity;

    const yearRange = Array.from({length: endYear - startYear + 1}, (_, k) => startYear + k)
        .filter((year) => year >= minYear && year <= maxYear);

    this.yearRangeFirst = yearRange[0];
    this.yearRangeLast = yearRange[yearRange.length - 1];

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
  clear() {
    this.model = this.value = this.startDate = this.endDate = this.selectedDates = null;
  },
});
