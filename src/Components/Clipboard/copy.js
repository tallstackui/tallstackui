import ClipboardJS from 'clipboard/dist/clipboard';
import { event } from '../../../js/helpers';

/**
 * Copy arbitrary text to the clipboard using the bundled ClipboardJS.
 *
 * A detached trigger element is created on the fly so the copy can run
 * without an existing DOM target, then destroyed once the operation
 * settles. Resolves to whether the copy succeeded and dispatches the
 * `ts-ui:copy` event on `window` carrying the copied text.
 *
 * @param {String} text
 * @return {Promise<Boolean>}
 */
export default (text) =>
  new Promise((resolve) => {
    const trigger = document.createElement('button');

    trigger.type = 'button';
    trigger.setAttribute('aria-hidden', 'true');

    // Pin the throwaway trigger to the current viewport and keep it out of the
    // layout flow, otherwise clicking it would scroll the page down to the
    // element appended at the bottom of the body.
    trigger.style.cssText =
      'position:fixed;top:0;left:0;width:1px;height:1px;padding:0;border:0;opacity:0;pointer-events:none;';

    document.body.appendChild(trigger);

    const clipboard = new ClipboardJS(trigger, { text: () => text });

    const cleanup = () => {
      clipboard.destroy();
      trigger.remove();
    };

    clipboard.on('success', (action) => {
      action.clearSelection();

      event('copy', { text });

      cleanup();

      resolve(true);
    });

    clipboard.on('error', () => {
      cleanup();

      resolve(false);
    });

    trigger.click();
  });
