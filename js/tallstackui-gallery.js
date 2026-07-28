import gallery from '../src/Components/Gallery/alpine';

document.addEventListener('alpine:init', () => {
  Alpine.data('tallstackui_gallery', gallery);
});
