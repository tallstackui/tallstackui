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
 * @param mark {String|Null}
 */
export const overflow = (state, mark = null) => {
  const element = document.querySelector('body');

  // state ?
  //     element.classList.add('!overflow-hidden') :
  //     element.classList.remove('!overflow-hidden');

  if (state)
};
