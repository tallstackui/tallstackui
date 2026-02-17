import {
  error,
  event,
  overflow,
  register_ui_element,
  unregister_ui_element,
} from '../../../js/helpers';
import { body } from '../Form/Select/helpers';

export default (
  request,
  selectable = {},
  shortcutKey = 'ctrl.k',
  recycle = false,
  url = null,
  inline = false,
  id = 'command-palette'
) => ({
  show: false,
  search: '',
  selected: -1,
  selectable: selectable,
  response: [],
  loading: false,
  fetched: false,
  _url: url,
  _inline: inline,
  _cache: [],
  _dirty: true,
  _options: null,
  _keyboard: false,
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
  /**
   * Register the keyboard shortcut listener for toggling the palette.
   *
   * @param {String} key
   * @return {void}
   */
  shortcut(key) {
    const parts = key.split('.');
    const letter = parts[parts.length - 1].toLowerCase();
    const modifiers = parts.slice(0, -1).map((m) => m.toLowerCase());

    window.addEventListener('keydown', (e) => {
      if (e.key.toLowerCase() !== letter) {
        return;
      }

      const ctrl = modifiers.includes('ctrl');
      const meta = modifiers.includes('meta');
      const shift = modifiers.includes('shift');
      const alt = modifiers.includes('alt');

      if ((ctrl || meta) && !(e.ctrlKey || e.metaKey)) {
        return;
      }

      if (shift && !e.shiftKey) {
        return;
      }

      if (alt && !e.altKey) {
        return;
      }

      e.preventDefault();

      this.show ? this.close() : this.open();
    });
  },
  /**
   * Open the command palette and reset state.
   *
   * @return {void}
   */
  open() {
    this.show = true;
    this.search = '';
    this.selected = -1;
    this._dirty = true;
    this._options = null;

    if (!recycle) {
      this.response = [];
    }

    overflow(true, id);
    register_ui_element(id, 'command-palette');

    this.$nextTick(() => this.$refs.search?.focus());

    this.$dispatch('open');
    event(`command-palette:${id}:open`, null, false);
  },
  /**
   * Close the command palette and restore overflow.
   *
   * @return {void}
   */
  close() {
    this.show = false;

    overflow(false, id);
    unregister_ui_element(id);

    this.$dispatch('close');
    event(`command-palette:${id}:close`, null, false);
  },
  /**
   * Fetch search results from the server endpoint.
   *
   * @return {Promise<void>}
   */
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
  /**
   * Mark the available options cache as dirty.
   *
   * @return {void}
   */
  invalidateAvailable() {
    this._dirty = true;
    this._options = null;
  },
  /**
   * Strip internal keys prefixed with '__' from an option.
   *
   * @param {Object} option
   * @return {Object}
   */
  sanitize(option) {
    return Object.fromEntries(Object.entries(option).filter(([key]) => !key.startsWith('__')));
  },
  /**
   * Map an option's keys to the standard selectable fields.
   *
   * @param {Object} option
   * @return {Object}
   */
  remap(option) {
    return {
      label: option[this.selectable.label] ?? null,
      value: option[this.selectable.value] ?? null,
      description: option[this.selectable.description] ?? null,
      image: option[this.selectable.image] ?? null,
      icon: option[this.selectable.icon] ?? null,
      additional: option.additional ?? {},
    };
  },
  /**
   * Handle option selection using the priority chain:
   * inline event > actionable > global event.
   *
   * @param {Object} option
   * @return {Promise<void>}
   */
  async selectOption(option) {
    if (!option || option.disabled) {
      return;
    }

    const sanitized = this.sanitize(option);

    if (this._inline) {
      this.$dispatch('select', sanitized);
      this.close();

      return;
    }

    if (this._url) {
      await this.executeAction(this.remap(option));

      return;
    }

    event(`command-palette:${id}:select`, sanitized, false);

    this.close();
  },
  /**
   * Send the selected item to the actionable server endpoint.
   *
   * @param {Object} item
   * @return {Promise<void>}
   */
  async executeAction(item) {
    try {
      this.loading = true;

      const token = document.head.querySelector('[name="csrf-token"]')?.getAttribute('content');

      const response = await fetch(this._url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          ...(token ? { 'X-CSRF-TOKEN': token } : {}),
        },
        body: JSON.stringify({
          item: item,
          search: this.search,
        }),
      });

      if (!response.ok) {
        throw new Error(`Action request failed: ${response.statusText}`);
      }

      const callback = await response.json();

      this.handleCallback(callback);
    } catch (e) {
      error(e.message);
    } finally {
      this.loading = false;
    }
  },
  /**
   * Process the server callback response (redirect or event).
   *
   * @param {Object} callback
   * @return {void}
   */
  handleCallback(callback) {
    this.close();

    if (callback.type === 'redirect') {
      if (callback.external) {
        window.open(callback.data.to, '_blank');
      } else if (callback.navigate && window.Livewire) {
        window.Livewire.navigate(callback.data.to);
      } else {
        window.location.href = callback.data.to;
      }

      return;
    }

    if (callback.type === 'event') {
      event(callback.data.name, callback.data.params ?? {}, false);
    }
  },
  /**
   * Update the selected index on mouse hover. Ignored during keyboard navigation.
   *
   * @param {Number} index
   * @return {void}
   */
  mouseHover(index) {
    if (this._keyboard) return;

    this.selected = index;
  },
  /**
   * Move selection up or down with keyboard arrows.
   *
   * @param {String} direction - 'next' or 'previous'
   * @return {void}
   */
  navigate(direction) {
    const items = this.available;

    if (!items || items.length === 0) {
      return;
    }

    this._keyboard = true;

    const current = this.selected;
    const max = items.length - 1;

    if (direction === 'next') {
      this.selected = current >= max ? 0 : current + 1;
    } else {
      this.selected = current <= 0 ? max : current - 1;
    }

    if (!this._options) {
      this._options = this.$refs.list?.querySelectorAll('[role="option"]');
    }

    const options = this._options;

    if (options && this.selected >= 0 && this.selected < options.length) {
      options[this.selected].scrollIntoView({ block: 'nearest' });
    }
  },
  /**
   * Get the cached list of available options.
   *
   * @return {Array}
   */
  get available() {
    if (!this._dirty) {
      return this._cache;
    }

    if (!this.response || this.response.length === 0) {
      this._cache = [];
      this._dirty = false;

      return this._cache;
    }

    this._cache = this.response;
    this._dirty = false;

    return this._cache;
  },
});
