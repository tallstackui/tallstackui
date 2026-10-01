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

      const decimals = (String(this.number).split('.')[1] ?? '').length;
      const factor = Math.pow(10, decimals);

      this.start = 0;

      const step = (timestamp) => {
        if (!this.start) {
          this.start = timestamp;
        }

        const progress = timestamp - this.start;
        const percentage = Math.min(progress / (this.duration * 1000), 1);
        const value = percentage === 1 ? target : Math.floor(percentage * target * factor) / factor;

        element.textContent = value.toLocaleString(undefined, {
          minimumFractionDigits: decimals,
          maximumFractionDigits: decimals,
        });

        if (progress < this.duration * 1000) {
          window.requestAnimationFrame(step);
        }
      };

      window.requestAnimationFrame(step);
    });
  },
});
