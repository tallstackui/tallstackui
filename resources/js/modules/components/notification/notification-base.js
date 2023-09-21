export default () => ({
    notifications: [],
    add(event) {
        event.detail.id = event.timeStamp;
        this.notifications.push(event.detail)
    },
    remove(notification) {
        this.notifications = this.notifications.filter(element => element.id !== notification.id)
    }
})
