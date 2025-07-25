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

  initializeCanvas() {
    if (this.isVisible() && !this.initialized) {
      this.setupCanvas();
      this.initialized = true;
    } else if (!this.isVisible()) {
      this.waitForVisibility();
    }
  },

  isVisible() {
    const rect = this.canvas.getBoundingClientRect();
    return rect.width > 0 && rect.height > 0 && this.canvas.offsetParent !== null;
  },

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

  setupCanvas() {
    if (!this.isVisible()) return;
    
    this.canvas.width = this.$refs.canvas.parentElement.clientWidth;
    this.canvas.height = this.height;
    this.setBackground();
    this.saveState();
  },

  handleResize() {
    if (this.initialized && this.isVisible()) {
      this.setupCanvas();
    }
  },

  clear() {
    if (!this.initialized || !this.isVisible()) return;
    
    this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    this.setBackground();
    this.saveState();
    this.model = null;
  },

  start(event) {
    if (!this.initialized || !this.isVisible()) return;
    
    event.preventDefault();
    this.drawing = true;
    const { offsetX, offsetY } = this.getCoordinates(event);
    this.lastX = offsetX;
    this.lastY = offsetY;
    this.draw(event);
  },

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

  drawDot(x, y) {
    this.context.beginPath();
    this.context.arc(x, y, this.line / 2, 0, Math.PI * 2);
    this.context.fillStyle = this.color;
    this.context.fill();
    this.context.closePath();
  },

  stop(event) {
    if (!this.drawing || !this.initialized) return;
    
    event.preventDefault();
    this.drawing = false;
    this.saveState();
  },

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

  redo() {
    if (!this.initialized || this.stacks.redo.length === 0) return;
    
    this.stacks.undo.push(this.stacks.redo.pop());
    this.restoreState(this.stacks.undo[this.stacks.undo.length - 1]);
    this.updateModel();
  },

  restoreState(imageData) {
    if (imageData && this.isVisible()) {
      try {
        this.context.putImageData(imageData, 0, 0);
      } catch (error) {
        console.warn('Failed to restore canvas state');
      }
    }
  },

  updateModel() {
    if (this.initialized && this.isVisible()) {
      this.model = this.canvas.toDataURL(`image/${this.getExtension()}`);
    }
  },

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

  setBackground() {
    if (!this.initialized) return;
    
    let bgColor = this.background;
    if (jpeg && bgColor === 'transparent') {
      bgColor = '#FFFFFF';
    }
    this.context.fillStyle = bgColor;
    this.context.fillRect(0, 0, this.canvas.width, this.canvas.height);
  },

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

  getExtension() {
    return jpeg ? 'jpeg' : 'png';
  },
});
