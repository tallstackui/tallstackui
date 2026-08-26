// Anything above this is downscaled on load: a full resolution phone photo
// drawn on a canvas is enough to crash the tab on iOS.
const MAX_SIDE = 4096;

const MIN_CROP = 24;

const editable = (file) => file.type.startsWith('image/') && file.type !== 'image/svg+xml';

const decode = (file) =>
  new Promise((resolve, reject) => {
    const url = URL.createObjectURL(file);
    const image = new Image();

    image.onload = () => {
      URL.revokeObjectURL(url);
      resolve(image);
    };

    image.onerror = () => {
      URL.revokeObjectURL(url);
      reject(new Error('Unreadable image.'));
    };

    image.src = url;
  });

const load = async (file) => {
  // from-image applies the EXIF orientation, so a portrait phone photo
  // does not show up sideways before the user even touched it.
  const source =
    typeof createImageBitmap === 'function'
      ? await createImageBitmap(file, { imageOrientation: 'from-image' }).catch(() => decode(file))
      : await decode(file);

  const largest = Math.max(source.width, source.height);

  if (largest <= MAX_SIDE) {
    return source;
  }

  const ratio = MAX_SIDE / largest;
  const canvas = document.createElement('canvas');

  canvas.width = Math.round(source.width * ratio);
  canvas.height = Math.round(source.height * ratio);
  canvas.getContext('2d').drawImage(source, 0, 0, canvas.width, canvas.height);

  source.close?.();

  return canvas;
};

const output = (file, format) => {
  if (format) {
    return `image/${format}`;
  }

  // A canvas cannot write an animated gif back, so the frame becomes a png.
  return file.type === 'image/gif' ? 'image/png' : file.type;
};

const rename = (name, type) => {
  const extension = { 'image/png': 'png', 'image/jpeg': 'jpg', 'image/webp': 'webp' }[type];
  const position = name.lastIndexOf('.');
  const base = position > 0 ? name.slice(0, position) : name;

  return extension ? `${base}.${extension}` : name;
};

