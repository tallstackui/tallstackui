import {dispatchEvent} from '../../helpers';

export default (toast, ok, confirm, cancel) => ({
  toast: toast,
  show: false,
  text: {
    ok: ok,
    confirm: confirm,
    cancel: cancel,
  },
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
            this.hide(true);
            dispatchEvent('toast:timeouted', this.toast);
            clearInterval(interval);
          }
        }
      }, time);

      const progress = this.$refs.progress;

      this.$refs.toast.addEventListener('mouseover', () => {
        paused = true;
        progress.style.animationPlayState = 'paused';
      });

      this.$refs.toast.addEventListener('mouseout', () => {
        paused = false;
        progress.style.animationPlayState = 'running';
      });
    });
  },
  accept(toast) {
    const params = toast.options.confirm.params ?? null;

    dispatchEvent('toast:accepted', toast);

    Livewire.find(toast.component)
        .call(toast.options.confirm.method, params?.constructor !== Array ? params : [...params]);

    this.hide();
  },
  reject(toast) {
    dispatchEvent('toast:rejected', toast);

    const method = toast.options.cancel.method;

    if (method) {
      const params = toast.options.cancel.params ?? null;

      Livewire.find(toast.component)
          .call(method, params?.constructor !== Array ? params : [...params]);
    }

    this.hide();
  },
  hide(immediately = true) {
    setTimeout(() => {
      this.show = false;
      this.remove(this.toast);
    }, immediately ? 0 : this.toast.timeout * 100);
  },
});
