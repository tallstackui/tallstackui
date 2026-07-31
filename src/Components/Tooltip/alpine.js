import { place } from '../../../js/helpers/placement';
import { unique } from '../../../js/helpers';

const OFFSET = 10;
const PADDING = 8;
const ARROW = 8;
const INSET = 12;

const DELAYS = { slow: 400, fast: 150, faster: 75, flash: 0 };
const DELAY = 'fast';

/*
One balloon serves the whole page. Tooltips are mutually exclusive by nature,
so a node per anchor would only pile up detached elements every time Livewire
morphs a toolbar.
*/
let balloon = null;
let content = null;
let arrow = null;

let anchor = null;
let pointer = 'mouse';
let timer = null;
let frame = null;
let observer = null;

// Read when the attribute is not in the DOM, which is the case under a
// custom Alpine prefix.
const sentences = new WeakMap();

// Published by the script directive onto its own tag: anchors reached by the
// directive have no component instance to carry the config for them.
let globals = null;

const global = (key) => {
  if (globals === null) {
    const tag = document.querySelector(
      'script[data-tsui-tooltip-delay], script[data-tsui-tooltip-color]'
    );

    globals = tag ? { ...tag.dataset } : {};
  }

  return globals[key] ?? null;
};

const build = () => {
  if (balloon?.isConnected) {
    return;
  }

  balloon = document.createElement('div');
  balloon.id = `tsui-tooltip-${unique()}`;
  balloon.setAttribute('data-tsui-tooltip', '');
  balloon.setAttribute('role', 'tooltip');

  content = document.createElement('span');

  arrow = document.createElement('span');
  arrow.setAttribute('data-arrow', '');

  balloon.append(content, arrow);
  document.body.append(balloon);
};

const disabled = (el) => {
  const value = el.getAttribute('data-tooltip-disabled');

  return value !== null && value !== 'false';
};

const sentence = (el) => el.getAttribute('x-tooltip') ?? sentences.get(el) ?? '';

const wait = (el) => {
  const name = el.getAttribute('data-tooltip-delay') ?? global('tsuiTooltipDelay') ?? DELAY;

  return DELAYS[name] ?? DELAYS[DELAY];
};

// Theme variables instead of literal values, so any palette the app adds to
// `@theme` works without a color map to keep in sync.
const paint = (el) => {
  const color = el.getAttribute('data-tooltip-color') ?? global('tsuiTooltipColor');

  if (!color) {
    balloon.removeAttribute('data-color');
    balloon.style.removeProperty('--tsui-tooltip-bg');
    balloon.style.removeProperty('--tsui-tooltip-fg');

    return;
  }

  balloon.setAttribute('data-color', color);
  balloon.style.setProperty(
    '--tsui-tooltip-bg',
    color === 'black' ? 'var(--color-black)' : `var(--color-${color}-600)`
  );
  balloon.style.setProperty('--tsui-tooltip-fg', 'var(--color-white)');
};

const reposition = () => {
  if (!anchor?.isConnected || disabled(anchor)) {
    hide();

    return;
  }

  const {
    x,
    y,
    side,
    arrow: point,
  } = place(anchor, balloon, {
    placement: anchor.getAttribute('data-position') || 'top',
    offset: OFFSET,
    padding: PADDING,
    arrow: INSET,
  });

  balloon.style.translate = `${x}px ${y}px`;
  balloon.setAttribute('data-side', side);

  const half = ARROW / 2;

  const edge = {
    top: balloon.offsetHeight - half,
    bottom: -half,
    left: balloon.offsetWidth - half,
    right: -half,
  }[side];

  if (side === 'top' || side === 'bottom') {
    arrow.style.left = `${point - half}px`;
    arrow.style.top = `${edge}px`;

    return;
  }

  arrow.style.top = `${point - half}px`;
  arrow.style.left = `${edge}px`;
};

const track = () => {
  cancelAnimationFrame(frame);

  frame = requestAnimationFrame(reposition);
};

