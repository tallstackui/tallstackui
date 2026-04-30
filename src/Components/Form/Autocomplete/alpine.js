import { error, wireChange } from '../../../../js/helpers';

const normalize = (text) => String(text ?? '').toLowerCase();

export default (model = null, items = [], request = null, strict = false, lazy = null) => ({
  model: model,
  items: Array.isArray(items) ? items : [],
  request: request,
  strict: strict,
  lazy: lazy,
  show: false,
  loading: false,
  search: '',
  highlighted: -1,
  available: [],
  selected: null,
  _abort: null,
  _debounce: null,
  init() {
    this.items = this.normalizeItems(this.items);

    if (this.model) {
      this.selected = this.findByValue(this.model);
      this.search = this.selected ? this.selected.value : this.strict ? '' : this.model;

      if (this.strict && !this.selected) {
        this.model = null;
      }
    }

    this.recompute();

    this.$watch('show', (value) => {
      if (value) {
        this.highlighted = -1;

        if (this.request) {
          this.fetch();
        } else {
          this.recompute();
        }

        this.$dispatch('open');

        return;
      }

      this.highlighted = -1;

      if (this.strict) {
        this.search = this.selected ? this.selected.value : '';
      }

      this.$dispatch('close');
    });

    this.$watch('model', (value) => {
      if (this.show) {
        return;
      }

      const matched = this.findByValue(value);

      this.selected = matched ?? this.selected;
      this.search = matched ? matched.value : this.strict ? '' : (value ?? '');
    });

    this.$watch('highlighted', (index) => {
      if (index < 0 || !this.$refs.floating) {
        return;
      }

      const row = this.$refs.floating.querySelector(`[data-index="${index}"]`);

      row?.scrollIntoView({ block: 'nearest' });
    });
  },

  /**
   * Normalize the items so optional fields exist on every entry.
   *
   * @param {Array} items
   * @returns {Array}
   */
  normalizeItems(items) {
    return items.map((item) => {
      if (typeof item === 'string') {
        return { value: item, description: null, image: null, disabled: false };
      }

      return {
        value: item.value,
        description: item.description ?? null,
        image: item.image ?? null,
        disabled: !!item.disabled,
      };
    });
  },

  /**
   * Look up an item by its `value`. Used to resync `selected` when the
   * Livewire model changes externally.
   *
   * @param {*} value
   * @returns {Object|null}
   */
  findByValue(value) {
    if (value === null || value === undefined || value === '') {
      return null;
    }

    return this.items.find((item) => item.value === value) ?? null;
  },

  /**
   * Compute the rows currently visible in the dropdown for a local items list.
   *
   * @returns {void}
   */
  recompute() {
    const term = normalize(this.search);

    if (!term) {
      this.available = this.items;

      return;
    }

    this.available = this.items.filter((item) => {
      return (
        normalize(item.value).includes(term) ||
        (item.description && normalize(item.description).includes(term))
      );
    });
  },

  /**
   * Open the dropdown. Side-effects (fetch / recompute / dispatch) live
   * inside the `show` watcher so the state stays consistent regardless of
   * how `show` was flipped (toggle, click outside, watcher, etc.).
   *
   * @returns {void}
   */
  open() {
    this.show = true;
  },

  /**
   * Toggle the dropdown from a user-initiated click. Suppresses the open
   * when:
   *   - the lazy threshold isn't satisfied yet (regardless of source);
   *   - the source is remote and there's nothing to query yet.
   *
   * Both gates exist so the panel never pops up empty just because the
   * user clicked into the input.
   *
   * @returns {void}
   */
  toggle() {
    if (this.show) {
      this.show = false;

      return;
    }

    if (this.lazy && (this.search ?? '').length < this.lazy) {
      return;
    }

    if (this.request && !(this.search ?? '').toString().length) {
      return;
    }

    this.show = true;
  },

  /**
   * Close the dropdown.
   *
   * @returns {void}
   */
  close() {
    this.show = false;
  },

  /**
   * React to typing in the input.
   *
   * @returns {void}
   */
  onInput() {
    if (!this.strict) {
      this.model = this.search;
    }

    // Under the lazy threshold the panel must stay closed. If it was
    // already open (e.g. the user typed past the threshold and is now
    // backspacing), close it explicitly — otherwise the panel would
    // hang around showing "no results".
    if (this.lazy && this.search.length < this.lazy) {
      this.available = [];
      this.loading = false;
      this.show = false;

      return;
    }

    // For remote sources, an empty query is not a valid request — stay
    // closed (the user will reopen by typing) instead of flashing a
    // useless panel.
    if (this.request && !this.search) {
      this.available = [];
      this.loading = false;
      this.show = false;

      return;
    }

    if (!this.show) {
      this.show = true;

      return;
    }

    if (this.request) {
      this.fetch();
    } else {
      this.recompute();
      this.highlighted = this.available.length > 0 ? 0 : -1;
    }
  },

  /**
   * Pick an item from the dropdown.
   *
   * @param {Object} item
   * @returns {void}
   */
  pick(item) {
    if (item.disabled) {
      return;
    }

    this.selected = item;
    this.search = item.value;
    this.model = item.value;

    this.$dispatch('select', { item });

    this.close();

    wireChange(null, this.model);
  },

  /**
   * Clear the input and reset the selection.
   *
   * @returns {void}
   */
  clear() {
    this.search = '';
    this.model = this.strict ? null : '';
    this.selected = null;
    this.available = this.items;
    this.highlighted = -1;

    this.$dispatch('clear');
    this.$refs.input?.focus();
  },

  /**
   * Keyboard navigation handler.
   *
   * @param {KeyboardEvent} event
   * @returns {void}
   */
  navigate(event) {
    if (event.key === 'Escape') {
      if (this.show) {
        event.preventDefault();
        this.close();
      }

      return;
    }

    if (event.key === 'Tab') {
      this.close();

      return;
    }

    if (event.key === 'ArrowDown') {
      event.preventDefault();

      if (!this.show) {
        this.open();

        return;
      }

      if (this.available.length === 0) {
        return;
      }

      this.highlighted = (this.highlighted + 1) % this.available.length;

      return;
    }

    if (event.key === 'ArrowUp') {
      event.preventDefault();

      if (!this.show || this.available.length === 0) {
        return;
      }

      this.highlighted = this.highlighted <= 0 ? this.available.length - 1 : this.highlighted - 1;

      return;
    }

    if (event.key === 'Enter') {
      if (!this.show) {
        return;
      }

      const target = this.highlighted >= 0 ? this.available[this.highlighted] : null;

      if (target) {
        event.preventDefault();
        this.pick(target);

        return;
      }

      if (this.strict) {
        event.preventDefault();
        this.close();
      }
    }
  },

  /**
   * Fetch items from the configured remote endpoint, debounced.
   *
   * @returns {void}
   */
  fetch() {
    if (!this.request) {
      return;
    }

    if (!(this.search ?? '').toString().length) {
      this.available = [];
      this.loading = false;

      return;
    }

    if (this._debounce) {
      clearTimeout(this._debounce);
    }

    // Flip loading immediately so the dropdown shows the spinner during
    // the debounce window instead of flashing the "no results" placeholder.
    this.loading = true;

    this._debounce = setTimeout(() => {
      if (this._abort) {
        this._abort.abort();
      }

      this._abort = new AbortController();

      const params = this.$refs.params ? JSON.parse(this.$refs.params.textContent || '{}') : {};

      const url = new URL(this.request.url, window.location.origin);
      const init = { signal: this._abort.signal, headers: { Accept: 'application/json' } };

      if ((this.request.method ?? 'get').toLowerCase() === 'post') {
        init.method = 'POST';
        init.headers['Content-Type'] = 'application/json';
        init.body = JSON.stringify({ search: this.search, ...params });
      } else {
        url.searchParams.set('search', this.search ?? '');

        for (const [key, value] of Object.entries(params)) {
          url.searchParams.set(key, value);
        }
      }

      fetch(url, init)
        .then((response) => (response.ok ? response.json() : Promise.reject(response)))
        .then((data) => {
          const list = Array.isArray(data) ? data : (data?.data ?? []);

          this.items = this.normalizeItems(list);
          this.available = this.items;
          this.highlighted = this.available.length > 0 ? 0 : -1;
        })
        .catch((reason) => {
          if (reason?.name === 'AbortError') {
            return;
          }

          error(`Autocomplete request failed: ${reason?.statusText ?? reason}`); // AI: remove it
          this.available = [];
        })
        .finally(() => {
          this.loading = false;
          this._abort = null;
        });
    }, 250);
  },
});
