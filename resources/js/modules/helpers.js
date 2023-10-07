/**
 * @param message
 * @return {void}
 */
export const warning = (message) => {
  console.warn(`[TallStackUi] ${message}`);
};

/**
 * @param message
 * @return {void}
 */
export const error = (message) => {
  console.error(`[TallStackUi] ${message}`);
};

/**
 * @param name {String}
 * @param params {Array|Object}
 */
export const dispatchEvent = (name, params = null) => {
  const event = `tallstackui:${name}`;

  window.dispatchEvent(new CustomEvent(event, {detail: params}));
};
