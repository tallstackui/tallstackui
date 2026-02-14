export default (data = {}) => ({
  storage: data?.name ?? 'dark-theme',
  mode: (() => {
    const storage = localStorage.getItem(data?.name ?? 'dark-theme');

    // Migrate an old boolean format
    if (storage === 'true') return 'dark';
    if (storage === 'false') return 'light';

    if (['light', 'dark', 'system'].includes(storage)) return storage;

    if (data?.default === 'dark' || data?.default === true) return 'dark';
    if (data?.default === 'system') return 'system';

    return 'light';
  })(),
  darkTheme: false,
  media: null,
  init() {
    const resolve = () => {
      if (this.mode === 'dark') {
        return true;
      }

      if (this.mode === 'light') {
        return false;
      }

      return window.matchMedia('(prefers-color-scheme: dark)').matches;
    };

    this.darkTheme = resolve();

    this.$watch('mode', (value) => {
      localStorage.setItem(this.storage, value);

      this.darkTheme = resolve();

      this.listen();
    });

    this.listen();
  },
  /**
   * Manage the OS preference listener. Attaches when mode is 'system',
   * removes when switching to 'light' or 'dark' to prevent leaks.
   *
   * @return {void}
   */
  listen() {
    const mq = window.matchMedia('(prefers-color-scheme: dark)');

    if (this.media) {
      mq.removeEventListener('change', this.media);

      this.media = null;
    }

    if (this.mode !== 'system') return;

    this.media = (e) => {
      this.darkTheme = e.matches;
    };

    mq.addEventListener('change', this.media);
  },
});
