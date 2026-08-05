export default (model, color, background, line, height, jpeg, persistent) => ({
  model: model,
  canvas: null,
  context: null,
  drawing: false,
  strokes: [],
  undone: [],
  stroke: null,
  observer: null,
  color: color,
  background: background,
  line: line,
  height: height,
  init() {
    this.canvas = this.$refs.canvas;

    this.context = this.canvas.getContext('2d');
    this.context.lineCap = 'round';
    this.context.lineJoin = 'round';

    // Observing the container rather than the window: a collapsing sidebar resizes
    // the canvas without firing a resize event. The first observation sizes it.
    this.observer = new ResizeObserver(() => this.size());

    this.observer.observe(this.canvas.parentElement);
  },
  destroy() {
    this.observer.disconnect();

    this.strokes = [];
    this.undone = [];
    this.stroke = null;
  },
  clear() {
    this.strokes = [];
    this.undone = [];
    this.stroke = null;

    this.paint();
    this.save();
  },
  start(event) {
    event.preventDefault();

    this.drawing = true;

    const { offsetX, offsetY } = this.coordinates(event);

    this.stroke = { color: this.color, line: this.line, points: [] };

    this.point(offsetX, offsetY);
    this.dot(this.stroke, offsetX, offsetY);
  },
  draw(event) {
    if (!this.drawing) {
      return;
    }

    event.preventDefault();

    const { offsetX, offsetY } = this.coordinates(event);
    const previous = this.absolute(this.stroke.points[this.stroke.points.length - 1]);

    this.segment(this.stroke, previous, { x: offsetX, y: offsetY });

    this.point(offsetX, offsetY);
  },
  stop(event) {
    if (!this.drawing) {
      return;
    }

    event.preventDefault();

    this.drawing = false;

    this.strokes.push(this.stroke);
    this.stroke = null;
    this.undone = [];

    this.save();
  },
  undo() {
    if (this.strokes.length === 0) {
      return;
    }

    this.undone.push(this.strokes.pop());

    this.paint();
    this.save();
  },
  redo() {
    if (this.undone.length === 0) {
      return;
    }

    this.strokes.push(this.undone.pop());

    this.paint();
    this.save();
  },
  save() {
    this.model = this.strokes.length > 0 ? this.canvas.toDataURL(`image/${this.extension}`) : null;
  },
  download() {
    const url = this.canvas.toDataURL(`image/${this.extension}`);
    const link = document.createElement('a');

    link.href = url;
    link.download = `signature.${this.extension}`;

    document.body.appendChild(link);

    link.click();

    document.body.removeChild(link);

    this.$el.dispatchEvent(new CustomEvent('export', { detail: { signature: url } }));
  },
  backgroundColor() {
    if (jpeg && this.background === 'transparent') {
      this.background = '#FFFFFF';
    }

    this.context.fillStyle = this.background;

    this.context.fillRect(0, 0, this.canvas.width, this.canvas.height);
  },
  size() {
    const width = this.$refs.canvas.parentElement.clientWidth;

    if (this.canvas.width === width && this.canvas.height === this.height) {
      return;
    }

    if (!persistent) {
      this.drawing = false;
      this.strokes = [];
      this.undone = [];
      this.stroke = null;
    }

    // Assigning width or height wipes the canvas, and the context resets with it.
    this.canvas.width = width;
    this.canvas.height = this.height;

    this.context.lineCap = 'round';
    this.context.lineJoin = 'round';

    this.paint();
    this.save();
  },
  paint() {
    this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);

    this.backgroundColor();

    this.strokes.forEach((stroke) => this.replay(stroke));
  },
  replay(stroke) {
    if (stroke.points.length === 0) {
      return;
    }

    let previous = this.absolute(stroke.points[0]);

    this.dot(stroke, previous.x, previous.y);

    for (let index = 1; index < stroke.points.length; index++) {
      const point = this.absolute(stroke.points[index]);

      this.segment(stroke, previous, point);

      previous = point;
    }
  },
  // The horizontal axis is stored as a fraction of the canvas width so a stroke can
  // be redrawn at any width instead of resampled from pixels, which is what blurred
  // the drawing on every resize. The height never changes.
  point(x, y) {
    this.stroke.points.push({ x: x / this.canvas.width, y: y });
  },
  absolute(point) {
    return { x: point.x * this.canvas.width, y: point.y };
  },
  segment(stroke, from, to) {
    const distance = Math.sqrt(Math.pow(to.x - from.x, 2) + Math.pow(to.y - from.y, 2));
    const angle = Math.atan2(to.y - from.y, to.x - from.x);

    for (let step = 0; step < distance; step += stroke.line / 3) {
      this.dot(stroke, from.x + Math.cos(angle) * step, from.y + Math.sin(angle) * step);
    }
  },
  dot(stroke, x, y) {
    this.context.beginPath();
    this.context.arc(x, y, stroke.line / 2, 0, Math.PI * 2);
    this.context.fillStyle = stroke.color;
    this.context.fill();
    this.context.closePath();
  },
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
  get extension() {
    return jpeg ? 'jpeg' : 'png';
  },
});
