import date from '../src/Components/Form/Date/alpine';
import time from '../src/Components/Form/Time/alpine';
import calendar from '../src/Components/Calendar/alpine';

document.addEventListener('alpine:init', () => {
  Alpine.data('tallstackui_formDate', date);
  Alpine.data('tallstackui_formTime', time);
  Alpine.data('tallstackui_calendar', calendar);
});
