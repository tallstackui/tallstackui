import Interaction from '../../../js/globals/interaction';
import { error } from '../../../js/helpers';

export default class DialogInteraction extends Interaction {
  /**
   * @param title {String}
   * @param description {String|Null}
   * @return {DialogInteraction}
   */
  question = (title, description = null) => {
    this._data.type = 'question';
    this._data.title = title;
    this._data.description = description;

    this.onType();

    return this;
  };

  /**
   * @param id {String}
   * @return {DialogInteraction|void}
   */
  wireable = (id = '') => {
    const label = id === '' ? 'first in page' : id;

    const livewire = id === '' ? Livewire.first() : Livewire.find(id);

    if (!livewire) {
      return error(`The Livewire component [${label}] was not found in the current page.`);
    }

    this._data.reference = livewire.id ?? null;

    return this;
  };

  /**
   * @param text {String|Null}
   * @param method {String|Function|Null}
   * @param params {String|Number|Object|Null}
   * @return {DialogInteraction}
   */
  confirm = (text = null, method = null, params = null) => {
    this._data.options.confirm = this._data.options.confirm || {};

    this._data.options.confirm.static = !method;
    this._data.options.confirm.text = text;
    this._data.options.confirm.method = method;
    this._data.options.confirm.params = params;

    return this;
  };

  /**
   * @param text {String|Null}
   * @param method {String|Function|Null}
   * @param params {String|Number|Object|Null}
   * @return {DialogInteraction}
   */
  cancel = (text = null, method = null, params = null) => {
    this._data.options.cancel = this._data.options.cancel || {};

    this._data.options.cancel.static = !method;
    this._data.options.cancel.text = text;
    this._data.options.cancel.method = method;
    this._data.options.cancel.params = params;

    return this;
  };

  /**
   * @return {DialogInteraction}
   */
  persistent = () => {
    this._data.persistent = true;

    return this;
  };

  /**
   * Initialize default confirm options when setting the dialog type.
   *
   * @return {void}
   */
  onType() {
    this._data.options.confirm = {};
    this._data.options.confirm.static = true;
  }

  /**
   * Return the event name for this interaction type.
   *
   * @return {String}
   */
  event() {
    return 'dialog';
  }

  /**
   * Validate that confirm/cancel texts and Livewire reference are properly set.
   *
   * @return {Boolean}
   */
  validate() {
    const options = this._data.options ?? null;

    if (options.cancel && !options.cancel.text) {
      error('You must set the text of [cancel] action.');
      return false;
    }

    if (options.confirm && !options.confirm.static && !options.confirm.text) {
      error('You must set the text of [confirm] action.');
      return false;
    }

    if ((options.cancel?.method || options.confirm?.method) && !this._data.reference) {
      error(
        'You must set the id of the Livewire component to interact with [confirm] or [cancel] action.'
      );
      return false;
    }

    return true;
  }
}
