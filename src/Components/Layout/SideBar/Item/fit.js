/**
 * Caps the collapsed-group flyout at the space left under the trigger.
 */
export default function fit(panel, button) {
  const scroll = panel.querySelector('[data-flyout-scroll]');

  if (!scroll || !button) {
    return;
  }

  const gutter = parseFloat(getComputedStyle(document.documentElement).fontSize) * 2;
  const viewport = window.visualViewport;
  const bottom = viewport ? viewport.offsetTop + viewport.height : window.innerHeight;

  scroll.style.maxHeight = Math.max(0, bottom - button.getBoundingClientRect().top - gutter) + 'px';
}
