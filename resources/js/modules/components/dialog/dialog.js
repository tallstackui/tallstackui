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
    setTimeout(() => window.Livewire
        .find(dialog.component)
        .call(dialog.options.confirm.method, params?.constructor !== Array ? params : [...params]), 100);

    this.remove();
  },
  reject(dialog) {
    if (dialog.type !== 'question') {
      return this.remove();
    }

    dispatchEvent('dialog:rejected', dialog);

    if (dialog.options.cancel.method) {
      const params = dialog.options.cancel.params ?? null;
      setTimeout(() => window.Livewire
          .find(dialog.component)
          .call(dialog.options.cancel.method, params?.constructor !== Array ? params : [...params]), 100);
    }

    this.remove();
  },
});
