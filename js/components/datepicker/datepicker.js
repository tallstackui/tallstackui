export default (rangeMode = false, disabledDates= []) => ({
  datePickerOpen: false,
  datePickerValue: '',
  datePickerFormat: 'YYYY-MM-DD',
  datePickerMonth: '',
  datePickerYear: '',
  datePickerDay: '',
  datePickerHour: '',
  datePickerMinute: '',
  datePickerAmPm: 'AM', // Added property for AM/PM
  datePickerDaysInMonth: [],
  datePickerBlankDaysInMonth: [],
  datePickerMonthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
  datePickerDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
  showYearPicker: false,
  yearRangeStart: 0,
  startDate: null,
  endDate: null,
  rangeMode: rangeMode,
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
    this.datePickerAmPm = currentDate.getHours() >= 12 ? 'PM' : 'AM'; // Set initial AM/PM value
    this.datePickerCalculateDays();
  },
  datePickerDayClicked(day) {
    const selectedDate = new Date(this.datePickerYear, this.datePickerMonth, day);
    if (this.isDateDisabled(selectedDate)) {
      // Don't do anything if date is disabled
      return;
    }
    if (this.rangeMode) {
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
  dateCompare(date1, date2) {
    var data1 = new Date("date");
    var data2 = new Date("Wed Jan 10 2024 00:00:00 GMT-0300 (Horário Padrão de Brasília)");

    if (data1.getTime() === data2.getTime()) {
        console.log("As datas são iguais.");
    } else {
        console.log("As datas são diferentes.");
    }
  },
  datePickerIsToday(day) {
    const today = new Date();
    const d = new Date(this.datePickerYear, this.datePickerMonth, day);
    return today.toDateString() === d.toDateString() ? true : false;
  },
  datePickerCalculateDays() {
    const daysInMonth = new Date(this.datePickerYear, this.datePickerMonth + 1, 0).getDate();
    const dayOfWeek = new Date(this.datePickerYear, this.datePickerMonth).getDay();
    const blankdaysArray = [];
    for (var i = 1; i <= dayOfWeek; i++) {
      blankdaysArray.push(i);
    }
    const daysArray = [];
    for (i = 1; i <= daysInMonth; i++) {
      const d = new Date(this.datePickerYear, this.datePickerMonth, i);
      const isDisabled = this.isDateDisabled(d); // Check if the date is disabled
      daysArray.push({day: i, full: d, isDisabled}); // Store the day number and its disabled status
    }
    this.datePickerBlankDaysInMonth = blankdaysArray;
    this.datePickerDaysInMonth = daysArray;
  },
  datePickerFormatDate(date) {
    const formattedDay = this.datePickerDays[date.getDay()];
    const formattedDate = ('0' + date.getDate()).slice(-2); // appends 0 (zero) in single digit date
    const formattedMonth = this.datePickerMonthNames[date.getMonth()];
    const formattedMonthShortName = this.datePickerMonthNames[date.getMonth()].substring(0, 3);
    const formattedMonthInNumber = ('0' + (date.getMonth() + 1)).slice(-2);
    const formattedYear = date.getFullYear();
    let formattedHour = date.getHours();
    const formattedMinute = ('0' + date.getMinutes()).slice(-2);
    const amPm = formattedHour >= 12 ? 'PM' : 'AM';

    if (this.datePickerFormat === 'M d, Y') {
      return `${formattedMonthShortName} ${formattedDate}, ${formattedYear}`;
    }
    if (this.datePickerFormat === 'MM-DD-YYYY') {
      return `${formattedMonthInNumber}-${formattedDate}-${formattedYear}`;
    }
    if (this.datePickerFormat === 'DD-MM-YYYY') {
      return `${formattedDate}-${formattedMonthInNumber}-${formattedYear}`;
    }
    if (this.datePickerFormat === 'YYYY-MM-DD') {
      return `${formattedYear}-${formattedMonthInNumber}-${formattedDate}`;
    }
    if (this.datePickerFormat === 'D d M, Y') {
      return `${formattedDay} ${formattedDate} ${formattedMonthShortName} ${formattedYear}`;
    }

    // Convert 24h to 12h format if needed
    formattedHour = formattedHour % 12 || 12;

    // Append the time to the formatted date string
    return `${formattedMonth} ${formattedDate}, ${formattedYear} ${formattedHour}:${formattedMinute} ${amPm}`;
  },
  setDate(type) {
    const currentDate = new Date();
    if (type === 'yesterday') {
      currentDate.setDate(currentDate.getDate() - 1);
    } else if (type === 'tomorrow') {
      currentDate.setDate(currentDate.getDate() + 1);
    }
    // No change needed for 'today', as currentDate is already set to now.

    this.datePickerMonth = currentDate.getMonth();
    this.datePickerYear = currentDate.getFullYear();
    this.datePickerDay = currentDate.getDate(); // Use getDate() to get the day of the month
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
    if (this.rangeMode) {
      if (this.startDate) {
        this.datePickerValue = this.startDate ? this.datePickerFormatDate(this.startDate) : '';
        if (this.endDate) {
          this.datePickerValue += ' - ' + (this.endDate ? this.datePickerFormatDate(this.endDate) : '');
          this.datePickerOpen = false;
        }
      } else {
        this.datePickerValue = '';
        this.datePickerOpen = false;
      }
    } else {
      this.datePickerValue = this.startDate ? this.datePickerFormatDate(this.startDate) : '';
      this.datePickerOpen = false;
    }
  },

  datePickerAway() {
    if (this.rangeMode) {
      if (this.endDate) {
        this.datePickerOpen = false;
      }
    } else {
      this.datePickerOpen = false;
    }
  },

  // Function to check if a date is disabled
  isDateDisabled(date) {
    const formattedDate = this.datePickerFormatDate(date);
    return this.disabledDates.includes(formattedDate);
  },

});
