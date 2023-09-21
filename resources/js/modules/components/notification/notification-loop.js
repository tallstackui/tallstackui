export default (notification) => ({
    notification :  notification,
    show : false,
    init() {
        this.$nextTick(() => this.show = true);

        setTimeout(() => this.hide(), this.notification.timeout);
    },
    accept(notification) {
        let params = notification.options.confirm.params ?? null;

        this.$dispatch(notification.options.confirm.event, params.constructor !== Array ? [params] : [...params]);

        this.hide();
    },
    reject(notification) {
        let params = notification.options.cancel.params ?? null;

        this.$dispatch(notification.options.cancel.event, params.constructor !== Array ? [params] : [...params]);

        this.hide();
    },
    hide() {
        this.show = false;

        setTimeout(() => this.remove(this.notification), this.notification.timeout);
    }
})
