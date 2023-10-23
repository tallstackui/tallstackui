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

/**
 * @param state {Boolean}
 */
export const overflow = (state) => {
  const elements = [...document.querySelectorAll('body, [main-container]')];

  state ?
      elements.forEach((el) => el.classList.add('!overflow-hidden')) :
      elements.forEach((el) => el.classList.remove('!overflow-hidden'));
};
