export default () => ({
    tallStackUiMenuMobile: false,
    init() {
        this.$watch('tallStackUiMenuMobile', (value) => {
            const html = document.querySelector('html');

            Alpine.store('tsui').toggle(false);

            if (value) {
                html.classList.add('overflow-hidden');
            }
            
            if (!value) {
                html.classList.remove('overflow-hidden');
            }
        });
    },
});
