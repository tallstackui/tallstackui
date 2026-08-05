import { error } from '../../../js/helpers';

export default (options) => ({
  copied: false,
  timeout: null,

  /**
   * Copy the code to the clipboard as an image.
   *
   * Only a raster can be pasted somewhere useful: a vector lands as markup in
   * a chat or a document, when it lands at all. The item is built from the
   * pending promise rather than an awaited blob because Safari drops the
   * write once the gesture that started it has ended.
   */
  async copy() {
    if (!navigator.clipboard?.write || typeof ClipboardItem === 'undefined') {
      error('The clipboard is only available over HTTPS, on localhost or on 127.0.0.1.');

      return;
    }

    try {
      await navigator.clipboard.write([new ClipboardItem({ 'image/png': this.raster() })]);
    } catch {
      error('The QR code could not be copied to the clipboard.');

      return;
    }

    this.copied = true;

    clearTimeout(this.timeout);

    this.timeout = setTimeout(() => (this.copied = false), 2000);
  },

  async download() {
    const blob =
      options.format === 'svg'
        ? new Blob([this.serialize()], { type: 'image/svg+xml' })
        : await this.raster();

    const url = URL.createObjectURL(blob);
    const anchor = document.createElement('a');

    anchor.href = url;
    anchor.download = `qr-code.${options.format}`;

    anchor.click();

    URL.revokeObjectURL(url);
  },

  /**
   * Rasterize the vector through a canvas.
   *
   * The image is loaded from a data URL, which puts it in a document of its
   * own with no access to the page. That is why serialize() has to inline
   * everything the markup depends on.
   */
  raster() {
    return new Promise((resolve, reject) => {
      const image = new Image();

      image.onload = () => {
        const canvas = document.createElement('canvas');

        canvas.width = options.pixels;
        canvas.height = options.pixels;

        canvas.getContext('2d').drawImage(image, 0, 0, options.pixels, options.pixels);

        canvas.toBlob(
          (blob) => (blob ? resolve(blob) : reject(new Error('Empty canvas.'))),
          'image/png'
        );
      };

      image.onerror = () => reject(new Error('Unreadable vector.'));

      image.src = `data:image/svg+xml;charset=utf-8,${encodeURIComponent(this.serialize())}`;
    });
  },

  /**
   * The code as a standalone document.
   *
   * The classes are dropped and the color they resolved to is inlined, so
   * that currentColor still means the same thing without the stylesheet.
   */
  serialize() {
    const clone = this.$refs.code.cloneNode(true);

    clone.removeAttribute('class');
    clone.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
    clone.setAttribute('width', options.pixels);
    clone.setAttribute('height', options.pixels);
    clone.style.color = getComputedStyle(this.$refs.code).color;

    return new XMLSerializer().serializeToString(clone);
  },
});
