/**
 * @param message
 * @return {void}
 */
export const warning = (message) => {
  console.warn(`[TallStackUI] ${message}`);
};

/**
 * @param message
 * @return {void}
 */
export const error = (message) => {
  console.error(`[TallStackUI] ${message}`);
};

/**
 * @param name {String}
 * @param params {Array|Object}
 */
export const dispatchEvent = (name, params = null) => {
  const event = `tallstackui:${name}`;

  window.dispatchEvent(new CustomEvent(event, {detail: params}));
};

/**
 * @param status {Boolean}
 * @param component {String|Null}
 */
export const overflow = (status, component = null) => {
  // The strategy here was adopted to prevent the loading component
  // from removing overflow when used in conjunction with other
  // components that handle overflow: modal, slide, dialogs.
  const element = document.querySelector('body');
  const data = 'data-overflow';
  const exists = [...element.attributes].some((attr) => attr.name === data);

  if (status && (!exists || element.getAttribute(data) === component)) {
    element.classList.add('!overflow-hidden');
    element.setAttribute(data, component);
  } else if (!status && exists && element.getAttribute(data) === component) {
    element.removeAttribute(data);
    element.classList.remove('!overflow-hidden');
  }
};
