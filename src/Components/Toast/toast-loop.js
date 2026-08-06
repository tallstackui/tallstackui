import { event } from '../../../js/helpers';

// stacked is not mirrored here on purpose: reads fall through to the
// parent pile scope, staying live when the mode flips per event.
export default (toast) => ({
  toast: toast,
  show: false,
  paused: false,
  piled: false,
  observer: null,
  listener: null,
  interval: null,
  init() {
    let elapsed = 0;

    const time = this.toast.timeout * 10;
    const max = this.toast.timeout * 1000;

    this.$nextTick(() => {
      this.show = true;

      this.measure();

      if (this.toast.persistent) {
        return;
      }

      // Kept on the instance so destroy() can reach it. A toast dropped by the
      // parent's flush() keeps `show === true`, so the self-clear below never
      // runs and the timer would outlive the card it belongs to.
      this.interval = setInterval(() => {
        if (!this.show) {
          this.stop();
        } else if (!this.frozen) {
          elapsed += time;

          if (elapsed >= max) {
            this.hide();

            if (toast.hooks?.timeout) {
              Livewire.find(toast.reference).call(
                toast.hooks.timeout.method,
                toast.hooks.timeout.params
              );
            }

            event('toast:timeout', this.toast, false);

            this.stop();
          }
        }
      }, time);

      const progress = this.$refs.progress;

      // While piled, the hover belongs to the pile wrapper: the cards slide
      // under a still pointer, so mouseout on them never fires reliably.
      // Guarded per event instead of skipped, since the mode can flip.
      this.$refs.toast.addEventListener('mouseover', () => {
        if (this.stacked) {
          return;
        }

        this.paused = true;
        this.animate(false);
      });

      this.$refs.toast.addEventListener('mouseout', () => {
        if (this.stacked) {
          return;
        }

        this.paused = false;
        this.animate(!this.frozen);
      });

      this.listener = () => {
        if (document.hidden) {
          return;
        }

        const remaining = max - elapsed;

        if (remaining <= 2000) {
          this.hide();

          return;
        }

        if (!progress) {
          return;
        }

        progress.style.animationDuration = max - elapsed + 'ms';
        progress.classList.remove('animate-progress');
        progress.offsetWidth;
        progress.classList.add('animate-progress');
        progress.style.animationDuration = max - elapsed + 'ms';
      };

      document.addEventListener('visibilitychange', this.listener);
    });
  },
  destroy() {
    this.stop();

    this.observer?.disconnect();

    if (this.listener) {
      document.removeEventListener('visibilitychange', this.listener);
    }
  },
  /**
   * @return {void}
   */
  stop() {
    if (this.interval === null) {
      return;
    }

    clearInterval(this.interval);

    this.interval = null;
  },
  /**
   * @return {Boolean}
   */
  get frozen() {
    return this.paused === true || this.piled === true;
  },
  /**
   * @param {Boolean} running
   * @return {void}
   */
  animate(running) {
    const progress = this.$refs.progress;

    if (!progress) {
      return;
    }

    const state = running ? 'running' : 'paused';

    progress.style.webkitAnimationPlayState = state;
    progress.style.animationPlayState = state;
  },
  /**
   * Driven by the pile, which holds every toast in it at once.
   *
   * @param {Boolean} expanded
   * @return {void}
   */
  freeze(expanded) {
    this.piled = expanded === true;

    this.animate(!this.frozen);
  },
  /**
   * Keeps reporting, not just once: a description collapsing or expanding
   * changes how tall the card is, and the pile has to re-seat around it.
   *
   * @return {void}
   */
  measure() {
    // Always observed, even outside stacked mode: the pile needs every
    // height already known when a later toast switches the mode on.
    // ResizeObserver notifies once on observe, with the settled height, so
    // there is no initial value to report by hand.
    this.observer = new ResizeObserver(() =>
      this.$dispatch('ts-ui:toast-measured', {
        id: this.toast.id,
        height: this.$refs.toast.offsetHeight,
      })
    );

    this.observer.observe(this.$refs.toast);
  },
  /**
   * Accept the toast (by confirming).
   *
   * @param {Object} toast
   * @return {void}
   */
  accept(toast) {
    event('toast:accepted', toast, false);

    if (toast.options.confirm.static === true || toast.options.confirm.method === null) {
      return this.hide();
    }

    // This piece of code was made to allow dialog/toast to be used inside Livewire custom
    // directives in order to pass Livewire's action() as the method to be executed, allowing
    // the fluent execution of the action associated with the directive.
    const method = toast.options.confirm.method;

    this.hide();

    if (typeof method === 'function') {
      method();

      return;
    }

    Livewire.find(toast.reference).call(method, toast.options.confirm.params);
  },
  /**
   * Reject the toast (by cancelling).
   *
   * @param {Object} toast
   * @return {void}
   */
  reject(toast) {
    event('toast:rejected', toast, false);

    if (toast.options.cancel.static === true || toast.options.cancel.method === null) {
      return this.hide();
    }

    // This piece of code was made to allow dialog/toast to be used inside Livewire custom
    // directives in order to pass Livewire's action() as the method to be executed, allowing
    // the fluent execution of the action associated with the directive.
    const method = toast.options.cancel.method;

    this.hide();

    if (typeof method === 'function') {
      method();

      return;
    }

    Livewire.find(toast.reference).call(method, toast.options.cancel.params);
  },
  /**
   * Hide the toast.
   *
   * @param {Boolean} immediately
   * @param {Boolean} internal
   * @return {void}
   */
  hide(immediately = true, internal = true) {
    if (!internal && toast.hooks?.close) {
      Livewire.find(toast.reference).call(toast.hooks.close.method, toast.hooks.close.params);
    }

    setTimeout(
      () => {
        this.show = false;
        this.remove(this.toast);
      },
      immediately ? 0 : this.toast.timeout * 100
    );
  },
});
