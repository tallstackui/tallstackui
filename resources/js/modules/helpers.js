/**
 * @param message
 * @return {void}
 */
export const warning = (message) => {
  console.warn(`[TasteUi] ${message}`);
};

/**
 * @param message
 * @return {void}
 */
export const error = (message) => {
  console.error(`[TasteUi] ${message}`);
};

/**
 * @param name {String}
 * @param params {Array|Object}
 */
export const dispatchEvent = (name, params = null) => {
  const event = `tasteui:${name}`;

  window.dispatchEvent(new CustomEvent(event, {detail: params}));
};
