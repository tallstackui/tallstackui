export default (number, animated, duration = 1) => ({
  visible: false,
  start: 0,
  number: number,
  animated: animated,
  duration: Number(duration) > 0 ? Number(duration) : 1,
  init() {
    this.$watch('visible', (visible) => {
      if (!visible || this.animated === false) {
        return;
      }

      const target = Number(this.number);

      if (!Number.isFinite(target)) {
        return;
      }

      const element = this.$refs.number;

      if (!element) {
        return;
      }

      this.start = 0;

      const step = (timestamp) => {
        if (!this.start) {
          this.start = timestamp;
        }

        const progress = timestamp - this.start;
        const percentage = Math.min(progress / (this.duration * 1000), 1);
        const value = Math.floor(percentage * target);

        element.textContent = value.toLocaleString();

        if (progress < this.duration * 1000) {
          window.requestAnimationFrame(step);
        }
      };

      window.requestAnimationFrame(step);
    });
  },
});
