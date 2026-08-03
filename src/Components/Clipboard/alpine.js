import write from './write';

export default (text = null, hash = null, type, placeholders) => ({
  text: text,
  notification: null,
  placeholders: placeholders,
  time: 2000,
  init() {
    this.$watch('notification', (value) => {
      if (!value || type === 'icon') {
        return;
      }

      // The approach taken here is to prevent different
      // buttons from receiving text changes when clicked.
      const ref = this.$refs[`${type}-${hash}`];

      ref.innerText = this.placeholders.copied;

      setTimeout(() => (ref.innerText = this.placeholders.copy), this.time);
    });
  },
  /**
   * Copy the content to the clipboard.
   */
  async copy() {
    // Using this.notification here to prevent the copy again during the text effect.
    if (!text || !hash || Boolean(this.notification) === true) {
      return;
    }

    // Resolved before awaiting because $el points at the element that
    // triggered the expression, which is where the copy listener lives.
    const element = this.$el;

    if (!(await write(this.text))) {
      this.notification = false;

      return;
    }

    this.notification = true;

    setTimeout(() => (this.notification = false), this.time);

    element.dispatchEvent(new CustomEvent('copy', { detail: { text: this.text } }));
  },
});
