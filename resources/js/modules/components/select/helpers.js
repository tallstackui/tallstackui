/**
 * @param search {String}
 * @param dimensional {Boolean}
 * @param selectable {Object}
 * @param options {Array}
 * @returns {Array}
 */
export const options = (search, dimensional, selectable, options) => {
    search = search.toLowerCase();

    return search === ''
        ? options
        : options.filter(option => {
            return dimensional
                ? option[selectable.label].toString().toLowerCase().indexOf(search) !== -1
                : option.toString().toLowerCase().indexOf(search) !== -1;
        });
};

/**
 * @param selecteds {Array}
 * @param option {Object}
 * @returns {boolean}
 */
export const selected = (selecteds, option) => {
    return selecteds.some(selected => {
        const keys   = Object.keys(selected);
        const values = Object.values(selected);

        return keys.every(key => {
            return selected[key] === option[key];
        }) && values.every(value => {
            return selected[value] === option[value];
        });
    });
}