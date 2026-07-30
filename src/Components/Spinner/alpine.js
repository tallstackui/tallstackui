export default (frames, interval) => ({
  frames: frames,
  index: 0,
  timer: null,
  init() {
    this.timer = setInterval(() => {
      this.index = (this.index + 1) % this.frames.length;
    }, interval);
  },
  destroy() {
    clearInterval(this.timer);

    this.timer = null;
  },
});
