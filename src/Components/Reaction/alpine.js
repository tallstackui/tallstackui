import { place } from '../../../js/helpers/placement';

const OFFSET = 10;
const PADDING = 8;
const ARROW = 8;

// The panel corners are rounded-lg (8px): the inset keeps the whole rotated
// arrow (bounding box ~11.3px) clear of the corner arc when it clamps.
const INSET = 16;

export default (model, content, position, delay = null, balloon = null, hover = false) => {
  let popover = null;
  let arrow = null;
  let frame = null;
  let follow = null;
  let outside = null;
  let escape = null;

  return {
    show: false,
    quantity: model,
    timeout: null,
    init() {
      follow = () => this.reposition();

      outside = (event) => {
        if (popover?.contains(event.target) || this.$refs.button.contains(event.target)) {
          return;
        }

        this.show = false;
      };

      escape = (event) => {
        if (event.key !== 'Escape') {
          return;
        }

        this.show = false;
      };

      this.$watch('show', (value) => {
        if (value) {
          this.open();

          return;
        }

        this.close();
      });
    },
    // The pointerType guard restricts hover to real pointers: on touch the tap
    // fires pointerenter right before click, and the two would cancel each other.
    enter(event) {
      if (!hover || event.pointerType !== 'mouse') {
        return;
      }

      clearTimeout(this.timeout);

      this.show = true;
    },
    // The delay is a grace period to cross the offset gap between the trigger
    // and the floating panel without collapsing the popover mid-way.
    leave(event) {
      if (!hover || event.pointerType !== 'mouse') {
        return;
      }

      clearTimeout(this.timeout);

      this.timeout = setTimeout(() => {
        this.show = false;
      }, 300);
    },
    toggle(event) {
      if (hover && event.pointerType === 'mouse') {
        return;
      }

      this.show = !this.show;
    },
    attach() {
      window.addEventListener('scroll', follow, { capture: true, passive: true });
      window.addEventListener('resize', follow, { passive: true });
      document.addEventListener('pointerdown', outside, true);
      document.addEventListener('keydown', escape);
    },
    // Kept inside the `wire:ignore` wrapper, never in `<body>`: `$wire` walks
    // upwards for a Livewire root, and outside of one it silently degrades to
    // a no-op, so the emoji buttons would stop reaching the server.
    build() {
      if (popover?.isConnected) {
        return;
      }

      popover = document.createElement('div');
      popover.setAttribute('data-tsui-popover', '');
      popover.setAttribute('dusk', 'tallstackui_reaction_popover');
      popover.innerHTML = content;

      if (delay === 'flash') {
        popover.setAttribute('data-instant', '');
      } else if (delay) {
        popover.setAttribute('data-delay', delay);
      }

      if (balloon) {
        popover.setAttribute('data-color', balloon);
        popover.style.setProperty(
          '--tsui-popover-bg',
          balloon === 'black' ? 'var(--color-black)' : `var(--color-${balloon}-600)`
        );
        popover.style.setProperty(
          '--tsui-popover-border',
          balloon === 'black' ? 'var(--color-black)' : `var(--color-${balloon}-700)`
        );
      }

      arrow = document.createElement('span');
      arrow.setAttribute('data-arrow', '');

      popover.append(arrow);

      // The panel is `position: fixed`, so it does not sit in the wrapper's
      // hit box. Without these, crossing the offset gap collapses the hover.
      if (hover) {
        popover.addEventListener('pointerenter', (event) => this.enter(event));
        popover.addEventListener('pointerleave', (event) => this.leave(event));
      }

      this.$refs.button.parentElement.append(popover);
    },
    close() {
      cancelAnimationFrame(frame);

      popover?.removeAttribute('data-show');

      this.detach();
    },
    destroy() {
      clearTimeout(this.timeout);

      this.detach();

      popover?.remove();
      popover = null;
      arrow = null;
    },
    detach() {
      window.removeEventListener('scroll', follow, { capture: true });
      window.removeEventListener('resize', follow);
      document.removeEventListener('pointerdown', outside, true);
      document.removeEventListener('keydown', escape);
    },
    open() {
      this.build();
      this.reposition();

      cancelAnimationFrame(frame);

      frame = requestAnimationFrame(() => popover.setAttribute('data-show', ''));

      this.attach();
    },
    reposition() {
      if (!popover) {
        return;
      }

      const {
        x,
        y,
        side,
        arrow: point,
      } = place(this.$refs.button, popover, {
        placement: position,
        offset: OFFSET,
        padding: PADDING,
        arrow: INSET,
      });

      popover.style.translate = `${x}px ${y}px`;
      popover.setAttribute('data-side', side);

      const half = ARROW / 2;

      // clientTop/clientLeft are the panel border widths: offsetHeight is
      // border-box while an absolute child is measured from the padding box,
      // so without the discount the arrow detaches by the border width.
      const edge = {
        top: popover.offsetHeight - half - popover.clientTop,
        bottom: -half - popover.clientTop,
        left: popover.offsetWidth - half - popover.clientLeft,
        right: -half - popover.clientLeft,
      }[side];

      if (side === 'top' || side === 'bottom') {
        arrow.style.left = `${point - half}px`;
        arrow.style.top = `${edge}px`;

        return;
      }

      arrow.style.top = `${point - half}px`;
      arrow.style.left = `${edge}px`;
    },
  };
};
