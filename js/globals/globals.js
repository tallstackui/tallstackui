import { event, error } from '../helpers';
import DialogInteraction from '../../src/Components/Dialog/frontend';
import ToastInteraction from '../../src/Components/Toast/frontend';

window.$tsui = {
  /** Methods to open components by name. */
  open: {
    /** @param {String} name @return {void} */
    modal: (name) => event(`modal:${name}-open`, null, false),
    /** @param {String} name @return {void} */
    slide: (name) => event(`slide:${name}-open`, null, false),
    /** @param {String} name @return {void} */
    select: (name) => event(`select:${name}-open`, null, false),
    /** @return {void} */
    commandPalette: () => event('command-palette-open', null, false),
  },
  /** Methods to close components by name. */
  close: {
    /** @param {String} name @return {void} */
    modal: (name) => event(`modal:${name}-close`, null, false),
    /** @param {String} name @return {void} */
    slide: (name) => event(`slide:${name}-close`, null, false),
    /** @param {String} name @return {void} */
    select: (name) => event(`select:${name}-close`, null, false),
    /** @return {void} */
    commandPalette: () => event('command-palette-close', null, false),
  },
  /**
   * Create a dialog or toast interaction instance.
   *
   * @param {String} type - 'dialog' or 'toast'
   * @return {DialogInteraction|ToastInteraction|void}
   */
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
  /**
   * Focus an element by data-focus attribute, id, or x-ref.
   *
   * @param {String} name
   * @param {Number} time - Delay in milliseconds
   * @return {void}
   */
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
