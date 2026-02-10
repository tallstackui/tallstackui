import { event } from '../helpers';

export default class Interaction {
  _data;

  constructor() {
    this._data = {};
    this._data.options = {};
  }

  /**
   * @param title {String}
   * @param description {String|Null}
   * @return {Interaction}
   */
  success = (title, description = null) => {
    this._data.type = 'success';
    this._data.title = title;
    this._data.description = description;

    this.onType();

    return this;
  };

  /**
   * @param title {String}
   * @param description {String|Null}
   * @return {Interaction}
   */
  error = (title, description = null) => {
    this._data.type = 'error';
    this._data.title = title;
    this._data.description = description;

    this.onType();

    return this;
  };

  /**
   * @param title {String}
   * @param description {String|Null}
   * @return {Interaction}
   */
  warning = (title, description = null) => {
    this._data.type = 'warning';
    this._data.title = title;
    this._data.description = description;

    this.onType();

    return this;
  };

  /**
   * @param title {String}
   * @param description {String|Null}
   * @return {Interaction}
   */
  info = (title, description = null) => {
    this._data.type = 'info';
    this._data.title = title;
    this._data.description = description;

    this.onType();

    return this;
  };

  onType() {}

  event() {
    return '';
  }

  validate() {
    return true;
  }

  payload() {
    return {
      event: this.event(),
      ...this._data,
    };
  }

  /**
   * @return {void}
   */
  send = () => {
    if (!this.validate()) {
      return;
    }

    const data = this.payload();

    event(data.event, data);
  };
}
