/**
 * Copy text through the legacy, origin-agnostic path.
 *
 * `execCommand` copies the current selection instead of an argument, hence
 * the throwaway textarea. The font size keeps iOS from zooming when the
 * element takes focus, and the range covers the selection quirk of readonly
 * fields on the same platform.
 *
 * @param {String} text
 * @return {Boolean}
 */
const legacy = (text) => {
  const area = document.createElement('textarea');

  area.value = text;
  area.setAttribute('readonly', '');
  area.setAttribute('aria-hidden', 'true');

  // Pinned to the viewport and out of the layout flow, otherwise selecting it
  // would scroll the page down to the element appended at the bottom of body.
  area.style.cssText =
    'position:fixed;top:0;left:0;width:1px;height:1px;padding:0;border:0;opacity:0;font-size:16px;';

  document.body.appendChild(area);

  area.select();
  area.setSelectionRange(0, text.length);

  let copied = false;

  try {
    copied = document.execCommand('copy');
  } catch {
    copied = false;
  }

  area.remove();

  window.getSelection()?.removeAllRanges();

  return copied;
};

/**
 * Write text to the clipboard.
 *
 * The asynchronous Clipboard API is tried first and is only exposed in a
 * secure context: HTTPS, localhost or 127.0.0.1. Everywhere else, and on
 * every rejection, the legacy path takes over so the reachable environments
 * stay the same. Both paths require an active user gesture, so this has to
 * be called straight from the handler, without awaiting anything before it.
 *
 * @param {String} text
 * @return {Promise<Boolean>}
 */
export default async (text) => {
  if (navigator.clipboard?.writeText) {
    try {
      await navigator.clipboard.writeText(text);

      return true;
    } catch {
      // Rejected because the document lost focus, the permission was denied
      // or the gesture expired. The legacy path below still handles those.
    }
  }

  return legacy(text);
};
