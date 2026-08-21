import chart from '../src/Components/Chart/alpine';
import axis from '../src/Components/Chart/axis';

document.addEventListener('alpine:init', () => {
  Alpine.data('tallstackui_chart', chart);
  Alpine.data('tallstackui_chartAxis', axis);
});
