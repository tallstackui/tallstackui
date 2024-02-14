export default (number, animated, duration) => ({
  visible: false,
  start: 0,
  number: number,
  animated: animated,
  duration: duration,
  init() {
    this.$watch('visible', () => {
      if (animated === false) {
        return;
      }

      const $el = this.$refs.number;
      const easeOutQuart = (t) => 1 - (--t) * t * t * t;

      const step = (timestamp) => {
        if (!this.start) this.start = timestamp;
        const progress = timestamp - this.start;
        const percentage = Math.min(progress / (this.duration * 1000), 1);
        const easedPercentage = easeOutQuart(percentage);
        const value = Math.floor(easedPercentage * this.number);

        if (percentage === 1 || value >= this.number) {
          $el.innerHTML = this.number.toLocaleString();
          return;
        }

        $el.innerHTML = value.toLocaleString();
        window.requestAnimationFrame(step);
      };

      window.requestAnimationFrame(step);
    });
  },
});
