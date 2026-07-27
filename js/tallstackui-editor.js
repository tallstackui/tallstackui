import editor from '../src/Components/Editor/alpine';

document.addEventListener('alpine:init', () => {
  Alpine.data('tallstackui_editor', editor);
});
