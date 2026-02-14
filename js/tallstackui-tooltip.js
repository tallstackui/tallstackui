import reaction from '../src/Components/Reaction/alpine';
import tooltip from '../src/Components/Tooltip/alpine';

document.addEventListener('alpine:init', () => {
  Alpine.plugin(tooltip);
  Alpine.data('tallstackui_reaction', reaction);
});
