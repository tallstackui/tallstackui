import {autoUpdate, computePosition, flip, offset} from '@floating-ui/dom';

/**
 * @param request {Object|String}
 * @param search {String}
 * @param selected {Array}
 * @return {Object<url, method, params|data>}
 */
export const body = (request, search, selected) => {
  const simple = request.constructor === String;

  let url = simple ? request : request.url;
  let method = simple ? 'get' : request.method;
  const params = simple ? {} : request.params;

  method = method.toLowerCase();

  const init = {
    method: method,
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'TallStackUi': true,
    },
  };

  if (method === 'get') {
    const query = new URLSearchParams(params);

    if (search !== '') {
      query.set('search', search);
    }

    if (selected.length > 0) {
      query.set('selected', JSON.stringify(selected));
    }

    if (query.toString() !== '') {
      url += `?${query.toString()}`;
    }
  } else if (method === 'post') {
    init.body = JSON.stringify({
      ...params,
      search: search,
      selected: selected,
    });
  }

  return {url, init};
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
      position: 'absolute',
      left: `${x}px`,
      top: `${y}px`,
    });
  });
};
