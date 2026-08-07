import { place } from '../../../js/helpers/placement';

const OFFSET = 10;
const PADDING = 8;
const ARROW = 8;

// The panel corners are rounded-lg (8px): the inset keeps the whole rotated
// arrow (bounding box ~11.3px) clear of the corner arc when it clamps.
const INSET = 16;

export default (model, content, position, flash = false) => {
  let popover = null;
  let arrow = null;
  let frame = null;
  let follow = null;
  let outside = null;
  let escape = null;

  return {
    show: false,
    quantity: model,
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

      // The flash global: the balloon appears and disappears instantly.
      if (flash) {
        popover.setAttribute('data-instant', '');
      }

      arrow = document.createElement('span');
      arrow.setAttribute('data-arrow', '');

      popover.append(arrow);

      this.$refs.button.parentElement.append(popover);
    },
    close() {
      cancelAnimationFrame(frame);

      popover?.removeAttribute('data-show');

      this.detach();
    },
    destroy() {
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
