import { event } from '../../../js/helpers';
import write from './write';

/**
 * Copy arbitrary text to the clipboard.
 *
 * Resolves to whether the copy succeeded and dispatches the `ts-ui:copy`
 * event on `window` carrying the copied text. Browsers only allow clipboard
 * writes during a user gesture, so this has to be called from an interaction
 * such as a click, never from a timer or an asynchronous callback.
 *
 * @param {String} text
 * @return {Promise<Boolean>}
 */
export default async (text) => {
  const copied = await write(text);

  if (copied) {
    event('copy', { text });
  }

  return copied;
};
