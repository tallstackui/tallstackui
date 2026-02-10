import { error, overflow, register_ui_element, unregister_ui_element } from '../../../js/helpers';
import { body } from '../Form/Select/helpers';

export default (request, selectable = {}, shortcutKey = 'ctrl.k', recycle = false) => ({
  show: false,
  search: '',
  selected: -1,
  selectable: selectable,
  response: [],
  loading: false,
  fetched: false,
  _availableCache: [],
  _availableDirty: true,
  _navigateOptions: null,
  _debounce: null,
  init() {
    this.shortcut(shortcutKey);

    this.$watch('search', () => {
      this.fetched = false;
      this.invalidateAvailable();

      clearTimeout(this._debounce);

      this._debounce = setTimeout(() => this.makeRequest(), 300);
    });
  },
  shortcut(key) {
    const parts = key.split('.');
    const letter = parts[parts.length - 1].toLowerCase();
    const modifiers = parts.slice(0, -1).map((m) => m.toLowerCase());

    window.addEventListener('keydown', (event) => {
      if (event.key.toLowerCase() !== letter) {
        return;
      }

      const ctrl = modifiers.includes('ctrl');
      const meta = modifiers.includes('meta');
      const shift = modifiers.includes('shift');
      const alt = modifiers.includes('alt');

      if ((ctrl || meta) && !(event.ctrlKey || event.metaKey)) {
        return;
      }

      if (shift && !event.shiftKey) {
        return;
      }

      if (alt && !event.altKey) {
        return;
      }

      event.preventDefault();

      this.show ? this.close() : this.open();
    });
  },
  open() {
    this.show = true;
    this.search = '';
    this.selected = -1;
    this._availableDirty = true;
    this._navigateOptions = null;

    if (!recycle) {
      this.response = [];
    }

    overflow(true, 'command-palette');
    register_ui_element('command-palette', 'command-palette');

    this.$nextTick(() => this.$refs.search?.focus());
  },
  close() {
    this.show = false;

    overflow(false, 'command-palette');
    unregister_ui_element('command-palette');
  },
  async makeRequest() {
    if (this.search.length < 1) {
      if (!recycle || this.response.length === 0) {
        this.response = [];
      }

      this.invalidateAvailable();

      return;
    }

    this.loading = true;

    const { url, init } = body(request, this.search, []);

    try {
      const response = await fetch(url, init);
      const data = await response.json();

      this.response = data.map((option) => {
        if (!option[selectable.label]) {
          throw new Error('The [select.label] was not found in the response');
        }

        return {
          ...option,
          [selectable.label]: option[selectable.label].toString(),
        };
      });

      this.invalidateAvailable();
      this.selected = -1;
    } catch (e) {
      error(e.message);
    } finally {
      this.loading = false;
      this.fetched = true;
    }
  },
  invalidateAvailable() {
    this._availableDirty = true;
    this._navigateOptions = null;
  },
  selectOption(option) {
    if (!option || option.disabled) {
      return;
    }

    const sanitized = Object.fromEntries(
      Object.entries(option).filter(([key]) => !key.startsWith('__'))
    );

    window.dispatchEvent(new CustomEvent('tallstackui:command-palette', { detail: sanitized }));

    this.close();
  },
  navigate(direction) {
    const items = this.available;

    if (!items || items.length === 0) {
      return;
    }

    const current = this.selected;
    const max = items.length - 1;

    if (direction === 'next') {
      this.selected = current >= max ? 0 : current + 1;
    } else {
      this.selected = current <= 0 ? max : current - 1;
    }

    if (!this._navigateOptions) {
      this._navigateOptions = this.$refs.list?.querySelectorAll('[role="option"]');
    }

    const options = this._navigateOptions;

    if (options && this.selected >= 0 && this.selected < options.length) {
      options[this.selected].scrollIntoView({ block: 'nearest' });
    }
  },
  get available() {
    if (!this._availableDirty) {
      return this._availableCache;
    }

    if (!this.response || this.response.length === 0) {
      this._availableCache = [];
      this._availableDirty = false;

      return this._availableCache;
    }

    this._availableCache = this.response;
    this._availableDirty = false;

    return this._availableCache;
  },
});
