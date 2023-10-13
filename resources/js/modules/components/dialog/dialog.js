import {dispatchEvent, overflow} from '../../helpers';

export default (ok, confirm, cancel) => ({
  show: false,
  dialog: {},
  text: {
    ok: ok,
    confirm: confirm,
    cancel: cancel,
  },
  init() {
    this.$watch('show', (value) => overflow(value));
  },
  add(dialog) {
    this.show = true;

    this.dialog = dialog;
  },
  remove() {
    this.show = false;
  },
  accept(dialog) {
    if (dialog.type !== 'question') {
      return this.remove();
    }

    const params = dialog.options.confirm.params ?? null;

    dispatchEvent('dialog:accepted', dialog);
    setTimeout(() => this.$dispatch(dialog.options.confirm.event, params.constructor !== Array ? [params] : [...params]), 100);

    this.remove();
  },
  reject(dialog) {
    if (dialog.type !== 'question') {
      return this.remove();
    }

    const params = dialog.options.cancel.params ?? null;

    dispatchEvent('dialog:rejected', dialog);
    setTimeout(() => this.$dispatch(dialog.options.cancel.event, params?.constructor !== Array ? [params] : [...params]), 100);

    this.remove();
  },
});
