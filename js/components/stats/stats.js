export default (number, animated) => ({
  visible: false,
  start: 0,
  number: number,
  animated: animated,
  duration: 1,
  init() {
    this.$watch('visible', () => {
      if (animated === false) {
        return;
      }

      const number = this.$refs.number;

      const step = (timestamp) => {
        if (!this.start) this.start = timestamp;

        const progress = timestamp - this.start;
        const percentage = Math.min(progress / (this.duration * 1000), 1);
        const value = Math.floor(percentage * this.number);

        number.innerHTML = value.toLocaleString();

        if (progress < (this.duration * 1000)) {
          window.requestAnimationFrame(step);
        }
      };
      window.requestAnimationFrame(step);
    });
  },
});
