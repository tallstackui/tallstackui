import {event, overflow} from '../../helpers';

export default (flash, texts, overflowing) => ({
  show: false,
  dialog: {},
  text: {
    ok: texts.ok,
    confirm: texts.confirm,
    cancel: texts.cancel,
  },
  init() {
    if (flash) window.onload = () => this.add(flash);

    this.$watch('show', (value) => overflow(value, 'dialog', overflowing));
  },
  /**
   * @param dialog {Object}
   * @return {void}
   */
  add(dialog) {
    this.dialog = {};
    this.dialog = dialog;
    this.show = true;
  },
  /**
   * @param dismissed {Boolean}
   * @param internal {Boolean}
   * @return {void}
   */
  remove(dismissed = false, internal = false) {
    this.show = false;

    console.log(this.dialog.hooks);

    const hook = dismissed ? this.dialog.hooks?.dismiss : this.dialog.hooks?.close;

    if (hook && !internal) Livewire.find(this.dialog.component).call(hook.method, hook.params);

    if (!dismissed) return;

    event('dialog:dismissed', this.dialog, false);
  },
  /**
   * @param dialog {Object}
   * @param element {HTMLElement}
   * @return {void}
   */
  accept(dialog, element) {
    event('dialog:accepted', dialog, false);

    const component = Livewire.find(dialog.component);

    if (dialog.options.confirm.static === true || dialog.options.confirm.method === null) {
      if (dialog.hooks?.ok) {
        component.call(dialog.hooks.ok.method, dialog.hooks.ok.params);
      }

      return this.remove(false, true);
    }

    setTimeout(() => {
      component.call(dialog.options.confirm.method, dialog.options.confirm.params);

      // This is a little trick to prevent the element from being
      // focused if there is another dialog displayed sequentially.
      element.blur();
    }, 100);

    this.remove(false, true);
  },
  /**
   * @param dialog {Object}
   * @param element {HTMLElement}
   * @return {void}
   */
  reject(dialog, element) {
    event('dialog:rejected', dialog, false);

    const component = Livewire.find(dialog.component);

    if (!dialog.options || dialog.options.cancel.static === true || dialog.options.cancel.method === null) {
      if (dialog.hooks?.reject) {
        component.call(dialog.hooks.reject.method, dialog.hooks.reject.params);
      }

      return this.remove(false, true);
    }

    setTimeout(() => {
      component.call(dialog.options.cancel.method, dialog.options.cancel.params);

      // This is a little trick to prevent the element from being
      // focused if there is another dialog displayed sequentially.
      element.blur();
    }, 100);

    return this.remove(false, true);
  },
});
