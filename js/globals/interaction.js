import {event, error} from '../helpers';

// eslint-disable-next-line require-jsdoc
export default class Interaction {
  #data;
  #type;

  /**
   * @param type {String}
   */
  constructor(type = 'dialog') {
    this.#data = {};
    this.#data.options = {};
    this.#type = type;
  }

  /**
   * @param title {String}
   * @param description {String|Null}
   * @return {Interaction}
   */
  success = (title, description = null) => {
    this.#data.type = 'success';
    this.#data.title = title;
    this.#data.description = description;

    this.#static();

    return this;
  };

  /**
   * @param title {String}
   * @param description {String|Null}
   * @return {Interaction}
   */
  error = (title, description = null) => {
    this.#data.type = 'error';
    this.#data.title = title;
    this.#data.description = description;

    this.#static();

    return this;
  };

  /**
   * @param title {String}
   * @param description {String|Null}
   * @return {Interaction}
   */
  warning = (title, description = null) => {
    this.#data.type = 'warning';
    this.#data.title = title;
    this.#data.description = description;

    this.#static();

    return this;
  };

  /**
   * @param title {String}
   * @param description {String|Null}
   * @return {Interaction}
   */
  info = (title, description = null) => {
    this.#data.type = 'info';
    this.#data.title = title;
    this.#data.description = description;

    this.#static();

    return this;
  };

  /**
   * @param title {String}
   * @param description {String|Null}
   * @return {Interaction}
   */
  question = (title, description = null) => {
    this.#data.type = 'question';
    this.#data.title = title;
    this.#data.description = description;

    this.#static();

    return this;
  };

  /**
   * @param id {String}
   * @return {Interaction|void}
   */
  wireable = (id = '') => {
    const label = id === '' ? 'first in page' : id;

    const livewire = id === '' ?
        Livewire.first() :
        Livewire.find(id);

    if (!livewire) {
      return error(`The Livewire component [${label}] was not found in the current page.`);
    }

    this.#data.component = livewire.id ?? null;

    return this;
  };

  /**
   * @param text {String|Null}
   * @param method {String|Null}
   * @param params {String|Number|Object|Null}
   * @return {Interaction|void}
   */
  confirm = (text= null, method = null, params = null) => {
    this.#data.options.confirm = this.#data.options.confirm || {};

    this.#data.options.confirm.static = !method;
    this.#data.options.confirm.text = text;
    this.#data.options.confirm.method = method;
    this.#data.options.confirm.params = params;

    return this;
  };

  /**
   * @param text {String|Null}
   * @param method {String|Null}
   * @param params {String|Number|Object|Null}
   */
  cancel = (text= null, method = null, params = null) => {
    this.#data.options.cancel = this.#data.options.cancel || {};

    this.#data.options.cancel.static = !method;
    this.#data.options.cancel.text = text;
    this.#data.options.cancel.method = method;
    this.#data.options.cancel.params = params;

    return this;
  };

  #static = () => {
    this.#data.options.confirm = {};
    this.#data.options.confirm.static = true;
  };

  // Toast definitions

  /**
   * @param seconds {Number}
   * @return {Interaction}
   */
  timeout = (seconds = 3) => {
    this.#data.timeout = seconds;

    return this;
  };

  /**
   * @param expand {Boolean}
   * @return {Interaction}
   */
  expandable = (expand = true) => {
    this.#data.expandable = expand;

    return this;
  };

  // eslint-disable-next-line require-jsdoc
  #payload() {
    return {
      event: this.#type === 'toast' ? 'toast' : 'dialog',
      ...this.#data,
      ...(this.#type === 'toast' ? {
        timeout: this.#data.timeout ?? 3,
        expandable: this.#data.expandable ?? false,
      } : {}),
    };
  }

  #validate = () => {
    const options = this.#data.options ?? null;

    if (options.cancel && !options.cancel.text) {
      error('You must set the text of [cancel] action.');
      return false;
    }

    if (options.confirm && !options.confirm.static && !options.confirm.text) {
      error('You must set the text of [confirm] action.');
      return false;
    }

    if ((options.cancel?.method || options.confirm?.method) && !this.#data.component) {
      error('You must set the id of the Livewire component to interact with [confirm] or [cancel] action.');
      return false;
    }

    return true;
  };

  /**
   * @return {void}
   */
  send = () => {
    if (!this.#validate()) {
      return;
    }

    const data = this.#payload();

    event(data.event, data);
  };
}
