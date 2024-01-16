import ClipboardJS from 'clipboard/dist/clipboard';

export default (
    text = null,
    hash = null,
    type,
    placeholders,
) => ({
  text: text,
  notification: false,
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

      setTimeout(() => ref.innerText = this.placeholders.copy, this.time);
    });
  },
  copy() {
    if (!text || !hash) {
      return;
    }

    const clipboard = new ClipboardJS(`[data-hash="${hash}"]`, {
      text: () => this.text,
    });

    clipboard.on('success', (event) => {
      this.notification = true;

      event.clearSelection();

      setTimeout(() => this.notification = null, this.time);
    });

    clipboard.on('error', () => this.notification = false);

    this.$el.dispatchEvent(new CustomEvent('copied', {detail: {text: this.text}}));
  },
});
