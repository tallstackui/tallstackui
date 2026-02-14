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

  /**
   * Hook called after setting the interaction type. Override in subclasses.
   *
   * @return {void}
   */
  onType() {}

  /**
   * Return the event name for this interaction type. Override in subclasses.
   *
   * @return {String}
   */
  event() {
    return '';
  }

  /**
   * Validate the interaction data before sending. Override in subclasses.
   *
   * @return {Boolean}
   */
  validate() {
    return true;
  }

  /**
   * Build the payload object to be dispatched.
   *
   * @return {Object}
   */
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
