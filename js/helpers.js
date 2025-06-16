// Initialize the UI elements registry if it doesn't exist
if (!window.__tsui_elements) {
  window.__tsui_elements = [];
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
  const identification = prefix ? `tallstackui:${name}` : name;

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

  const last = window.__tsui_elements.length === 1;
  const same = window.__tsui_elements.some(el => el.type === component);
  const others = window.__tsui_elements.length > 0;

  if (last || (!same && !others)) {
    reset();
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
 * @param {String} id
 * @returns {Boolean}
 */
export const top_ui_element = (id) => {
  return (
    window.__tsui_elements.length > 0 &&
    window.__tsui_elements[window.__tsui_elements.length - 1].id === id
  );
};
