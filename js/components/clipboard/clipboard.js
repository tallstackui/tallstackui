import ClipboardJS from 'clipboard/dist/clipboard';

export default (text = null, hash = null, placeholders) => ({
  text: text,
  notification: false,
  placeholders: placeholders,
  init() {
    this.$watch('notification', (value) => {
      if (!value) {
        return;
      }

      if (Boolean(value) === true) {
        return $toast(this.placeholders.success).success();
      }

      return $toast(this.placeholders.failure).error();
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

      setTimeout(() => this.notification = null, 1000);
    });

    clipboard.on('error', () => this.notification = false);
  },
});
