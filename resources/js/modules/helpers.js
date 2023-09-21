/**
 * @param message
 * @returns {void}
 */
export const warning = (message) => {
    console.warn(`[TasteUi] ${message}`);
};

/**
 * @param message
 * @returns {void}
 */
export const error = (message) => {
    console.error(`[TasteUi] ${message}`);
};

/**
 * @param name {String}
 * @param params {Array|Object}
 */
export const dispatchEvent = (name, params = null) => {
    let event = `tasteui:${name}`;

    window.dispatchEvent(new CustomEvent(event, { detail: params }));
}