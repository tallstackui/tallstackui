import commandPalette from '../src/Components/CommandPalette/alpine';
import select from '../src/Components/Form/Select/Styled/alpine';

document.addEventListener('alpine:init', () => {
  Alpine.data('tallstackui_commandPalette', commandPalette);
  Alpine.data('tallstackui_select', select);
});
