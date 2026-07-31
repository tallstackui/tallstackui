const SIDES = ['top', 'bottom', 'left', 'right'];

const OPPOSITE = { top: 'bottom', bottom: 'top', left: 'right', right: 'left' };

const clamp = (value, min, max) => Math.min(Math.max(value, min), Math.max(min, max));

const parse = (placement) => {
  const [side, alignment = 'center'] = String(placement || 'top').split('-');

  return { side, alignment };
};

const room = (anchor, viewport, padding) => ({
  top: anchor.top - padding,
  bottom: viewport.height - anchor.bottom - padding,
  left: anchor.left - padding,
  right: viewport.width - anchor.right - padding,
});

/**
 * Places a floating element against an anchor, in viewport coordinates, with
 * flip on the main axis and shift on the cross axis.
 *
 * Sizes come from `offsetWidth`/`offsetHeight` on purpose: the element is
 * measured while it still carries the closed state's `scale`, and a bounding
 * rect would report the scaled size.
 *
 * @param reference {HTMLElement}
 * @param floating {HTMLElement}
 * @param options {{placement?: String, offset?: Number, padding?: Number, arrow?: Number}}
 * @returns {{x: Number, y: Number, side: String, alignment: String, arrow: Number}}
 */
export const place = (reference, floating, options = {}) => {
  const { placement = 'top', offset = 8, padding = 8, arrow = 0 } = options;

  const anchor = reference.getBoundingClientRect();
  const size = { width: floating.offsetWidth, height: floating.offsetHeight };

  const viewport = {
    width: document.documentElement.clientWidth,
    height: document.documentElement.clientHeight,
  };

  const space = room(anchor, viewport, padding);

  const fits = (side) => {
    const needed = side === 'top' || side === 'bottom' ? size.height : size.width;

    return space[side] >= needed + offset;
  };

  const roomiest = () =>
    SIDES.reduce((current, side) => (space[side] > space[current] ? side : current));

  let { side, alignment } = parse(placement);

  if (side === 'auto') {
    side = [...SIDES].sort((a, b) => space[b] - space[a]).find(fits) ?? roomiest();
  } else if (!fits(side) && fits(OPPOSITE[side])) {
    side = OPPOSITE[side];
  }

  const horizontal = side === 'top' || side === 'bottom';

  const main = {
    top: anchor.top - size.height - offset,
    bottom: anchor.bottom + offset,
    left: anchor.left - size.width - offset,
    right: anchor.right + offset,
  }[side];

  const align = (start, length, extent) => {
    if (alignment === 'start') {
      return start;
    }

    if (alignment === 'end') {
      return start + length - extent;
    }

    return start + length / 2 - extent / 2;
  };

  let x = horizontal ? align(anchor.left, anchor.width, size.width) : main;
  let y = horizontal ? main : align(anchor.top, anchor.height, size.height);

  // Both axes, not only the cross one: when neither side fits, overlapping
  // the anchor still beats rendering off-screen.
  x = clamp(x, padding, viewport.width - size.width - padding);
  y = clamp(y, padding, viewport.height - size.height - padding);

  const center = horizontal ? anchor.left + anchor.width / 2 : anchor.top + anchor.height / 2;
  const origin = horizontal ? x : y;
  const extent = horizontal ? size.width : size.height;

  return {
    x: Math.round(x),
    y: Math.round(y),
    side,
    alignment,
    arrow: Math.round(clamp(center - origin, arrow, extent - arrow)),
  };
};
