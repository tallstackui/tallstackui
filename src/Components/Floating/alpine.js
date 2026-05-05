/**
 * Wires the teleported `<x-floating>` popup to its anchor: width sync for
 * `w-full`, modal/slide close hooks, and proactive close when the anchor
 * leaves layout (Tab swap, Accordion collapse, etc.).
 *
 * Do NOT convert to `Alpine.data` — it creates a new scope and breaks
 * `x-show="show"` against the parent (reverted in 6bde57b0). Alpine magics
 * are passed in as callbacks instead.
 */
export default function floating(el, watch, nextTick, getAnchor, showName, getShow, setShow) {
  const anchor = getAnchor();
  const isWidthFull = el.classList.contains('w-full');

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

  let guardRaf = null;

  const guard = () => {
    if (!el.isConnected) {
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
    if (!value) {
      return;
    }

    el.style.display = '';

    startGuard();

    if (isWidthFull && anchor) {
      nextTick(() => setWidth());
    }
  });

  if (getShow()) {
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
