import { error, overflow, register_ui_element, unregister_ui_element } from '../../../js/helpers';
import { body } from '../Form/Select/helpers';

export default (request = null, selectable = {}, options = null, shortcut = 'ctrl.k', smooth = true) => ({
  show: false,
  search: '',
  selected: -1,
  selectable: selectable,
  response: [],
  loading: false,
  _options: [],
  _availableCache: [],
  _availableDirty: true,
  _navigateOptions: null,
  _normalize: false,
  _debounce: null,
  init() {
    if (options && Array.isArray(options) && options.length > 0) {
      this._options = options;
      this.preNormalize(this._options);
      this.invalidateAvailable();
    }

    this.registerShortcut(shortcut);

    this.$watch('search', () => {
      this.invalidateAvailable();

      if (request) {
        clearTimeout(this._debounce);
        this._debounce = setTimeout(() => this.makeRequest(), 300);
      }
    });
  },
  registerShortcut(shortcut) {
    const parts = shortcut.split('.');
    const key = parts[parts.length - 1].toLowerCase();
    const modifiers = parts.slice(0, -1).map((m) => m.toLowerCase());

    window.addEventListener('keydown', (event) => {
      if (event.key.toLowerCase() !== key) return;

      const ctrl = modifiers.includes('ctrl');
      const meta = modifiers.includes('meta');
      const shift = modifiers.includes('shift');
      const alt = modifiers.includes('alt');

      if ((ctrl || meta) && !(event.ctrlKey || event.metaKey)) return;
      if (shift && !event.shiftKey) return;
      if (alt && !event.altKey) return;

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
    if (!request || this.search.length < 1) {
      this.response = [];
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

      this.preNormalize(this.response);
      this.invalidateAvailable();
      this.selected = -1;
    } catch (e) {
      error(e.message);
    } finally {
      this.loading = false;
    }
  },
  invalidateAvailable() {
    this._availableDirty = true;
    this._navigateOptions = null;
  },
  preNormalize(items) {
    if (!items || !Array.isArray(items)) return;

    for (let i = 0; i < items.length; i++) {
      const item = items[i];

      if (!item || typeof item !== 'object') continue;

      const label = item[selectable.label];

      if (label) {
        item.__normalized = this.normalize(label.toString().toLowerCase());
      }

      const desc = item[selectable.description];

      if (desc) {
        item.__normalizedDesc = this.normalize(desc.toString().toLowerCase());
      }
    }
  },
  selectOption(option) {
    if (!option || option.disabled) return;

    window.dispatchEvent(
      new CustomEvent('tallstackui:command-palette', { detail: option })
    );

    this.close();
  },
  navigate(direction) {
    const items = this.available;

    if (!items || items.length === 0) return;

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
  normalize(string) {
    if (!string) return '';

    if (!this._normalize) this._normalize = new Map();

    if (this._normalize.has(string)) {
      return this._normalize.get(string);
    }

    const normalized = string.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    this._normalize.set(string, normalized);

    if (this._normalize.size > 100) {
      const key = this._normalize.keys().next().value;

      this._normalize.delete(key);
    }

    return normalized;
  },
  get available() {
    if (!this._availableDirty) return this._availableCache;

    const items = request ? this.response : this._options;

    if (!items || items.length === 0) {
      this._availableCache = [];
      this._availableDirty = false;

      return this._availableCache;
    }

    if (this.search === '') {
      this._availableCache = items;
      this._availableDirty = false;

      return this._availableCache;
    }

    if (request) {
      this._availableCache = items;
      this._availableDirty = false;

      return this._availableCache;
    }

    const search = this.normalize(this.search.toLowerCase());

    this._availableCache = items.filter((option) => {
      if (!option) return false;

      const label = option.__normalized;

      if (!label) return false;

      if (label.indexOf(search) !== -1) return true;

      if (option.__normalizedDesc) {
        return option.__normalizedDesc.indexOf(search) !== -1;
      }

      return false;
    });

    this._availableDirty = false;

    return this._availableCache;
  },
});
