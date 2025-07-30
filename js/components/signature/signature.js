export default (model, color, background, line, height, jpeg) => ({
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
  initialized: false,
  init() {
    this.canvas = this.$refs.canvas;
    this.context = this.canvas.getContext('2d', { willReadFrequently: true });
    this.context.line = this.line;
    this.context.lineCap = 'round';
    this.context.lineJoin = 'round';

    this.stacks = {
      undo: [],
      redo: [],
    };

    this.$nextTick(() => this.initializeCanvas());
    window.addEventListener('resize', this.handleResize.bind(this));
  },

  /**
   * Initialize canvas when it becomes visible
   * Handles modal scenarios where canvas might not be immediately visible
   */
  initializeCanvas() {
    if (this.isVisible() && !this.initialized) {
      this.setupCanvas();
      this.initialized = true;
    } else if (!this.isVisible()) {
      this.waitForVisibility();
    }
  },

  /**
   * Check if canvas element is visible and has dimensions
   * @returns {boolean} True if canvas is visible and ready for drawing
   */
  isVisible() {
    const rect = this.canvas.getBoundingClientRect();
    return rect.width > 0 && rect.height > 0 && this.canvas.offsetParent !== null;
  },

  /**
   * Wait for canvas to become visible using requestAnimationFrame
   * Used when canvas is initially hidden (e.g., in modals)
   */
  waitForVisibility() {
    const checkVisibility = () => {
      if (this.isVisible() && !this.initialized) {
        this.setupCanvas();
        this.initialized = true;
        return;
      }
      requestAnimationFrame(checkVisibility);
    };
    requestAnimationFrame(checkVisibility);
  },

  /**
   * Setup canvas dimensions and initial state
   * Called when canvas becomes visible and ready
   */
  setupCanvas() {
    if (!this.isVisible()) return;

    this.canvas.width = this.$refs.canvas.parentElement.clientWidth;
    this.canvas.height = this.height;
    this.backgroundColor();
    this.saveState();
  },

  /**
   * Handle window resize events
   * Reinitializes canvas if it's already been set up
   */
  handleResize() {
    if (this.initialized && this.isVisible()) {
      this.setupCanvas();
    }
  },
  /**
   * Clean the drawing on the canvas.
   *
   * @return {void}
   */
  clear() {
    if (!this.initialized || !this.isVisible()) return;

    this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    this.backgroundColor();
    this.saveState();
    this.model = null;
  },
  /**
   * Start drawing on the canvas.
   *
   * @param {Event} event
   * @return {void}
   */
  start(event) {
    if (!this.initialized || !this.isVisible()) return;

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
    if (!this.drawing || !this.initialized) return;

    event.preventDefault();
    const { offsetX, offsetY } = this.coordinates(event);
    const distance = Math.sqrt(
      Math.pow(offsetX - this.lastX, 2) + Math.pow(offsetY - this.lastY, 2)
    );
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
    if (!this.drawing || !this.initialized) return;

    event.preventDefault();
    this.drawing = false;
    this.saveState();
  },
  /**
   * Undoes the last action.
   *
   * @return {void}
   */
  undo() {
    if (!this.initialized || this.stacks.undo.length === 0) return;

    if (this.stacks.undo.length > 1) {
      this.stacks.redo.push(this.stacks.undo.pop());
      this.restoreState(this.stacks.undo[this.stacks.undo.length - 1]);
      this.updateModel();
    } else {
      this.stacks.redo.push(this.stacks.undo.pop());
      this.clear();
    }
  },
  /**
   * Redoes the last undone action/
   *
   * @return {void}
   */
  redo() {
    if (!this.initialized || this.stacks.redo.length === 0) return;

    this.stacks.undo.push(this.stacks.redo.pop());
    this.restoreState(this.stacks.undo[this.stacks.undo.length - 1]);
    this.updateModel();
  },

  /**
   * Restore canvas state from image data
   * @param {ImageData} imageData - Canvas image data to restore
   */
  restoreState(imageData) {
    if (imageData && this.isVisible()) {
      try {
        this.context.putImageData(imageData, 0, 0);
      } catch (error) {
        console.warn('Failed to restore canvas state');
      }
    }
  },

  /**
   * Update the model with current canvas data
   * Converts canvas to data URL and updates the model value
   */
  updateModel() {
    if (this.initialized && this.isVisible()) {
      this.model = this.canvas.toDataURL(`image/${this.extension()}`);
    }
  },

  /**
   * Save current canvas state to undo stack
   * Captures current canvas image data for undo/redo functionality
   */
  saveState() {
    if (!this.initialized || !this.isVisible()) return;

    try {
      const imageData = this.context.getImageData(0, 0, this.canvas.width, this.canvas.height);
      this.stacks.undo.push(imageData);
      this.stacks.redo = [];
      this.updateModel();
    } catch (error) {
      console.warn('Failed to save canvas state');
    }
  },
  /**
   * Download the canvas as an image.
   *
   * @return {void}
   */
  download() {
    if (!this.initialized) return;

    const url = this.canvas.toDataURL(`image/${this.extension()}`);
    const link = document.createElement('a');
    link.href = url;
    link.download = `signature.${this.extension()}`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    this.$el.dispatchEvent(new CustomEvent('export', { detail: { signature: url } }));
  },
  /**
   * Updates the background color of the canvas.
   *
   * @return {void}
   */
  backgroundColor() {
    if (!this.initialized) return;

    let bgColor = this.background;
    if (jpeg && bgColor === 'transparent') {
      bgColor = '#FFFFFF';
    }
    this.context.fillStyle = bgColor;
    this.context.fillRect(0, 0, this.canvas.width, this.canvas.height);
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
  },
});
