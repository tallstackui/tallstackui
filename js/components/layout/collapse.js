export default {
    open: false,
    mobile: false,
    init() {
        const media = window.matchMedia('(max-width: 768px)');
        this.mobile = media.matches;

        if (this.mobile) {
            this.open = false;
        }

        media.addEventListener('change', (e) => {
            this.mobile = e.matches;

            if (this.mobile) {
                this.open = false;
            }
        });

        const sidebar = localStorage.getItem('side-bar');

        this.open = sidebar !== null ? JSON.parse(sidebar) : true;

        if (this.mobile) {
            this.open = false;
        }
    },
    /**
     * Toggle the sidebar.
     *
     * @param {Boolean|Null} value
     *
     * @return {void}
     */
    toggle(value = null) {
        this.open = value ?? !this.open;

        localStorage.setItem('side-bar', JSON.stringify(this.open));
    },
};