export default (options) => {
  // Kept out of Alpine's reactive proxy: the bitmap is large.
  const state = { bitmap: null, file: null, resolve: null, drag: null, observer: null };

  return {
    editor: {
      name: null,
      rotation: 0,
      scale: 1,
      width: 0,
      height: 0,
      crop: { x: 0, y: 0, width: 0, height: 0 },
    },
    editable(file) {
      return !!options && (options.crop || options.rotate) && editable(file);
    },
    /**
     * Resolves with the edited file, the untouched file when it
     * cannot be edited, or null when the user cancels.
     * @param file {File}
     * @returns {Promise<File|null>}
     */
    async edit(file) {
      if (!this.editable(file)) {
        return file;
      }

      try {
        state.bitmap = await load(file);
      } catch {
        return file;
      }

      state.file = file;

      this.editor.name = file.name;
      this.editor.rotation = 0;
      this.editor.width = 0;

      this.overlay('open');

      // The modal has not painted yet, so the stage still measures zero:
      // the observer fires once it has a size and on every resize after.
      state.observer = new ResizeObserver(() => this.stage());
      state.observer.observe(this.part('stage'));

      return new Promise((resolve) => (state.resolve = resolve));
    },
    // The modal is its own Alpine scope, so $refs cannot reach inside it.
    part(name) {
      return document.getElementById(options.modal)?.querySelector(`[data-tsui-editor="${name}"]`) ?? null;
    },
    overlay(action) {
      window.dispatchEvent(new CustomEvent(`modal:${options.modal}-${action}`));
    },
    /**
     * Fit the rotated image into the stage. The crop box follows the new
     * scale unless the image itself changed and the box must start over.
     * @param reframe {Boolean}
     * @returns {void}
     */
    stage(reframe = false) {
      const box = this.part('stage');
      const canvas = this.part('canvas');

      if (!box || !canvas || !state.bitmap || !box.clientWidth) {
        return;
      }

      const [width, height] = this.rotated();
      const styles = getComputedStyle(box);
      const room = {
        width: box.clientWidth - parseFloat(styles.paddingLeft) - parseFloat(styles.paddingRight),
        height: box.clientHeight - parseFloat(styles.paddingTop) - parseFloat(styles.paddingBottom),
      };

      const fresh = reframe || !this.editor.width;
      const previous = this.editor.scale;

      this.editor.scale = Math.min(room.width / width, room.height / height, 1);
      this.editor.width = Math.round(width * this.editor.scale);
      this.editor.height = Math.round(height * this.editor.scale);

      canvas.width = this.editor.width;
      canvas.height = this.editor.height;

      this.paint(canvas.getContext('2d'), this.editor.scale);

      if (fresh) {
        this.reframe();

        return;
      }

      const ratio = this.editor.scale / previous;
      const { x, y, width: cropWidth, height: cropHeight } = this.editor.crop;

      this.editor.crop = { x: x * ratio, y: y * ratio, width: cropWidth * ratio, height: cropHeight * ratio };
    },
    paint(context, scale) {
      const [width, height] = this.rotated();

      context.save();
      context.translate((width * scale) / 2, (height * scale) / 2);
      context.rotate((this.editor.rotation * Math.PI) / 180);
      context.scale(scale, scale);
      context.drawImage(state.bitmap, -state.bitmap.width / 2, -state.bitmap.height / 2);
      context.restore();
    },
    rotated() {
      const swap = this.editor.rotation % 180 !== 0;

      return swap
        ? [state.bitmap.height, state.bitmap.width]
        : [state.bitmap.width, state.bitmap.height];
    },
    reframe() {
      const { width, height } = this.editor;
      const aspect = options.aspect;

      if (!aspect) {
        this.editor.crop = { x: 0, y: 0, width, height };

        return;
      }

      let cropWidth = width;
      let cropHeight = width / aspect;

      if (cropHeight > height) {
        cropHeight = height;
        cropWidth = height * aspect;
      }

      this.editor.crop = {
        x: (width - cropWidth) / 2,
        y: (height - cropHeight) / 2,
        width: cropWidth,
        height: cropHeight,
      };
    },
    rotate(degrees) {
      this.editor.rotation = (this.editor.rotation + degrees + 360) % 360;

      this.stage(true);
    },
    reset() {
      this.editor.rotation = 0;

      this.stage(true);
    },
    press(event, handle) {
      state.drag = {
        handle,
        x: event.clientX,
        y: event.clientY,
        origin: { ...this.editor.crop },
      };
    },
    drag(event) {
      if (!state.drag) {
        return;
      }

      const { handle, origin } = state.drag;
      const deltaX = event.clientX - state.drag.x;
      const deltaY = event.clientY - state.drag.y;

      if (handle === 'move') {
        this.editor.crop = {
          ...origin,
          x: Math.min(Math.max(0, origin.x + deltaX), this.editor.width - origin.width),
          y: Math.min(Math.max(0, origin.y + deltaY), this.editor.height - origin.height),
        };

        return;
      }

      this.editor.crop = this.resize(origin, handle, deltaX, deltaY);
    },
    release() {
      state.drag = null;
    },
    resize(origin, handle, deltaX, deltaY) {
      const bounds = { width: this.editor.width, height: this.editor.height };
      const aspect = options.aspect;

      let left = origin.x;
      let top = origin.y;
      let right = origin.x + origin.width;
      let bottom = origin.y + origin.height;

      if (handle.includes('e')) {
        right = Math.min(bounds.width, Math.max(left + MIN_CROP, right + deltaX));
      }

      if (handle.includes('w')) {
        left = Math.max(0, Math.min(right - MIN_CROP, left + deltaX));
      }

      if (handle.includes('s')) {
        bottom = Math.min(bounds.height, Math.max(top + MIN_CROP, bottom + deltaY));
      }

      if (handle.includes('n')) {
        top = Math.max(0, Math.min(bottom - MIN_CROP, top + deltaY));
      }

      if (!aspect) {
        return { x: left, y: top, width: right - left, height: bottom - top };
      }

      // With a locked aspect the dragged edge leads and the other follows,
      // anchored opposite to the handle so the box grows away from the pointer.
      const vertical = handle === 'n' || handle === 's';

      let width = vertical ? (bottom - top) * aspect : right - left;
      let height = width / aspect;

      const anchorX = handle.includes('w') ? right : left;
      const anchorY = handle.includes('n') ? bottom : top;
      const roomX = handle.includes('w') ? anchorX : bounds.width - anchorX;
      const roomY = handle.includes('n') ? anchorY : bounds.height - anchorY;

      if (width > roomX) {
        width = roomX;
        height = width / aspect;
      }

      if (height > roomY) {
        height = roomY;
        width = height * aspect;
      }

      return {
        x: handle.includes('w') ? anchorX - width : anchorX,
        y: handle.includes('n') ? anchorY - height : anchorY,
        width,
        height,
      };
    },
    async apply() {
      const { crop } = this.editor;
      const [width, height] = this.rotated();

      // Mapped as a fraction of the stage rather than divided by the scale:
      // the stage is rounded to whole pixels, so a full crop must still
      // land exactly on the source size.
      const ratio = { x: width / this.editor.width, y: height / this.editor.height };

      const region = options.crop
        ? {
            x: crop.x * ratio.x,
            y: crop.y * ratio.y,
            width: crop.width * ratio.x,
            height: crop.height * ratio.y,
          }
        : { x: 0, y: 0, width, height };

      const canvas = document.createElement('canvas');

      canvas.width = Math.max(1, Math.round(region.width));
      canvas.height = Math.max(1, Math.round(region.height));

      const context = canvas.getContext('2d');

      context.translate(-Math.round(region.x), -Math.round(region.y));

      this.paint(context, 1);

      const type = output(state.file, options.format);

      const blob = await new Promise((resolve) => canvas.toBlob(resolve, type, options.quality));

      if (!blob) {
        this.close(state.file);

        return;
      }

      this.close(
        new File([blob], rename(state.file.name, type), {
          type,
          lastModified: Date.now(),
        })
      );
    },
    cancel() {
      this.close(null);
    },
    // The modal closed on its own (escape, backdrop, X): only an edit
    // still waiting for an answer counts as cancelled.
    dismiss() {
      if (state.resolve) {
        this.close(null);
      }
    },
    close(result) {
      const resolve = state.resolve;

      state.observer?.disconnect();
      state.observer = null;
      state.bitmap?.close?.();
      state.bitmap = null;
      state.file = null;
      state.resolve = null;
      state.drag = null;

      this.overlay('close');

      resolve?.(result);
    },
  };
};
