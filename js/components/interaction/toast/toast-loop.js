import {event} from '../../../helpers';

export default (toast) => ({
  toast: toast,
  show: false,
  init() {
    let paused = false;
    let elapsed = 0;

    const time = this.toast.timeout * 10;
    const max = this.toast.timeout * 1000;

    this.$nextTick(() => {
      this.show = true;

      if (this.toast.persistent) {
        return;
      }

      const interval = setInterval(() => {
        if (!this.show) {
          clearInterval(interval);
        } else if (!paused) {
          elapsed += time;

          if (elapsed >= max) {
            this.hide();

            if (toast.hooks?.timeout) {
              Livewire.find(toast.component).call(toast.hooks.timeout.method, toast.hooks.timeout.params);
            }

            event('toast:timeout', this.toast, false);

            clearInterval(interval);
          }
        }
      }, time);

      const progress = this.$refs.progress;

      this.$refs.toast.addEventListener('mouseover', () => {
        paused = true;
        progress.style.webkitAnimationPlayState = 'paused';
        progress.style.animationPlayState = 'paused';
      });

      this.$refs.toast.addEventListener('mouseout', () => {
        paused = false;
        progress.style.webkitAnimationPlayState = 'running';
        progress.style.animationPlayState = 'running';
      });

      document.addEventListener('visibilitychange', () => {
        const remaining = max - elapsed;

        if (remaining > 2000) {
          progress.style.animationDuration = max - elapsed + 'ms';
          progress.classList.remove('animate-progress');
          progress.offsetWidth;
          progress.classList.add('animate-progress');
          progress.style.animationDuration = max - elapsed + 'ms';
        } else {
          this.hide();
        }
      });
    });
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

    Livewire.find(toast.component).call(method, toast.options.confirm.params);
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

    Livewire.find(toast.component).call(method, toast.options.cancel.params);
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
      Livewire.find(toast.component).call(toast.hooks.close.method, toast.hooks.close.params);
    }

    setTimeout(() => {
      this.show = false;
      this.remove(this.toast);
    }, immediately ? 0 : this.toast.timeout * 100);
  },
});
