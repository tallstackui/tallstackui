import {dispatchEvent} from '../../helpers';
import Confirmation from './confirmation';

// eslint-disable-next-line require-jsdoc
export default class Dialog {
  data;
  title;
  description;

  /**
   * @param title {String}
   * @param description {String|Null}
   */
  constructor(title, description = null) {
    this.data = {};
    this.title = title;
    this.description = description;
  }

  /**
   * @return {Toast}
   */
  success() {
    this.data.type = 'success';

    this.#dispatch();
  }

  /**
   * @return {Toast}
   */
  error() {
    this.data.type = 'error';

    this.#dispatch();
  }

  /**
   * @return {Toast}
   */
  warning() {
    this.data.type = 'warning';

    this.#dispatch();
  }

  /**
   * @return {Toast}
   */
  info() {
    this.data.type = 'info';

    this.#dispatch();
  }

  /**
   * @param data {Object}
   * @param component {String|Null}
   */
  confirm(data, component = null) {
    // eslint-disable-next-line new-cap
    return Confirmation(data, component, this.#data()).confirm();
  }

  // eslint-disable-next-line require-jsdoc
  #data() {
    return {
      event: 'dialog',
      title: this.title,
      description: this.description,
      type: this.data.type,
      timeout: this.data.timeout ?? 3,
      expandable: this.data.expandable ?? false,
    };
  }

  /**
   * @return {void}
   */
  #dispatch() {
    const data = this.#data();

    dispatchEvent(data.event, data);
  }
}
