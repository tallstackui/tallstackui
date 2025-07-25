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

  /**
   * Initialize the signature component
   * Sets up canvas context, event listeners, and initializes the drawing state
   */
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
    this.setBackground();
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
   * Clear the canvas and reset drawing state
   * Resets the model value and saves the cleared state
   */
  clear() {
    if (!this.initialized || !this.isVisible()) return;

    this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    this.setBackground();
    this.saveState();
    this.model = null;
  },

  /**
   * Start drawing operation
   * @param {Event} event - Mouse or touch event
   */
  start(event) {
    if (!this.initialized || !this.isVisible()) return;

    event.preventDefault();
    this.drawing = true;
    const { offsetX, offsetY } = this.getCoordinates(event);
    this.lastX = offsetX;
    this.lastY = offsetY;
    this.draw(event);
  },

  /**
   * Handle drawing movement
   * @param {Event} event - Mouse or touch move event
   */
  draw(event) {
    if (!this.drawing || !this.initialized) return;

    event.preventDefault();
    const { offsetX, offsetY } = this.getCoordinates(event);
    const distance = Math.sqrt(
      Math.pow(offsetX - this.lastX, 2) + Math.pow(offsetY - this.lastY, 2)
    );
    const angle = Math.atan2(offsetY - this.lastY, offsetX - this.lastX);

    for (let i = 0; i < distance; i += this.line / 3) {
      const x = this.lastX + Math.cos(angle) * i;
      const y = this.lastY + Math.sin(angle) * i;
      this.drawDot(x, y);
    }

    this.lastX = offsetX;
    this.lastY = offsetY;
  },

  /**
   * Draw a single dot at specified coordinates
   * @param {number} x - X coordinate
   * @param {number} y - Y coordinate
   */
  drawDot(x, y) {
    this.context.beginPath();
    this.context.arc(x, y, this.line / 2, 0, Math.PI * 2);
    this.context.fillStyle = this.color;
    this.context.fill();
    this.context.closePath();
  },

  /**
   * Stop drawing operation
   * @param {Event} event - Mouse or touch event
   */
  stop(event) {
    if (!this.drawing || !this.initialized) return;

    event.preventDefault();
    this.drawing = false;
    this.saveState();
  },

  /**
   * Undo the last drawing action
   * Restores the previous canvas state from undo stack
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
   * Redo the last undone action
   * Restores canvas state from redo stack
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
      this.model = this.canvas.toDataURL(`image/${this.getExtension()}`);
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
   * Download the signature as an image file
   * Creates a download link and triggers the download
   */
  download() {
    if (!this.initialized) return;

    const url = this.canvas.toDataURL(`image/${this.getExtension()}`);
    const link = document.createElement('a');
    link.href = url;
    link.download = `signature.${this.getExtension()}`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    this.$el.dispatchEvent(new CustomEvent('export', { detail: { signature: url } }));
  },

  /**
   * Set the canvas background color
   * Handles transparent background for JPEG format
   */
  setBackground() {
    if (!this.initialized) return;

    let bgColor = this.background;
    if (jpeg && bgColor === 'transparent') {
      bgColor = '#FFFFFF';
    }
    this.context.fillStyle = bgColor;
    this.context.fillRect(0, 0, this.canvas.width, this.canvas.height);
  },

  /**
   * Get normalized coordinates from mouse or touch event
   * @param {Event} event - Mouse or touch event
   * @returns {Object} Object with offsetX and offsetY properties
   */
  getCoordinates(event) {
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
   * Get file extension based on JPEG setting
   * @returns {string} File extension ('jpeg' or 'png')
   */
  getExtension() {
    return jpeg ? 'jpeg' : 'png';
  },
});
