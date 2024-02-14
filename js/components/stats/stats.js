export default (number, animated) => ({
  visible: false,
  start: 0,
  number: number,
  animated: animated,
  duration: 3,
  init() {
    this.$watch('visible', () => {
      if (animated === false) {
        return;
      }

      const number = this.$refs.number;

      const easeOutQuart = (percentage) => 1 - (--percentage) * percentage * percentage * percentage;

      const step = (timestamp) => {
        if (!this.start) this.start = timestamp;
        const percentage = Math.min((timestamp - this.start) / (this.duration * 1000), 1);
        const value = Math.floor(easeOutQuart(percentage) * this.number);

        if (percentage === 1 || value >= this.number) {
          number.innerHTML = this.number.toLocaleString();
          return;
        }

        number.innerHTML = value?.toLocaleString();
        window.requestAnimationFrame(step);
      };

      window.requestAnimationFrame(step);
    });
  },
});
