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
