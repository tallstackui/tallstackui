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
    /** @param {String} id @return {void} */
    commandPalette: (id = 'command-palette') => event(`command-palette:${id}-open`, null, false),
  },
  /** Methods to close components by name. */
  close: {
    /** @param {String} name @return {void} */
    modal: (name) => event(`modal:${name}-close`, null, false),
    /** @param {String} name @return {void} */
    slide: (name) => event(`slide:${name}-close`, null, false),
    /** @param {String} name @return {void} */
    select: (name) => event(`select:${name}-close`, null, false),
    /** @param {String} id @return {void} */
    commandPalette: (id = 'command-palette') => event(`command-palette:${id}-close`, null, false),
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
   * Programmatically interact with a `select.styled` instance by its
   * wrapping element id. Returns a small handle exposing helpers like
   * `setOptions(array)` so JS code can populate options without
   * touching the inner template element directly.
   *
   * @param {String} name - The id of the element wrapping the select
   * @return {{setOptions: function, refresh: function}|void}
   */
  select: (name) => {
    const root = document.getElementById(name);

    if (!root) {
      return error(`Element [#${name}] was not found.`);
    }

    const el = root.querySelector('[x-data^="tallstackui_select"]');

    if (!el) {
      return error(
        `Element [#${name}] does not contain a select.styled component.`,
      );
    }

    const data = Alpine.$data(el);

    return {
      /**
       * Replace the select's options. The data is serialized into
       * the inner template element; the select's MutationObserver
       * picks up the change and syncs reactively.
       *
       * @param {Array<Object|String|Number>} options
       * @return {void}
       */
      setOptions: (options) => {
        if (!data.$refs.options) {
          return error(
            `Element [#${name}] select has no options ref to update.`,
          );
        }

        const encoded = btoa(JSON.stringify(options));
        data.$refs.options.innerText = `JSON.parse(atob('${encoded}'))`;
      },
      /**
       * Force a re-read of the inner template element. Useful when
       * options were modified through a code path that bypassed
       * `setOptions` (e.g. direct DOM manipulation).
       *
       * @return {void}
       */
      refresh: () => data.sync(),
    };
  },
  /**
   * Focus an element by data-focus attribute, id, or x-ref.
   *
   * @param {String} name
   * @param {Number} time - Delay in milliseconds
   * @return {void}
   */
  focus: (name, time = 250) =>
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
