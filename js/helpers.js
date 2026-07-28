// Initialize the UI elements registry if it doesn't exist
if (!window.__tsui_elements) {
  window.__tsui_elements = [];
}

// Initialize the floating scroll-lock registry if it doesn't exist
if (!window.__tsui_floating_locks) {
  window.__tsui_floating_locks = [];
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

/**
 * @param status {Boolean}
 * @param component {String|Null}
 * @param skip {Boolean|Null}
 */
export const overflow = (status, component = null, skip = false) => {
  if (skip) return;

  const element = document.body;
  const key = 'data-overflow';
  const current = element.getAttribute(key);
  const has = current !== null;

  const set = () => {
    element.style.setProperty('overflow', 'hidden', 'important');
    element.setAttribute(key, component);

    const scroll = document.documentElement.scrollHeight > document.documentElement.clientHeight;

    if (scroll) {
      element.style.paddingRight = '15px';
    }
  };

  const reset = () => {
    element.removeAttribute(key);
    element.style.removeProperty('overflow');
    element.style.paddingRight = '';
  };

  if (status) {
    if (!has || current === component) {
      set();
    }

    return;
  }

  if (!has) return;

  // Only whoever took the lock may give it back. Without this an inner
  // element (a Loading inside a Modal, a Floating inside a Slide) unlocks
  // the body while the outer one is still on screen.
  if (current !== component) {
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

  const element = document.body;

  element.removeAttribute('data-overflow');
  element.style.removeProperty('overflow');
  element.style.paddingRight = '';
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
