export default (text, position) => ({
  visible: false,
  text: text,
  position: position,
  init() {
    this.$refs.content.addEventListener('mouseenter', () => { this.visible = true; }); this.$refs.content.addEventListener('mouseleave', () => { this.visible = false; });
  },
});