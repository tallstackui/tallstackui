import {dispatchEvent} from '../../helpers';

// TODO: confirmation?
// eslint-disable-next-line require-jsdoc
export default class Toast {
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
   * @return {void}
   */
  #dispatch() {
    dispatchEvent('dialog', {
      title: this.title,
      description: this.description,
      type: this.data.type,
    });
  }
}
