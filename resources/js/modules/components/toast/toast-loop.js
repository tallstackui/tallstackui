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
      this.hide();

      dispatchEvent('toast:timeouted', this.toast);
    }, this.toast.timeout * 1000);
  },
  accept(toast) {
    const params = toast.options.confirm.params ?? null;

    dispatchEvent('toast:accepted', toast);
    this.$dispatch(toast.options.confirm.event, params.constructor !== Array ? [params] : [...params]);

    this.hide();
  },
  reject(toast) {
    const params = toast.options.cancel.params ?? null;

    dispatchEvent('toast:rejected', toast);
    this.$dispatch(toast.options.cancel.event, params.constructor !== Array ? [params] : [...params]);

    this.hide();
  },
  hide() {
    this.show = false;

    setTimeout(() => this.remove(this.toast), this.toast.timeout * 1000);
  }
});
