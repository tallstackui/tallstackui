export default (range = false, format = 'YYYY-MM-DD', min, max, disabledDates = []) => ({
  open: false,
  format: format,
  datePickerValue: '',
  datePickerMonth: '',
  datePickerYear: '',
  datePickerDay: '',
  datePickerHour: '',
  datePickerMinute: '',
  datePickerAmPm: 'AM',
  datePickerDaysInMonth: [],
  datePickerBlankDaysInMonth: [],
  datePickerMonthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July',
    'August', 'September', 'October', 'November', 'December'],
  datePickerDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
  showYearPicker: false,
  yearRangeStart: 0,
  startDate: null,
  endDate: null,
  range: range,
  min: (min !== null) ? new Date(min) : null,
  max: (max !== null) ? new Date(max) : null,
  disabledDates: disabledDates,
  init() {
    currentDate = new Date();
    this.startDate = null;
    this.endDate = null;
    this.datePickerMonth = currentDate.getMonth();
    this.datePickerYear = currentDate.getFullYear();
    this.datePickerDay = currentDate.getDay();
    this.datePickerHour = currentDate.getHours();
    this.datePickerMinute = currentDate.getMinutes();
    this.datePickerAmPm = currentDate.getHours() >= 12 ? 'PM' : 'AM';
    this.datePickerCalculateDays();
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
    for (i = 1; i <= dayOfWeek; i++) {
      blankdaysArray.push(i);
    }
    const daysArray = [];
    for (day = 1; day <= daysInMonth; day++) {
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
    const formattedHour = date.getHours();
    const formattedMinute = ('0' + date.getMinutes()).slice(-2);
    const amPm = formattedHour >= 12 ? 'PM' : 'AM';

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

    // Convert 24h to 12h format if needed
    formattedHour = formattedHour % 12 || 12;

    // Append the time to the formatted date string
    return `${formattedMonth} ${formattedDate}, ${formattedYear} ${formattedHour}:${formattedMinute} ${amPm}`;
  },
  setDate(type) {
    const currentDate = new Date();
    // TODO: You need to check first that the date is not disabled
    if (type === 'yesterday') {
      currentDate.setDate(currentDate.getDate() - 1);
    } else if (type === 'tomorrow') {
      currentDate.setDate(currentDate.getDate() + 1);
    }

    this.startDate = null;
    this.endDate = null;
    // No change needed for 'today', as currentDate is already set to now.

    this.datePickerMonth = currentDate.getMonth();
    this.datePickerYear = currentDate.getFullYear();
    this.datePickerDay = currentDate.getDate();
    this.datePickerHour = currentDate.getHours();
    this.datePickerMinute = currentDate.getMinutes();
    this.datePickerAmPm = currentDate.getHours() >= 12 ? 'PM' : 'AM';
    this.datePickerValue = this.datePickerFormatDate(currentDate);
    this.datePickerCalculateDays(); // Important to recalculate the days for the new month/year
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
    if (this.range) {
      if (this.startDate) {
        this.datePickerValue = this.startDate ? this.datePickerFormatDate(this.startDate) : '';
        if (this.endDate) {
          this.datePickerValue += ' até ' + (this.endDate ? this.datePickerFormatDate(this.endDate) : '');
          this.open = false;
        }
      } else {
        this.datePickerValue = '';
        this.open = false;
      }
    } else {
      this.datePickerValue = this.startDate ? this.datePickerFormatDate(this.startDate) : '';
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
  isDateDisabled(date) {
    const formattedDate = date.toISOString().slice(0, 10);

    if (this.min && date <= this.min || this.max && date >= this.max) {
      return true;
    }

    return this.disabledDates.includes(formattedDate);
  },
  clear() {
    this.datePickerValue = null;
    this.startDate = null;
    this.endDate = null;
  },
});
