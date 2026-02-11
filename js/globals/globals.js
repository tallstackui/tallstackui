import { event, error } from '../helpers';
import DialogInteraction from '../../src/Components/Dialog/frontend';
import ToastInteraction from '../../src/Components/Toast/frontend';

window.$tsui = {
  open: {
    modal: (name) => event(`modal:${name}-open`, null, false),
    slide: (name) => event(`slide:${name}-open`, null, false),
    select: (name) => event(`select:${name}-open`, null, false),
    commandPalette: () => event('command-palette-open', null, false),
  },
  close: {
    modal: (name) => event(`modal:${name}-close`, null, false),
    slide: (name) => event(`slide:${name}-close`, null, false),
    select: (name) => event(`select:${name}-close`, null, false),
    commandPalette: () => event('command-palette-close', null, false),
  },
  interaction: (type = 'dialog') => {
    const interactions = {
      dialog: DialogInteraction,
      toast: ToastInteraction,
    };

    const InteractionClass = interactions[type];

    if (!InteractionClass) {
      return error(`Unknown interaction type: ${type}`);
    }

    return new InteractionClass();
  },
  focusOn: (name, time = 250) =>
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
    }, time),
};
