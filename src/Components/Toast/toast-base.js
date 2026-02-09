export default (flash, position = null, flashGlobal = false) => ({
  show: false,
  toasts: [],
  position: position,
  init() {
    if (flash) window.onload = () => this.add(flash);
    if (flash)
      document.addEventListener('livewire:navigated', () => this.add(flash), { once: true });
  },
  /**
   * Add a new toast to the list.
   *
   * @param {Event} event
   * @return {void}
   */
  add(event) {
    this.$nextTick(() => (this.show = true));

    if (flash) {
      // Since flash tends to be something to be
      // displayed later, we clear the array before
      // sending to prevent duplication.
      this.toasts = [];

      this.toasts.push(flash);
    }

    if (event.detail) {
      if (event.detail.sole && this.toasts.length > 0) {
        this.toasts = [];
      }

      event.detail.id ??= `${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;

      this.position = event.detail.position ?? this.position;

      this.toasts.push(event.detail);
    }
  },
  /**
   * Remove a toast from the list.
   *
   * @param {Object} toast
   * @return {void}
   */
  remove(toast) {
    this.toasts = this.toasts.filter((element) => element.id !== toast.id);
  },
  /**
   * Toast transitions.
   *
   * @returns {Object}
   */
  transition: flashGlobal
    ? {}
    : {
        'x-transition:enter': 'transform ease-out duration-300 transition',
        'x-transition:enter-start'() {
          // eslint-disable-next-line max-len
          return `translate-y-2 opacity-0 sm:translate-y-0 ${this.position.includes('-left') ? 'sm:-translate-x-2' : 'sm:translate-x-2'}`;
        },
        'x-transition:enter-end': 'translate-y-0 opacity-100 sm:translate-x-0',
      },
});
