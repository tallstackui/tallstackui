// encodeURIComponent leaves `!'()*` untouched, which RFC 3986 reserves. They
// are escaped here so the query string matches what the server side expects.
const encode = (value) =>
  encodeURIComponent(value).replace(
    /[!'()*]/g,
    (character) => `%${character.charCodeAt(0).toString(16).toUpperCase()}`
  );

const append = (prefix, value, output) => {
  if (value === undefined) {
    return;
  }

  if (value === null) {
    output.push(`${encode(prefix)}=`);

    return;
  }

  if (value instanceof Date) {
    output.push(`${encode(prefix)}=${encode(value.toISOString())}`);

    return;
  }

  if (typeof value === 'object') {
    // Bracket notation is what PHP expands back into nested arrays on the
    // other side, so `a[b]=c` and `a[0]=b` have to survive the encoding.
    // Arrays land here as well, keyed by their index.
    for (const [key, nested] of Object.entries(value)) {
      append(`${prefix}[${key}]`, nested, output);
    }

    return;
  }

  output.push(`${encode(prefix)}=${encode(value)}`);
};

const stringify = (params) => {
  const output = [];

  for (const [key, value] of Object.entries(params)) {
    append(key, value, output);
  }

  return output.join('&');
};

export const headers = () => {
  const token = document.head.querySelector('[name="csrf-token"]')?.getAttribute('content');

  return {
    'X-Requested-With': 'XMLHttpRequest',
    'Content-Type': 'application/json',
    Accept: 'application/json',
    'X-TallStack-Ui': true,
    ...(token ? { 'X-CSRF-TOKEN': token } : {}),
  };
};

export const body = (request, search, selected) => {
  const simple = request.constructor === String;

  let url = simple ? request : request.url;
  let method = simple ? 'get' : (request.method ?? 'get');
  const params = simple ? {} : (request.params ?? {});

  method = method.toLowerCase();

  const init = {
    method: method,
    headers: headers(),
  };

  if (method === 'get') {
    if (search !== '') {
      params.search = search;
    }

    if (selected.length > 0) {
      params.selected = JSON.stringify(selected);
    }

    url += '?' + stringify(params);
  } else {
    init.body = JSON.stringify({
      ...params,
      search: search,
      selected: selected,
    });
  }

  return { url, init };
};
