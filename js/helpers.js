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
  // When true, then we need to preserve the
  // overflow avoiding to hiding the scrollbar.
  if (skip) return;

  // The strategy here was adopted to prevent the loading component
  // from removing overflow when used in conjunction with other
  // components that handle overflow: modal, slide, dialogs.
  const element = document.querySelector('body');
  const key = 'data-overflow';
  const exists = [...element.attributes].some((attr) => attr.name === key);

  if (status && (!exists || element.getAttribute(key) === component)) {
    element.style.setProperty('overflow', 'hidden', 'important');
    element.setAttribute(key, component);
    // Prevent the scrollbar jump when the scrollbar is visible.
    if (document.documentElement.scrollHeight > document.documentElement.clientHeight) {
      element.style.paddingRight = '15px';
    }
  } else if (!status && exists && element.getAttribute(key) === component) {
    // Check if there are any other UI elements of the same type still open
    // If there are, don't remove the overflow style
    const elements =
      window.__tsui_elements && window.__tsui_elements.some((item) => item.type === component);

    if (!elements) {
      const others = window.__tsui_elements && window.__tsui_elements.length > 0;

      if (!others) {
        element.removeAttribute(key);
        element.style.removeProperty('overflow');
        element.style.paddingRight = '';
      }
    }
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
