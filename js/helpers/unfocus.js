/**
 * Blocks pointer interaction from focusing elements flagged with
 * data-tsui-unfocus. Keyboard navigation is untouched, so the focus
 * ring is still rendered when the element is reached through the Tab key.
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

    event.preventDefault();
  });
};
