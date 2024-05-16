export default (
  model,
  color,
  background,
  line, 
  height,
) => ({
  model: model,
  canvas: null,
  ctx: null,
  drawing: false,
  lastX: 0,
  lastY: 0,
  undoStack: [],
  redoStack: [],
  color: color,
  background: background,
  line: line,
  height: height,
  /**
   * Initializes the canvas and sets initial configurations
   */
  init() {
    this.canvas = this.$refs.canvas;
    this.ctx = this.canvas.getContext('2d', { willReadFrequently: true });
    this.ctx.line = this.line;
    this.ctx.lineCap = 'round';
    this.ctx.lineJoin = 'round';

    this.updateCanvasSize();
    this.updateBackgroundColor();

    window.addEventListener('resize', this.updateCanvasSize);
  },
  /**
   * Clears the drawing on the canvas
   */
  clear() {
    this.ctx.fillStyle = this.background;
    this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
    this.saveState();
    this.model = null;
  },
  /**
   * Starts drawing on the canvas
   *
   * @param event
   */
  startDrawing(event) {
    event.preventDefault();
    this.drawing = true;
    const { offsetX, offsetY } = this.getEventCoordinates(event);
    this.lastX = offsetX;
    this.lastY = offsetY;
    this.draw(event);
  },
  /**
   * Draws on the canvas
   *
   * @param event
   */
  draw(event) {
    if (this.drawing) {
      event.preventDefault();
      const { offsetX, offsetY } = this.getEventCoordinates(event);
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
    }
  },
  /**
   * Draws a dot on the canvas
   *
   * @param x
   * @param y
   */
  drawDot(x, y) {
    this.ctx.beginPath();
    this.ctx.arc(x, y, this.line / 2, 0, Math.PI * 2);
    this.ctx.fillStyle = this.color;
    this.ctx.fill();
    this.ctx.closePath();
  },
  /**
   * Stops drawing on the canvas
   *
   * @param event
   */
  stopDrawing(event) {
    if (this.drawing) {
      event.preventDefault();
      this.drawing = false;
      this.saveState();
    }
  },
  /**
   * Undoes the last action
   */
  undo() {
    if (this.undoStack.length > 1) {
      this.redoStack.push(this.undoStack.pop());
      this.ctx.putImageData(this.undoStack[this.undoStack.length - 1], 0, 0);
    }

    this.save();
  },
  /**
   * Redoes the last undone action
   */
  redo() {
    if (this.redoStack.length > 0) {
      this.undoStack.push(this.redoStack.pop());
      this.ctx.putImageData(this.undoStack[this.undoStack.length - 1], 0, 0);
    }

    this.save();
  },
  /**
   * Saves the image
   */
  save() {
    this.model = this.canvas.toDataURL();
  },

  /**
   * Saves the current state of the canvas to allow undoing and redoing
   */
  saveState() {
    this.undoStack.push(
      this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height)
    );
    this.redoStack = [];
    this.save();
  },
  /**
   * Exports the image
   */
  exportImage() {
    const dataUrl = this.canvas.toDataURL();
    const link = document.createElement('a');
    link.href = dataUrl;
    link.download = 'signature.png';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    this.$el.dispatchEvent(new CustomEvent('export', {detail: {signature: dataUrl}}));
  },

  /**
   * Changes the background color of the canvas
   */
  changeBackgroundColor() {
    this.updateBackgroundColor();
    this.clear();
  },
  /**
   * Updates the background color of the canvas
   */
  updateBackgroundColor() {
    this.ctx.fillStyle = this.background;
    this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
  },

  /**
   * Updates the size of the canvas
   */
  updateCanvasSize() {
    const container = this.$refs.canvas.parentElement;
    const containerWidth = container.clientWidth;
    this.canvas.width = containerWidth;
    this.canvas.height = this.height;
    this.updateBackgroundColor();
  },
  /**
   * Gets the event (mouse or touch) coordinates on the canvas
   *
   * @param event
   * @returns {{offsetX: number, offsetY: number}}
   */
  getEventCoordinates(event) {
    if (event.touches && event.touches.length > 0) {
      const touch = event.touches[0];
      const rect = this.canvas.getBoundingClientRect();
      return {
        offsetX: touch.clientX - rect.left,
        offsetY: touch.clientY - rect.top
      };
    }
    return {
      offsetX: event.offsetX,
      offsetY: event.offsetY
    };
  }
});
