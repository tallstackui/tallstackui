export default (
  model,
  color,
  background,
  line, 
  height,
  jpeg,
) => ({
  model: model,
  canvas: null,
  context: null,
  drawing: false,
  lastX: 0,
  lastY: 0,
  stacks: {},
  color: color,
  background: background,
  line: line,
  height: height,
  init() {
    this.canvas = this.$refs.canvas;

    this.context = this.canvas.getContext('2d', { willReadFrequently: true });
    this.context.line = this.line;
    this.context.lineCap = 'round';
    this.context.lineJoin = 'round';

    this.stacks = {
        undo: [],
        redo: [],
    }

    this.$nextTick(() => this.size(true));
    
    window.addEventListener('resize', this.size.bind(this));
  },
  /**
   * Clean the drawing on the canvas.
   *
   * @return {void}
   */
  clear() {
    this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);

    this.backgroundColor();
    this.store();

    this.model = null;
  },
  /**
   * Start drawing on the canvas.
   *
   * @param {Event} event
   * @return {void}
   */
  start(event) {
    event.preventDefault();

    this.drawing = true;

    const { offsetX, offsetY } = this.coordinates(event);

    this.lastX = offsetX;
    this.lastY = offsetY;

    this.draw(event);
  },
  /**
   * Draws on the canvas.
   *
   * @param {Event} event
   * @return {void}
   */
  draw(event) {
    if (!this.drawing) return;

    event.preventDefault();

    const { offsetX, offsetY } = this.coordinates(event);

    const distance = Math.sqrt(Math.pow(offsetX - this.lastX, 2) + Math.pow(offsetY - this.lastY, 2));

    const angle = Math.atan2(offsetY - this.lastY, offsetX - this.lastX);

    for (let i = 0; i < distance; i += this.line / 3) {
      const x = this.lastX + Math.cos(angle) * i;
      const y = this.lastY + Math.sin(angle) * i;
      this.dots(x, y);
    }

    this.lastX = offsetX;
    this.lastY = offsetY;
  },
  /**
   * Draws dots on the canvas.
   *
   * @param {Number} x
   * @param {Number} y
   * @return {void}
   */
  dots(x, y) {
    this.context.beginPath();
    this.context.arc(x, y, this.line / 2, 0, Math.PI * 2);
    this.context.fillStyle = this.color;
    this.context.fill();
    this.context.closePath();
  },
  /**
   * Stops drawing on the canvas
   *
   * @param {Event} event
   * @return {void}
   */
  stop(event) {
    if (!this.drawing) return;

    event.preventDefault();

    this.drawing = false;

    this.store();
  },
  /**
   * Undoes the last action.
   *
   * @return {void}
   */
  undo() {
    if (this.stacks.undo.length > 1) {
      this.stacks.redo.push(this.stacks.undo.pop());
      this.context.putImageData(this.stacks.undo[this.stacks.undo.length - 1], 0, 0);
      this.save();

      return;
    }

    this.stacks.redo.push(this.stacks.undo.pop());
    this.clear();
  },
  /**
   * Redoes the last undone action/
   *
   * @return {void}
   */
  redo() {
    if (this.stacks.redo.length > 0) {
      this.stacks.undo.push(this.stacks.redo.pop());
      this.context.putImageData(this.stacks.undo[this.stacks.undo.length - 1], 0, 0);
    }

    this.save();
  },
  /**
   * Sync canvas to the model.
   *
   * @return {void}
   */
  save() {
    this.model = this.canvas.toDataURL(`image/${this.extension}`);
  },
  /**
   * Store the current state of the canvas to allow the actions of undoing and redoing.
   *
   * @return {void}
   */
  store() {
    this.stacks.undo.push(this.context.getImageData(0, 0, this.canvas.width, this.canvas.height));

    this.stacks.redo = [];

    this.save();
  },
  /**
   * Download the canvas as an image.
   *
   * @return {void}
   */
  download() {
    const url = this.canvas.toDataURL(`image/${this.extension}`);
    const link = document.createElement('a');

    link.href = url;
    link.download = `signature.${this.extension}`;

    document.body.appendChild(link);

    link.click();

    document.body.removeChild(link);

    this.$el.dispatchEvent(new CustomEvent('export', {detail: {signature: url}}));
  },
  /**
   * Updates the background color of the canvas.
   *
   * @return {void}
   */
  backgroundColor() {
    if (jpeg && this.background === 'transparent') {
      this.background = '#FFFFFF';
    }

    this.context.fillStyle = this.background;

    this.context.fillRect(0, 0, this.canvas.width, this.canvas.height);
  },
  /**
   * Updates the size of the canvas
   *
   * @param {Boolean} clear
   * @return {void}
   */
  size(clear = false) {
    this.canvas.width = this.$refs.canvas.parentElement.clientWidth;
    this.canvas.height = this.height;

    if (!clear) return;

    this.clear();
  },
  /**
   * Gets the event (mouse or touch) coordinates on the canvas
   *
   * @param event
   * @returns {{offsetX: number, offsetY: number}}
   */
  coordinates(event) {
    const rect = this.canvas.getBoundingClientRect();
    
    if (event.touches && event.touches.length > 0) {
      const touch = event.touches[0];
      return {
        offsetX: (touch.clientX - rect.left) * (this.canvas.width / rect.width),
        offsetY: (touch.clientY - rect.top) * (this.canvas.height / rect.height),
      };
    }

    return {
      offsetX: (event.clientX - rect.left) * (this.canvas.width / rect.width),
      offsetY: (event.clientY - rect.top) * (this.canvas.height / rect.height),
    };
  },
  /**
   * Gets the extension of the image.
   *
   * @returns {String}
   */
  get extension() {
    return jpeg ? 'jpeg' : 'png';
  }
});
