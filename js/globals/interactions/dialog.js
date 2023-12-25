import {dispatchEvent} from '../../helpers';

// eslint-disable-next-line require-jsdoc
export default class Dialog {
  title;
  description;

  constructor(title, description = null) {
    this.title = title;
    this.description = description;
  }

  success() {
    this.object = {
      type: 'success',
      title: this.title,
      description: this.description,
    };

    this.#dispatch();
  }

  error() {
    this.object = {
      type: 'error',
      title: this.title,
      description: this.description,
    };

    this.#dispatch();
  }

  warning() {
    this.object = {
      type: 'warning',
      title: this.title,
      description: this.description,
    };

    this.#dispatch();
  }

  info() {
    this.object = {
      type: 'info',
      title: this.title,
      description: this.description,
    };

    this.#dispatch();
  }

  // eslint-disable-next-line require-jsdoc
  #dispatch() {
    dispatchEvent('dialog', this.object);
  }
}
