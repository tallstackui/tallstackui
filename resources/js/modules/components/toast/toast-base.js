export default () => ({
    toasts: [],
    add(event) {
        event.detail.id = event.timeStamp;

        this.toasts.push(event.detail)
    },
    remove(notification) {
        this.toasts = this.toasts.filter(element => element.id !== notification.id)
    }
})
