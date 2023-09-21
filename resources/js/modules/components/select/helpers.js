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
 * @return {Object<url, method, params|data>}
 */
export const body = (request, search) => {
  const simple = request.constructor === String;

  const url = simple ? request : request.url;
  const method = simple ? 'get' : request.method;
  const params = simple ? {} : request.params;

  const body = {url: url, method: method};

  switch (method) {
    case 'get':
      body.params = {...params};

      if (search !== '') body.params.search = search;
      break;
    case 'post':
      body.data = {...params};

      if (search !== '') body.data.search = search;
      break;
  }

  return {...body};
};
