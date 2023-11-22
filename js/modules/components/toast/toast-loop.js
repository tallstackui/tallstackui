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
    this.$nextTick(() => this.show = true);

    setTimeout(() => {
      this.hide(true);

      dispatchEvent('toast:timeouted', this.toast);
    }, this.toast.timeout * 1000);

    const interval = setInterval(() => {
      if (!this.show) {
        clearInterval(interval);
      }
    }, this.toast.timeout * 10);
  },
  accept(toast) {
    const params = toast.options.confirm.params ?? null;

    dispatchEvent('toast:accepted', toast);

    window.Livewire
        .find(toast.component)
        .call(toast.options.confirm.method, params?.constructor !== Array ? params : [...params]);

    this.hide();
  },
  reject(toast) {
    dispatchEvent('toast:rejected', toast);

    if (toast.options.cancel.method) {
      const params = toast.options.cancel.params;
      window.Livewire
          .find(toast.component)
          .call(toast.options.cancel.method, params?.constructor !== Array ? params : [...params]);
    }

    this.hide();
  },
  hide(imediately = true) {
    setTimeout(() => {
      this.show = false;
      this.remove(this.toast);
    }, imediately ? 0 : this.toast.timeout * 100);
  },
});
