import { floating_overflow, unique } from '../../../js/helpers';

const FOCUSABLE = [
  'button:not([disabled])',
  '[href]',
  'input:not([disabled]):not([type="hidden"])',
  'select:not([disabled])',
  'textarea:not([disabled])',
  '[tabindex]:not([tabindex="-1"])',
].join(',');

/**
 * Wires the teleported `<x-floating>` popup to its anchor: width sync for
 * `w-full`, modal/slide close hooks, and proactive close when the anchor
 * leaves layout (Tab swap, Accordion collapse, etc.).
 *
 * Do NOT convert to `Alpine.data` — it creates a new scope and breaks
 * `x-show="show"` against the parent (reverted in 6bde57b0). Alpine magics
 * are passed in as callbacks instead.
 */
export default function floating(
  el,
  watch,
  nextTick,
  getAnchor,
  showName,
  getShow,
  setShow,
  lock = false
) {
  const anchor = getAnchor();
  const isWidthFull = el.classList.contains('w-full');
  const id = unique();

  // No-op unless `floating_scroll_lock` is on. Releasing an id that never
  // took the lock is safe, so every teardown path can call this blindly.
  const scrollLock = (status) => {
    if (!lock) {
      return;
    }

    floating_overflow(status, id);
  };

  const setWidth = () => {
    const current = getAnchor();

    if (current && current.offsetWidth) {
      el.style.width = current.offsetWidth + 'px';
    }
  };

  // `getClientRects().length === 0` is the canonical `display:none` signal —
  // robust against `position:fixed`, which still has client rects.
  const anchorVisible = () => {
    const current = getAnchor();

    if (!current || !current.isConnected) {
      return false;
    }

    return current.getClientRects().length > 0;
  };

  // The panel is teleported to the end of `<body>`, so focus that lives inside
  // it dies with it: hiding the element hands activeElement back to `<body>`
  // and the next Tab restarts at the top of the document. Tracked as it happens
  // rather than read on close, since `x-show` may have hidden the panel first.
  let held = false;

  el.addEventListener('focusin', () => (held = true));

  // A relatedTarget outside the panel means the user moved on by themselves.
  // A null one means the panel took the focus down with it, which is the only
  // case worth restoring.
  el.addEventListener('focusout', (event) => {
    if (event.relatedTarget && !el.contains(event.relatedTarget)) {
      held = false;
    }
  });

  const restore = () => {
    if (!held) {
      return;
    }

    held = false;

    if (!anchorVisible()) {
      return;
    }

    const current = getAnchor();
    const target = current.matches(FOCUSABLE) ? current : current.querySelector(FOCUSABLE);

    target?.focus({ preventScroll: true });
  };

  let guardRaf = null;

  const guard = () => {
    if (!el.isConnected) {
      scrollLock(false);
      guardRaf = null;
      return;
    }

    if (!getShow()) {
      guardRaf = null;
      return;
    }

    if (!anchorVisible()) {
      el.style.display = 'none';
      setShow(false);
      guardRaf = null;
      return;
    }

    guardRaf = requestAnimationFrame(guard);
  };

  const startGuard = () => {
    if (!guardRaf) {
      guardRaf = requestAnimationFrame(guard);
    }
  };

  watch(showName, (value) => {
    scrollLock(value);

    if (!value) {
      restore();

      return;
    }

    el.style.display = '';

    startGuard();

    if (isWidthFull && anchor) {
      nextTick(() => setWidth());
    }
  });

  if (getShow()) {
    scrollLock(true);
    startGuard();
  }

  if (isWidthFull && anchor) {
    let widthRaf = null;

    new MutationObserver(() => {
      cancelAnimationFrame(widthRaf);
      widthRaf = requestAnimationFrame(() => setWidth());
    }).observe(el, { childList: true, subtree: true });

    if (getShow()) {
      nextTick(() => setWidth());
    }

    if (window.Livewire?.hook) {
      window.Livewire.hook('commit', ({ succeed }) => {
        succeed(() => {
          nextTick(() => {
            if (el.isConnected && getShow()) {
              setWidth();
            }
          });
        });
      });
    }
  }

  if (anchor) {
    const overlay = anchor.closest('[x-data*=tallstackui_modal], [x-data*=tallstackui_slide]');

    if (overlay) {
      overlay.addEventListener('close', () => setShow(false));
    }
  }

  // Proactive close before the container hides the anchor (Tab/Accordion/etc.),
  // so Floating UI can't reposition to (0, 0). Self-removes on detach to avoid
  // piling up listeners across Livewire morphs.
  const flush = () => {
    if (!el.isConnected) {
      scrollLock(false);
      window.removeEventListener('tallstackui:floating-flush', flush);
      return;
    }

    if (!getShow()) {
      return;
    }

    el.style.display = 'none';
    setShow(false);
  };

  window.addEventListener('tallstackui:floating-flush', flush);
}
