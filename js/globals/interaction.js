
import Dialog from './interactions/dialog';
import Toast from './interactions/toast';

// eslint-disable-next-line require-jsdoc
export default class Interaction {
  title;
  description;

  /**
   * @param title {String}
   * @param description {String|Null}
   */
  constructor(title, description = null) {
    this.title = title;
    this.description = description;
  }

  /**
   * @return {Dialog}
   */
  dialog() {
    return new Dialog(this.title, this.description);
  }

  /**
   * @return {Toast}
   */
  toast() {
    return new Toast(this.title, this.description);
  }
};