// A tapped tooltip has no pointer to follow, so scrolling closes it instead
// of dragging it along.
const scrolled = () => {
  if (pointer === 'touch' || pointer === 'pen') {
    hide();

    return;
  }

  track();
};

const outside = (event) => {
  if (anchor?.contains(event.target)) {
    return;
  }

  hide();
};

const escaped = (event) => {
  if (event.key !== 'Escape') {
    return;
  }

  hide();
};

const listen = () => {
  window.addEventListener('scroll', scrolled, { capture: true, passive: true });
  window.addEventListener('resize', track, { passive: true });
  document.addEventListener('keydown', escaped);
  document.addEventListener('pointerdown', outside, true);
};

const release = () => {
  window.removeEventListener('scroll', scrolled, { capture: true });
  window.removeEventListener('resize', track);
  document.removeEventListener('keydown', escaped);
  document.removeEventListener('pointerdown', outside, true);
};

// The sidebar disables its tooltips while the pointer is still over the item,
// so the flag has to be watched, not only read when the balloon opens.
const watch = (el) => {
  observer?.disconnect();

  observer = new MutationObserver(() => {
    if (!disabled(el)) {
      return;
    }

    hide();
  });

  observer.observe(el, { attributes: true, attributeFilter: ['data-tooltip-disabled'] });
};

const hide = () => {
  clearTimeout(timer);
  cancelAnimationFrame(frame);

  observer?.disconnect();
  observer = null;

  release();

  if (anchor) {
    anchor.removeAttribute('aria-describedby');
    anchor = null;
  }

  balloon?.removeAttribute('data-show');
};

const show = (el, kind) => {
  if (disabled(el)) {
    return;
  }

  const text = sentence(el);

  if (!text) {
    return;
  }

  build();

  if (anchor && anchor !== el) {
    anchor.removeAttribute('aria-describedby');
  }

  anchor = el;
  pointer = kind;

  content.innerHTML = text;

  paint(el);

  // `visibility: hidden` keeps the balloon in layout, so it is measurable
  // before it is ever painted.
  balloon.removeAttribute('data-show');
  reposition();

  // `reposition` drops an anchor that can no longer hold a tooltip.
  if (anchor !== el) {
    return;
  }

  el.setAttribute('aria-describedby', balloon.id);

  cancelAnimationFrame(frame);
  frame = requestAnimationFrame(() => balloon.setAttribute('data-show', ''));

  watch(el);
  listen();
};

export default function (Alpine) {
  document.addEventListener('livewire:navigating', () => {
    hide();

    balloon?.remove();
    balloon = null;
    globals = null;
  });

  /**
   * @param el {HTMLElement}
   * @param expression {String}
   */
  Alpine.directive('tooltip', (el, { expression }, { cleanup }) => {
    sentences.set(el, expression);

    const enter = (event) => {
      if (event.pointerType && event.pointerType !== 'mouse') {
        return;
      }

      clearTimeout(timer);

      timer = setTimeout(() => show(el, 'mouse'), wait(el));
    };

    const leave = (event) => {
      if (event.pointerType && event.pointerType !== 'mouse') {
        return;
      }

      clearTimeout(timer);

      if (anchor !== el) {
        return;
      }

      hide();
    };

    const tap = (event) => {
      if (event.pointerType === 'mouse') {
        return;
      }

      if (anchor === el) {
        hide();

        return;
      }

      show(el, event.pointerType);
    };

    const focus = () => {
      if (anchor === el) {
        return;
      }

      show(el, 'keyboard');
    };

    const blur = () => {
      if (anchor !== el) {
        return;
      }

      hide();
    };

    el.addEventListener('pointerenter', enter);
    el.addEventListener('pointerleave', leave);
    el.addEventListener('pointerdown', tap);
    el.addEventListener('focus', focus);
    el.addEventListener('blur', blur);

    cleanup(() => {
      el.removeEventListener('pointerenter', enter);
      el.removeEventListener('pointerleave', leave);
      el.removeEventListener('pointerdown', tap);
      el.removeEventListener('focus', focus);
      el.removeEventListener('blur', blur);

      if (anchor === el) {
        hide();
      }
    });
  });
}
