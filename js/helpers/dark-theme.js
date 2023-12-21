/**
 * @param storage {String|Null}
 * @param initialize {String|Null}
 */
export default (storage = null, initialize = null) => ({
  storage: storage ?? 'dark-theme',
  darkTheme: Boolean(initialize) === true ? true : (localStorage.getItem(storage ?? 'dark-theme') === 'true'),
  init() {
    const dark = localStorage.getItem(this.storage);

    this.darkTheme = Boolean(initialize) === true ? true : (dark === 'true');

    this.$watch('darkTheme', (value) => localStorage.setItem(this.storage, value));
  },
});
