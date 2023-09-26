export default (storage = null) => ({
  storage: storage ?? 'dark-theme',
  darkTheme: localStorage.getItem(storage ?? 'dark-theme') === 'true',
  init() {
    const dark = localStorage.getItem(this.storage);

    this.darkTheme = dark === 'true';

    this.$watch('darkTheme', (val) => localStorage.setItem(this.storage, val));
  },
});
