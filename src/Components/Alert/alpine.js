export default (dismiss) => ({
  show: true,
  init() {
    if (dismiss) {
      setTimeout(() => (this.show = false), dismiss * 1000);
    }
  },
});
