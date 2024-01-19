export default (model, range, format = 'YYYY-MM-DD', min, max, disabledDates = [], days, months) => ({
  open: false,
  model: model,
  format: format,
  datePickerValue: '',
  datePickerMonth: '',
  datePickerYear: '',
  datePickerDay: '',
  datePickerDaysInMonth: [],
  datePickerBlankDaysInMonth: [],
  datePickerMonthNames: months,
  datePickerDays: days,
  showYearPicker: false,
  yearRangeStart: 0,
  startDate: null,
  endDate: null,
  range: range,
  min: (min !== null) ? new Date(min) : null,
  max: (max !== null) ? new Date(max) : null,
  disabledDates: disabledDates,
  init() {
    const currentDate = new Date();
    this.startDate = null;
    this.endDate = null;
    this.datePickerMonth = currentDate.getMonth();
    this.datePickerYear = currentDate.getFullYear();
    this.datePickerDay = currentDate.getDay();
    this.datePickerCalculateDays();
    this.datePickerValue = this.model !== null ? this.model : '';

    if (this.model instanceof Array) {
      const startDate = this.datePickerFormatDate(new Date(this.model[0] + 'T00:00:00Z'));
      const endDate = this.datePickerFormatDate(new Date(this.model[1] + 'T00:00:00Z'));

      this.model = [startDate, endDate];
      Object.assign(this, {startDate, endDate});

      this.updateInputValue();
    } else {
      this.datePickerValue = this.model = this.datePickerFormatDate(new Date(this.datePickerValue));
    }

    this.$watch('datePickerValue', (value) => {
      if (this.range) {
        this.model = value.split(' - ');
      } else {
        this.model = value;
        this.$refs.datePickerInput = value;
      }
    });
  },
  datePickerDayClicked(day) {
    const selectedDate = new Date(this.datePickerYear, this.datePickerMonth, day);
    if (this.isDateDisabled(selectedDate)) {
      // Don't do anything if date is disabled
      return;
    }
    if (this.range) {
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
  datePickerPreviousMonth() {
    if (this.datePickerMonth == 0) {
      this.datePickerYear--;
      this.datePickerMonth = 12;
    }
    this.datePickerMonth--;
    this.datePickerCalculateDays();
  },
  datePickerNextMonth() {
    if (this.datePickerMonth == 11) {
      this.datePickerMonth = 0;
      this.datePickerYear++;
    } else {
      this.datePickerMonth++;
    }
    this.datePickerCalculateDays();
  },
  datePickerIsSelectedDate(day) {
    const date = new Date(this.datePickerYear, this.datePickerMonth, day);
    return this.datePickerValue.includes(this.datePickerFormatDate(date));
  },
  dateInterval(date) {
    return new Date(date) >= new Date(this.startDate) && new Date(date) <= new Date(this.endDate);
  },
  datePickerIsToday(day) {
    const today = new Date();
    const date = new Date(this.datePickerYear, this.datePickerMonth, day);
    return today.toDateString() === date.toDateString() ? true : false;
  },
  datePickerCalculateDays() {
    const daysInMonth = new Date(this.datePickerYear, this.datePickerMonth + 1, 0).getDate();
    const dayOfWeek = new Date(this.datePickerYear, this.datePickerMonth).getDay();
    const blankdaysArray = [];
    for (let i = 1; i <= dayOfWeek; i++) {
      blankdaysArray.push(i);
    }
    const daysArray = [];
    for (let day = 1; day <= daysInMonth; day++) {
      const date = new Date(this.datePickerYear, this.datePickerMonth, day);
      const isDisabled = this.isDateDisabled(date); // Check if the date is disabled
      daysArray.push({day: day, full: date, isDisabled}); // Store the day number and its disabled status
    }
    this.datePickerBlankDaysInMonth = blankdaysArray;
    this.datePickerDaysInMonth = daysArray;
  },
  datePickerFormatDate(date) {
    const formattedDay = this.datePickerDays[date.getDay()];
    const formattedDate = ('0' + date.getDate()).slice(-2);
    const formattedMonth = this.datePickerMonthNames[date.getMonth()];
    const formattedMonthShortName = this.datePickerMonthNames[date.getMonth()].substring(0, 3);
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
  setDate(type) {
    const currentDate = new Date();

    if (type === 'yesterday') {
      currentDate.setDate(currentDate.getDate() - 1);
    } else if (type === 'tomorrow') {
      currentDate.setDate(currentDate.getDate() + 1);
    } else if (type === 'last7days') {
      if (this.range) {
        this.datePickerHelperRange(7);
        return;
      } else {
        currentDate.setDate(currentDate.getDate() - 7);
      }
    } else if (type === 'last15days') {
      if (this.range) {
        this.datePickerHelperRange(15);
        return;
      } else {
        currentDate.setDate(currentDate.getDate() - 15);
      }
    } else if (type === 'last30days') {
      if (this.range) {
        this.datePickerHelperRange(30);
        return;
      } else {
        currentDate.setDate(currentDate.getDate() - 30);
      }
    }

    // No change needed for 'today', as currentDate is already set to now.

    this.datePickerMonth = currentDate.getMonth();
    this.datePickerYear = currentDate.getFullYear();
    this.datePickerDay = currentDate.getDate();
    this.datePickerValue = this.datePickerFormatDate(currentDate);
    this.datePickerCalculateDays(); // Important to recalculate the days for the new month/year

    this.datePickerDaysInMonth.forEach((date) => {
      const current = currentDate.toISOString().slice(0, 10);
      const selected = date.full.toISOString().slice(0, 10);
      if (current === selected && date.isDisabled === true) {
        this.datePickerValue = '';
      }
    });
  },
  toggleYearPicker() {
    this.showYearPicker = true;
    // Initialize the year range starting with the current year
    if (this.showYearPicker) {
      this.yearRangeStart = this.datePickerYear - 11;
    }
  },
  generateYearRange() {
    const startYear = this.yearRangeStart;
    const endYear = startYear + 19;
    return Array.from({length: endYear - startYear + 1}, (_, k) => startYear + k);
  },
  previousYearRange(e) {
    e.stopPropagation();
    this.yearRangeStart -= 19;
  },
  nextYearRange(e) {
    e.stopPropagation();
    this.yearRangeStart += 19;
  },
  selectYear(e, year) {
    e.stopPropagation();
    this.datePickerYear = year;
    this.showYearPicker = false;
    this.datePickerCalculateDays();
  },
  updateInputValue() {
    const startDateValue = this.startDate ? this.datePickerFormatDate(this.startDate) : '';
    const endDateValue = this.endDate ? this.datePickerFormatDate(this.endDate) : '';

    if (this.range) {
      this.datePickerValue = startDateValue + (endDateValue ? ` - ${endDateValue}` : '');
      this.open = this.startDate !== null;
    } else {
      this.datePickerValue = startDateValue;
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
  datePickerHelperRange(time) {
    const currentDate = new Date();
    const startDate = new Date(currentDate - time * 24 * 60 * 60 * 1000);
    startDate.setHours(0, 0, 0, 0);
    const endDate = new Date(currentDate);
    endDate.setHours(0, 0, 0, 0);

    Object.assign(this, {startDate, endDate});
    this.updateInputValue();
  },
  isDateDisabled(date) {
    const formattedDate = date.toISOString().slice(0, 10);
    return (this.min && date <= this.min) || (this.max && date >= this.max) || this.disabledDates.includes(formattedDate);
  },
  clear() {
    this.datePickerValue = '';
    this.startDate = null;
    this.endDate = null;
  },
});
