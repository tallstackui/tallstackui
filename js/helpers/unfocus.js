/**
 * Blocks pointer interaction from focusing elements flagged with
 * data-tsui-unfocus. Keyboard navigation is untouched, so the focus
 * ring is still rendered when the element is reached through the Tab key.
 *
 * A focused field is blurred first: the blocked mousedown would keep it
 * focused, so its pending blur and change events would never fire.
 *
 * @return {void}
 */
export default () => {
  document.addEventListener('mousedown', (event) => {
    const target = event.target;

    if (!target?.closest) {
      return;
    }

    if (!target.closest('[data-tsui-unfocus]')) {
      return;
    }

    const active = document.activeElement;

    if (active?.matches('input, textarea, select') || active?.isContentEditable) {
      active.blur();
    }

    event.preventDefault();
  });
};
