export default (minimize = null) => ({
    show: true,
    minimize: minimize ?? false,
    init(){
        this.setMinWidth();
    },
    setMinWidth() {
        const card = this.$refs.card;
        const width = card.offsetWidth;
        card.style.minWidth = `${width}px`;
    },
});
