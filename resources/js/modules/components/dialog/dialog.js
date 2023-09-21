import {dispatchEvent} from '../../helpers';

export default (ok, confirm, cancel) => ({
  show: false,
  dialog: {},
  text: {
    ok: ok,
    confirm: confirm,
    cancel: cancel,
  },
  add(dialog) {
    this.show = true;

    this.dialog = dialog;
  },
  remove() {
    this.show = false;

    setTimeout(() => this.dialog = {}, 500);
  },
  accept(dialog) {
    if (dialog.type !== 'question') {
      return this.remove();
    }

    const params = dialog.options.confirm.params ?? null;

    dispatchEvent('dialog:accepted', dialog);
    this.$dispatch(dialog.options.confirm.event, params.constructor !== Array ? [params] : [...params]);

    this.remove();
  },
  reject(dialog) {
    if (dialog.type !== 'question') {
      return this.remove();
    }

    const params = dialog.options.cancel.params ?? null;

    dispatchEvent('dialog:rejected', dialog);
    this.$dispatch(dialog.options.cancel.event, params.constructor !== Array ? [params] : [...params]);

    this.remove();
  },
  get cancelButtonText() {
    return this.dialog.text?.cancel.text ?? this.text.cancel;
  },
  get confirmButtonText() {
    if (this.dialog.type !== 'question') {
      return this.text.ok;
    }

    return this.dialog.text?.confirm.text ?? (this.text.confirm.length > 0 ? this.text.confirm : this.text.ok);
  },
});
