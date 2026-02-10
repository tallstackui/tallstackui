import { event, error } from '../helpers';
import DialogInteraction from '../../src/Components/Dialog/frontend';
import ToastInteraction from '../../src/Components/Toast/frontend';

window.$modalOpen = (name) => event(`modal:${name}-open`, null, false);
window.$modalClose = (name) => event(`modal:${name}-close`, null, false);

window.$slideOpen = (name) => event(`slide:${name}-open`, null, false);
window.$slideClose = (name) => event(`slide:${name}-close`, null, false);

window.$selectOpen = (name) => event(`select:${name}-open`, null, false);
window.$selectClose = (name) => event(`select:${name}-close`, null, false);

window.$commandPaletteOpen = () => event('command-palette-open', null, false);
window.$commandPaletteClose = () => event('command-palette-close', null, false);

const interactions = {
  dialog: DialogInteraction,
  toast: ToastInteraction,
};

window.$interaction = (type = 'dialog') => {
  const InteractionClass = interactions[type];

  if (!InteractionClass) {
    return error(`Unknown interaction type: ${type}`);
  }

  return new InteractionClass();
};

window.$focusOn = (name, time = 250) =>
  setTimeout(() => {
    let element = document.querySelector(`[data-focus="${name}"]`);

    if (!element) {
      element = document.getElementById(name);
    }

    if (!element) {
      element = document.querySelector(`[x-ref="${name}"]`);
    }

    if (element) {
      element.focus();
    }
  }, time);
