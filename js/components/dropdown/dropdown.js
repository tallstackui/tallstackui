
export default (animate) => ({
  show: false,
  animate: animate,
  init() {
    window.addEventListener('scroll', () => {
      const element = this.$refs.dropdown?.getBoundingClientRect();
      this.show = (element?.bottom < 0 || element?.top > window.innerHeight && this.show) ? false : this.show;
    });
  },
});
