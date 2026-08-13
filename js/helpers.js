// Initialize the UI elements registry if it doesn't exist
if (!window.__tsui_elements) {
  window.__tsui_elements = [];
}

// Initialize the floating scroll-lock registry if it doesn't exist
if (!window.__tsui_floating_locks) {
  window.__tsui_floating_locks = [];
}

// Initialize the registry of floatings currently on screen if it doesn't exist
if (!window.__tsui_floating_open) {
  window.__tsui_floating_open = [];
}

/**
 * @param message {String}
 * @return {void}
 */
export const warning = (message) => console.warn(`[TallStackUI] ${message}`);

/**
 * @param message {String}
 * @return {void}
 */
export const error = (message) => console.error(`[TallStackUI] ${message}`);

/**
 * @param name {String}
 * @param params {Object|Null}
 * @param prefix {Boolean}
 */
export const event = (name, params = null, prefix = true) => {
  const identification = prefix ? `ts-ui:${name}` : name;

  window.dispatchEvent(new CustomEvent(identification, params ? { detail: params } : {}));
};

// Locking components that keep a countable live holder. Loading, Editor and
// Upload lock the body without registering anywhere, so an absent entry proves
// nothing about them and they are never treated as gone.
const COUNTABLE = ['modal', 'slide', 'dialog', 'carousel', 'gallery', 'command-palette'];

/**
 * How many elements of a type still hold the body lock, or null when the type
 * keeps no record of itself.
 *
 * @param type {String|Null}
 * @return {Number|Null}
 */
const holders = (type) => {
  if (type === 'floating') {
    return window.__tsui_floating_locks.length;
  }

  if (!COUNTABLE.includes(type)) {
    return null;
  }

  return window.__tsui_elements.filter((element) => element.type === type).length;
};

/**
 * Track which floatings are on screen, so an overlay can tell whether a popup
 * of its own owns the escape key. Floatings stay out of `__tsui_elements` on
 * purpose (see `floating_overflow`), so they need a registry of their own.
 *
 * @param status {Boolean}
 * @param id {String}
 * @return {void}
 */
export const floating_visibility = (status, id) => {
  const stack = window.__tsui_floating_open;
  const index = stack.indexOf(id);

  if (status && index === -1) {
    stack.push(id);

    return;
  }

  if (!status && index !== -1) {
    stack.splice(index, 1);
  }
};

/**
 * Claim the escape key for a floating, marking the event so overlays further
 * along the propagation can tell it was already spent.
 *
 * @param event {Event}
 * @return {Boolean}
 */
export const escape_claim = (event) => {
  if (event) {
    event.__tsui_escape_claimed = true;
  }

  return true;
};

/**
 * Whether a floating owns this escape press. Both halves are needed because
 * the listeners all sit on `window` and their order is the order they were
 * registered: an overlay running first sees the popup still open, and one
 * running last sees the claim the popup left on the event.
 *
 * @param event {Event}
 * @return {Boolean}
 */
export const escape_claimed = (event) =>
  event?.__tsui_escape_claimed === true || window.__tsui_floating_open.length > 0;

/**
 * @param status {Boolean}
 * @param component {String|Null}
 * @param skip {Boolean|Null}
 */
export const overflow = (status, component = null, skip = false) => {
  if (skip) {
    return;
  }

  const element = document.body;
  const key = 'data-overflow';
  const current = element.getAttribute(key);
  const has = current !== null;

  const set = () => {
    // Must be read before locking, and it is the width the scrollbar takes
    // from layout rather than whether the page scrolls. Overlay scrollbars
    // take none, so there is nothing to give back.
    const gutter = window.innerWidth - document.documentElement.clientWidth;

    element.style.setProperty('overflow', 'hidden', 'important');
    element.setAttribute(key, component);

    if (gutter > 0) {
      element.style.paddingRight = `${gutter}px`;

      // Published so full bleed elements can bleed back into the reserved
      // strip. Unset while the body is free, so the fallback is what applies.
      document.documentElement.style.setProperty('--tsui-scrollbar-offset', `${gutter}px`);
    }
  };

  const reset = () => {
    element.removeAttribute(key);
    element.style.removeProperty('overflow');
    element.style.paddingRight = '';

    document.documentElement.style.removeProperty('--tsui-scrollbar-offset');
  };

  if (status) {
    if (!has || current === component) {
      set();
    }

    return;
  }

  if (!has) {
    return;
  }

  // Only whoever took the lock may give it back. Without this an inner
  // element (a Loading inside a Modal, a Floating inside a Slide) unlocks
  // the body while the outer one is still on screen.
  //
  // Unless the owner is provably gone: a floating can take the lock before a
  // modal opens, and the modal is then the last one out with a marker it does
  // not own. Bailing there leaves the body locked for good.
  if (current !== component && holders(current) !== 0) {
    return;
  }

  const foreign = window.__tsui_elements.some((element) => element.type !== component);
  const siblings = window.__tsui_elements.filter((element) => element.type === component).length;

  // `siblings` still counts the caller, since components unregister only
  // after releasing the lock. More than one means a same-type element
  // (a stacked modal) is still open.
  if (!foreign && siblings <= 1) {
    reset();
  }
};

