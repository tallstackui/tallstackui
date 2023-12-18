export default () => ({
    datePickerOpen: false,
    datePickerValue: '',
    datePickerFormat: 'M d, Y',
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
    init() {
        currentDate = new Date();
        if (this.datePickerValue) {
            currentDate = new Date(Date.parse(this.datePickerValue));
        }
        this.datePickerMonth = currentDate.getMonth();
        this.datePickerYear = currentDate.getFullYear();
        this.datePickerDay = currentDate.getDay();
        this.datePickerHour = currentDate.getHours();
        this.datePickerMinute = currentDate.getMinutes();
        this.datePickerAmPm = currentDate.getHours() >= 12 ? 'PM' : 'AM'; // Set initial AM/PM value
        this.datePickerValue = this.datePickerFormatDate(currentDate);
        this.datePickerCalculateDays();
    },
    datePickerDayClicked(day) {
        let selectedDate = new Date(this.datePickerYear, this.datePickerMonth, day, this.datePickerHour, this.datePickerMinute);
        this.datePickerDay = day;
        this.datePickerValue = this.datePickerFormatDate(selectedDate);
        this.datePickerIsSelectedDate(day);
        this.datePickerOpen = false;
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
        const d = new Date(this.datePickerYear, this.datePickerMonth, day);
        return this.datePickerValue === this.datePickerFormatDate(d) ? true : false;
    },
    datePickerIsToday(day) {
        const today = new Date();
        const d = new Date(this.datePickerYear, this.datePickerMonth, day);
        return today.toDateString() === d.toDateString() ? true : false;
    },
    datePickerCalculateDays() {
        let daysInMonth = new Date(this.datePickerYear, this.datePickerMonth + 1, 0).getDate();
        // find where to start calendar day of week
        let dayOfWeek = new Date(this.datePickerYear, this.datePickerMonth).getDay();
        let blankdaysArray = [];
        for (var i = 1; i <= dayOfWeek; i++) {
            blankdaysArray.push(i);
        }
        let daysArray = [];
        for (var i = 1; i <= daysInMonth; i++) {
            daysArray.push(i);
        }
        this.datePickerBlankDaysInMonth = blankdaysArray;
        this.datePickerDaysInMonth = daysArray;
    },
    datePickerFormatDate(date) {
        let formattedDay = this.datePickerDays[date.getDay()];
        let formattedDate = ('0' + date.getDate()).slice(-2); // appends 0 (zero) in single digit date
        let formattedMonth = this.datePickerMonthNames[date.getMonth()];
        let formattedMonthShortName = this.datePickerMonthNames[date.getMonth()].substring(0, 3);
        let formattedMonthInNumber = ('0' + (parseInt(date.getMonth()) + 1)).slice(-2);
        let formattedYear = date.getFullYear();
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
        return `${formattedMonth} ${formattedDate}, ${formattedYear}`;
    },
    setDate(type) {
        let currentDate = new Date();
        if (type === 'yesterday') {
            currentDate.setDate(currentDate.getDate() - 1);
        } else if (type === 'today') {
            // No change needed for today
        } else if (type === 'tomorrow') {
            currentDate.setDate(currentDate.getDate() + 1);
        }

        this.datePickerMonth = currentDate.getMonth();
        this.datePickerYear = currentDate.getFullYear();
        this.datePickerDay = currentDate.getDay();
        this.datePickerHour = currentDate.getHours();
        this.datePickerMinute = currentDate.getMinutes();
        this.datePickerAmPm = currentDate.getHours() >= 12 ? 'PM' : 'AM';
        this.datePickerValue = this.datePickerFormatDate(currentDate);
        this.datePickerCalculateDays();
    },
    toggleYearPicker() {
        this.showYearPicker = !this.showYearPicker;
        // Initialize the year range starting with the current year
        if (this.showYearPicker) {
            this.yearRangeStart = this.datePickerYear - 11;
        }
    },

    generateYearRange() {
        let startYear = this.yearRangeStart;
        let endYear = startYear + 11;
        return Array.from({ length: endYear - startYear + 1 }, (_, k) => startYear + k);
    },

    previousYearRange() {
        this.yearRangeStart -= 11;
    },

    nextYearRange() {
        this.yearRangeStart += 11;
    },

    selectYear(year) {
        this.datePickerYear = year;
        this.showYearPicker = false;
        this.datePickerCalculateDays();
    }
});
