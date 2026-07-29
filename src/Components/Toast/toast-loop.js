import { event } from '../../../js/helpers';

export default (toast, stacked = false) => ({
  toast: toast,
  show: false,
  stacked: stacked,
  paused: false,
  piled: false,
  observer: null,
  listener: null,
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

      const interval = setInterval(() => {
        if (!this.show) {
          clearInterval(interval);
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

            clearInterval(interval);
          }
        }
      }, time);

      const progress = this.$refs.progress;

      // While piled, the hover belongs to the pile wrapper: the cards slide
      // under a still pointer, so mouseout on them never fires reliably.
      if (!this.stacked) {
        this.$refs.toast.addEventListener('mouseover', () => {
          this.paused = true;
          this.animate(false);
        });

        this.$refs.toast.addEventListener('mouseout', () => {
          this.paused = false;
          this.animate(!this.frozen);
        });
      }

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
    this.observer?.disconnect();

    if (this.listener) {
      document.removeEventListener('visibilitychange', this.listener);
    }
  },
  /**
   * Whether the countdown is on hold, for any reason.
   *
   * @return {Boolean}
   */
  get frozen() {
    return this.paused === true || this.piled === true;
  },
  /**
   * Play or pause the progress bar.
   *
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
   * Hold the countdown while the pile this toast belongs to is expanded,
   * which freezes every toast in it at once.
   *
   * @param {Boolean} expanded
   * @return {void}
   */
  freeze(expanded) {
    this.piled = expanded === true;

    this.animate(!this.frozen);
  },
  /**
   * Report the rendered height to the pile and keep reporting it, since the
   * description collapsing or expanding changes how tall the card is.
   *
   * @return {void}
   */
  measure() {
    if (!this.stacked) {
      return;
    }

    // ResizeObserver already notifies once on observe, with the settled
    // height, so there is no need to report an initial value by hand.
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
