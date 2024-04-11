export default (flash) => ({
  show: false,
  toasts: [],
  init () {
    if (flash) window.onload = () => this.add(flash);
  },
  add(event) {
    this.$nextTick(() => this.show = true);

    if (event.detail) event.detail.id = event.timeStamp;

    this.toasts.push(event.detail ?? flash);
  },
  remove(notification) {
    this.toasts = this.toasts.filter((element) => element.id !== notification.id);
  },
});