/**
 * Refcounted scroll-lock for floating popups.
 *
 * Nested floatings (a Dropdown submenu inside its own dropdown) must neither
 * re-lock nor release early: the first to open takes the lock and only the
 * last to close gives it back. Deliberately kept out of `__tsui_elements` so
 * floatings never join the stack that decides which overlay owns escape and
 * click-outside.
 *
 * @param status {Boolean}
 * @param id {String}
 */
export const floating_overflow = (status, id) => {
  const stack = window.__tsui_floating_locks;
  const index = stack.indexOf(id);

  if (status) {
    if (index === -1) {
      stack.push(id);
    }

    if (stack.length === 1) {
      overflow(true, 'floating');
    }

    return;
  }

  if (index === -1) {
    return;
  }

  stack.splice(index, 1);

  if (stack.length === 0) {
    overflow(false, 'floating');
  }
};

/**
 * @param change {Object|Null}
 * @param model {*}
 */
export const wireChange = (change, model) => {
  if (!change) {
    return;
  }

  Livewire.find(change.id).call(change.method, model);
};

/**
 * Swallow a keypress on a control the browser refuses to lock through
 * `readonly`. Tab goes through, so the control keeps its place in the tab order.
 *
 * @param event {KeyboardEvent}
 * @return {void}
 */
export const lock_keydown = (event) => {
  if (event.key === 'Tab') {
    return;
  }

  event.preventDefault();
};

/**
 * Spread into the Alpine data, then bail out of every method that
 * mutates state while `locked()` is true.
 *
 * @param disabled {Boolean}
 * @param readonly {Boolean}
 * @return {Object}
 */
export const lockable = (disabled = false, readonly = false) => ({
  disabled: disabled,
  readonly: readonly,
  locked() {
    return this.disabled || this.readonly;
  },
});

/** @returns {string} */
export const unique = () =>
  [...crypto.getRandomValues(new Uint8Array(12))]
    .map((b) => b.toString(36).padStart(2, '0'))
    .join('')
    .substring(0, 15);

/**
 * @param {String} id
 * @param {String} type
 */
export const register_ui_element = (id, type) => {
  window.__tsui_elements.push({ id, type });
};

/**
 * @param {String} id - Unique ID of the element
 */
export const unregister_ui_element = (id) => {
  const index = window.__tsui_elements.findIndex((item) => item.id === id);

  if (index > -1) {
    window.__tsui_elements.splice(index, 1);
  }
};

/**
 * Clear the UI elements registry and restore the body scroll-lock.
 *
 * On SPA navigation (wire:navigate) an open overlay is destroyed without its
 * close watcher ever running, leaving an orphaned entry in the global registry
 * that survives the page swap. The orphan poisons the unlock gate on the next
 * page and the body stays locked forever. Flushing on navigation drops the
 * orphans and force-restores the body.
 *
 * @return {void}
 */
export const flush_ui_elements = () => {
  window.__tsui_elements = [];
  window.__tsui_floating_locks = [];
  window.__tsui_floating_open = [];

  const element = document.body;

  element.removeAttribute('data-overflow');
  element.style.removeProperty('overflow');
  element.style.paddingRight = '';

  document.documentElement.style.removeProperty('--tsui-scrollbar-offset');
};

/**
 * @param {String} id
 * @returns {Boolean}
 */
export const top_ui_element = (id) => {
  return (
    window.__tsui_elements.length > 0 &&
    window.__tsui_elements[window.__tsui_elements.length - 1].id === id
  );
};
