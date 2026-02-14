import date from '../src/Components/Form/Date/alpine';
import time from '../src/Components/Form/Time/alpine';

document.addEventListener('alpine:init', () => {
  Alpine.data('tallstackui_formDate', date);
  Alpine.data('tallstackui_formTime', time);
});
