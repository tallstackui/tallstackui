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

/**
 * @param request {Object|String}
 * @param search {String}
 * @returns {Object<url, method, params|data>}
 */
export const body = (request, search) => {
    const simple = request.constructor === String;

    const url = simple ? request : request.url;
    const method = simple ? 'get' : request.method;
    const params = simple ? {} : request.params;

    const body = {url: url, method: method}

    switch (method) {
        case 'get':
            body.params = {...params}

            if (search !== '') body.params.search = search;
            break;
        case 'post':
            body.data = {...params}

            if (search !== '') body.data.search = search;
            break;
    }

    return {...body};
}
