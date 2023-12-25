import {dispatchEvent} from '../../helpers';

// eslint-disable-next-line require-jsdoc
// TODO: confirmation?
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
   * @param timeout {Number}
   * @return {Toast}
   */
  timeout(timeout = 3) {
    this.data.timeout = timeout;

    return this;
  }

  /**
   * @param expandable {Boolean}
   * @return {Toast}
   */
  expandable(expandable = true) {
    this.data.expandable = expandable;

    return this;
  }

  /**
   * @return {void}
   */
  #dispatch() {
    dispatchEvent('toast', {
      title: this.title,
      description: this.description,
      type: this.data.type,
      timeout: this.data.timeout ?? 3,
      expandable: this.data.expandable ?? false,
    });
  }
}
