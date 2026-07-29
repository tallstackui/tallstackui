import chart from '../src/Components/Chart/alpine';

document.addEventListener('alpine:init', () => {
  Alpine.data('tallstackui_chart', chart);
});
