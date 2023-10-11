import {autoUpdate, computePosition, flip, offset} from '@floating-ui/dom';

/**
 * @param search {String}
 * @param dimensional {Boolean}
 * @param selectable {Object}
 * @param options {Array}
 * @return {Array}
 */
export const options = (search, dimensional, selectable, options) => {
  search = search.toLowerCase();

  return search === '' ?
    options :
    options.filter((option) => {
      return dimensional ?
        option[selectable.label].toString().toLowerCase().indexOf(search) !== -1 :
        option.toString().toLowerCase().indexOf(search) !== -1;
    });
};

/**
 * @param request {Object|String}
 * @param search {String}
 * @param selected {Array}
 * @return {Object<url, method, params|data>}
 */
export const body = (request, search, selected) => {
  const simple = request.constructor === String;

  const url = simple ? request : request.url;
  const method = simple ? 'get' : request.method;
  const params = simple ? {} : request.params;

  const body = {url: url, method: method};

  switch (method) {
    case 'get':
      body.params = {...params};

      if (search !== '') body.params.search = search;

      if (selected.length > 0) {
        body.params.selected = JSON.stringify(selected);
      }
      break;
    case 'post':
      body.data = {...params};

      if (search !== '') body.data.search = search;

      if (selected.length > 0) {
        body.data.selected = selected;
      }
      break;
  }

  return {...body};
};

/**
 *
 * @param root {HTMLElement}
 * @param select {HTMLElement}
 * @returns {() => void}
 */
export const sync = (root, select) => {
  return autoUpdate(
      root,
      select,
      () => update(root, select),
  );
};

/**
 * @param root {HTMLElement}
 * @param select {HTMLElement}
 */
export const update = (root, select) => {
  computePosition(root, select, {
    placement: 'bottom',
    middleware: [
      offset(4),
      flip(),
    ],
  }).then(({x, y}) => {
    return Object.assign(select.style, {
      left: `${x}px`,
      top: `${y}px`,
    });
  });
};
