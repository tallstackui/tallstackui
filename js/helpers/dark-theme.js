export default (data = {}) => ({
  storage: data?.name ?? 'dark-theme',
  darkTheme: (() => {
    const storage = localStorage.getItem(data?.name ?? 'dark-theme');

    if (storage !== null) {
      return storage === 'true';
    }

    return data?.default === 'dark' || data?.default === true;
  })(),
  init() {
    this.$watch('darkTheme', (value) => localStorage.setItem(this.storage, value));
  },
});
