import clipboard from '../src/Components/Clipboard/alpine';

export { default as copy } from '../src/Components/Clipboard/copy';

document.addEventListener('alpine:init', () => {
  Alpine.data('tallstackui_clipboard', clipboard);
});
