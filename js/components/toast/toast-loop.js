import {event} from '../../helpers';

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
  accept(toast) {
    event('toast:accepted', toast, false);

    if (toast.options.confirm.static === true || toast.options.confirm.method === null) {
      return this.hide();
    }

    Livewire.find(toast.component)
        .call(toast.options.confirm.method, toast.options.confirm.params);

    this.hide();
  },
  reject(toast) {
    event('toast:rejected', toast, false);

    if (toast.options.cancel.static === true || toast.options.cancel.method === null) {
      return this.hide();
    }

    Livewire.find(toast.component)
        .call(toast.options.cancel.method, toast.options.cancel.params);

    this.hide();
  },
  /**
   * Hide the toast.
   *
   * @param immediately {Boolean}
   * @param internal {Boolean}
   * @return {void}
   */
  hide(immediately = true, internal = true) {
    if (!internal && toast.hooks?.close) {
      Livewire.find(toast.component)
          .call(toast.hooks.close.method, toast.hooks.close.params);
    }

    setTimeout(() => {
      this.show = false;
      this.remove(this.toast);
    }, immediately ? 0 : this.toast.timeout * 100);
  },
});
