export default () => ({
    show : false,
    toasts: [],
    add(event) {
        this.$nextTick(() => this.show = true);

        event.detail.id = event.timeStamp;

        this.toasts.push(event.detail)
    },
    remove(notification) {
        this.toasts = this.toasts.filter(element => element.id !== notification.id)
    }
})
