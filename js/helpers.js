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

  window.dispatchEvent(new CustomEvent(identification, params ? {detail: params} : {}));
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
    element.classList.add('!overflow-hidden');
    element.setAttribute(key, component);
    element.style.paddingRight = '15px'; // Fix the scrollbar jump.
  } else if (!status && exists && element.getAttribute(key) === component) {
    element.removeAttribute(key);
    element.classList.remove('!overflow-hidden');
    element.style.paddingRight = '';
  }
};

/**
 * @param change {Object|Null}
 * @param model {*}
 */
export const wireChange = (change, model) => {
  if (! change) {
    return;
  }

  Livewire.find(change.id).call(change.method, model);
};
