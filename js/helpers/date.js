const NAMES = {
  months: [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
  ],
  weekdays: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
};

// A year, a year-month or a full date, optionally followed by a time, all read
// as local. A string carrying a zone falls through to the native parser.
const PARSABLE =
  /^(\d{4})[-/]?(\d{1,2})?[-/]?(\d{0,2})[T\s]*(\d{1,2})?:?(\d{1,2})?:?(\d{1,2})?[.:]?(\d+)?$/;

const FORMATTABLE =
  /\[([^\]]+)]|Y{1,4}|M{1,4}|D{1,2}|d{1,4}|H{1,2}|h{1,2}|a|A|m{1,2}|s{1,2}|Z{1,2}|SSS/g;

const pad = (value, length = 2) => String(value).padStart(length, '0');

const days = (date) => new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();

const parse = (value) => {
  // Only an omitted value is now: the range input needs an unset end, `null`,
  // to format as `Invalid Date`.
  if (value === null) {
    return new Date(NaN);
  }

  if (value === undefined) {
    return new Date();
  }

  if (value instanceof DateTime) {
    return new Date(value.raw.getTime());
  }

  if (value instanceof Date || typeof value === 'number') {
    return new Date(value);
  }

  const text = String(value);
  const matches = /Z$/i.test(text) ? null : PARSABLE.exec(text);

  if (!matches) {
    return new Date(value);
  }

  const [, year, month, day, hour, minute, second, milliseconds] = matches;

  return new Date(
    year,
    month - 1 || 0,
    day || 1,
    hour || 0,
    minute || 0,
    second || 0,
    (milliseconds || '0').substring(0, 3)
  );
};

/**
 * An immutable local date, covering the slice the Calendar, Date and Time
 * components need.
 */
class DateTime {
  constructor(value) {
    this.raw = parse(value);
  }

  /**
   * @param {Number} value
   * @param {String} unit
   * @returns {DateTime}
   */
  add(value, unit) {
    const date = new Date(this.raw.getTime());

    if (unit !== 'year' && unit !== 'month') {
      date.setDate(date.getDate() + value);

      return new DateTime(date);
    }

    // Off the 1st, so a 31st or a 29th of February clamps to the last day of
    // where it arrives instead of spilling into the next month.
    const day = date.getDate();

    date.setDate(1);

    if (unit === 'year') {
      date.setFullYear(date.getFullYear() + value);
    } else {
      date.setMonth(date.getMonth() + value);
    }

    date.setDate(Math.min(day, days(date)));

    return new DateTime(date);
  }

  /**
   * @returns {Number}
   */
  date() {
    return this.raw.getDate();
  }

  /**
   * The day of the week, from 0 on Sunday.
   *
   * @returns {Number}
   */
  day() {
    return this.raw.getDay();
  }

  /**
   * @param {String} unit
   * @returns {DateTime}
   */
  endOf(unit) {
    const date = new Date(this.raw.getTime());

    if (unit === 'year') {
      date.setMonth(11, 31);
    } else if (unit === 'month') {
      date.setMonth(date.getMonth() + 1, 0);
    }

    date.setHours(23, 59, 59, 999);

    return new DateTime(date);
  }

  /**
   * @param {String} template
   * @returns {String}
   */
  format(template = 'YYYY-MM-DDTHH:mm:ssZ') {
    if (!this.isValid()) {
      return 'Invalid Date';
    }

    const hour = this.hour();
    const offset = -this.raw.getTimezoneOffset();
    const zone = `${offset < 0 ? '-' : '+'}${pad(Math.trunc(Math.abs(offset) / 60))}`;

    const tokens = {
      YY: pad(this.year() % 100),
      YYYY: pad(this.year(), 4),
      M: this.month() + 1,
      MM: pad(this.month() + 1),
      MMM: NAMES.months[this.month()].slice(0, 3),
      MMMM: NAMES.months[this.month()],
      D: this.date(),
      DD: pad(this.date()),
      d: this.day(),
      dd: NAMES.weekdays[this.day()].slice(0, 2),
      ddd: NAMES.weekdays[this.day()].slice(0, 3),
      dddd: NAMES.weekdays[this.day()],
      H: hour,
      HH: pad(hour),
      h: hour % 12 || 12,
      hh: pad(hour % 12 || 12),
      a: hour < 12 ? 'am' : 'pm',
      A: hour < 12 ? 'AM' : 'PM',
      m: this.minute(),
      mm: pad(this.minute()),
      s: this.raw.getSeconds(),
      ss: pad(this.raw.getSeconds()),
      Z: `${zone}:${pad(Math.abs(offset) % 60)}`,
      ZZ: `${zone}${pad(Math.abs(offset) % 60)}`,
      SSS: pad(this.raw.getMilliseconds(), 3),
    };

    return template.replace(FORMATTABLE, (match, literal) => literal ?? String(tokens[match]));
  }

  /**
   * @returns {Number}
   */
  hour() {
    return this.raw.getHours();
  }

  /**
   * @param {*} other
   * @returns {Boolean}
   */
  isAfter(other) {
    return this.valueOf() > new DateTime(other).valueOf();
  }

  /**
   * @param {*} other
   * @returns {Boolean}
   */
  isBefore(other) {
    return this.valueOf() < new DateTime(other).valueOf();
  }

  /**
   * Exclusive on both ends, matching the boundaries the calendar draws: the
   * start and the end of a range are painted as edges, not as in-between days.
   *
   * @param {*} start
   * @param {*} end
   * @returns {Boolean}
   */
  isBetween(start, end) {
    return this.isAfter(start) && this.isBefore(end);
  }

  /**
   * @param {*} other
   * @returns {Boolean}
   */
  isSame(other) {
    return this.valueOf() === new DateTime(other).valueOf();
  }

  /**
   * @returns {Boolean}
   */
  isValid() {
    return !Number.isNaN(this.raw.getTime());
  }

  /**
   * @returns {Number}
   */
  minute() {
    return this.raw.getMinutes();
  }

  /**
   * The month, from 0 in January.
   *
   * @returns {Number}
   */
  month() {
    return this.raw.getMonth();
  }

  /**
   * @param {String} unit
   * @returns {DateTime}
   */
  startOf(unit) {
    const date = new Date(this.raw.getTime());

    if (unit === 'year') {
      date.setMonth(0, 1);
    } else if (unit === 'month') {
      date.setDate(1);
    }

    date.setHours(0, 0, 0, 0);

    return new DateTime(date);
  }

  /**
   * @returns {Date}
   */
  toDate() {
    return new Date(this.raw.getTime());
  }

  /**
   * Lets two instances be compared with the plain relational operators.
   *
   * @returns {Number}
   */
  valueOf() {
    return this.raw.getTime();
  }

  /**
   * @returns {Number}
   */
  year() {
    return this.raw.getFullYear();
  }
}

/**
 * Build a date from a `Date`, another instance, a timestamp or a string. An
 * omitted value is now and a `null` is an invalid date.
 *
 * @param {*} value
 * @returns {DateTime}
 */
export const datetime = (value) => new DateTime(value);

/**
 * @param {*} value
 * @returns {Boolean}
 */
export const isDatetime = (value) => value instanceof DateTime;

/**
 * Replace the month and weekday names the format tokens read. Both arrays are
 * expected in their natural order, January and Sunday first, since they are
 * indexed by what the date itself reports.
 *
 * @param {{months?: String[], weekdays?: String[]}} names
 * @returns {void}
 */
export const localize = ({ months, weekdays }) => {
  if (months) {
    NAMES.months = months;
  }

  if (weekdays) {
    NAMES.weekdays = weekdays;
  }
};
