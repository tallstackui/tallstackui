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
 * @param control {Boolean}
 */
export const overflow = (status, component = null, control = false) => {
  // When true, then we need to preserve the
  // overflow avoiding to hiding the scrollbar.
  if (control) return;

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
