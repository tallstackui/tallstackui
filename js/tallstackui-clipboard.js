import clipboard from '../src/Components/Clipboard/alpine';

document.addEventListener('alpine:init', () => {
  Alpine.data('tallstackui_clipboard', clipboard);
});
