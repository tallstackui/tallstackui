import { lockable, overflow } from '../../../../../js/helpers';

const readable = (bytes) => {
  if (!bytes) {
    return '0 B';
  }

  const units = ['B', 'KB', 'MB', 'GB', 'TB'];
  const exponent = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
  const value = bytes / Math.pow(1024, exponent);

  return `${value.toFixed(value >= 10 || exponent === 0 ? 0 : 1)} ${units[exponent]}`;
};

const acceptable = (file, accept) => {
  if (!accept) {
    return true;
  }

  const tokens = accept
    .split(',')
    .map((token) => token.trim())
    .filter(Boolean);

  return tokens.some((token) => {
    if (token.startsWith('.')) {
      return file.name.toLowerCase().endsWith(token.toLowerCase());
    }

    if (token.endsWith('/*')) {
      return file.type.startsWith(token.slice(0, -1));
    }

    return file.type === token;
  });
};

export default (options) => ({
  files: [],
  dragging: false,
  scrollable: false,
  preview: { open: false, src: null, name: null },

  id: options.id,
  property: options.property,
  name: options.name,
  route: options.route,
  method: options.method,
  multiple: options.multiple,
  manual: options.manual,
  sortable: options.sortable,
  ...lockable(options.disabled, options.readonly),
  limit: options.limit,
  config: options.config,
  i18n: options.i18n || {},
  headers: options.headers || {},

  live: options.live,

  aborts: new Map(),
  queue: [],
  workers: 0,
  last: null,

  sorting: null,
  press: null,
  handlers: null,

  init() {
    // options.wire only carries an id when PHP rendered us inside a Livewire
    // component, which is exactly when the $wire magic is safe to touch.
    this.files = this.hydrate(options.existing);
    this.last = this.stamp(options.existing);

    // Kept as stable references so release() can take them off the window.
    this.handlers = {
      move: (event) => this.track(event),
      up: () => this.release(),
    };

    this.$watch('preview.open', (value) => overflow(value, 'upload-async', false));
    this.$watch('files', () => this.$nextTick(() => this.measure()));

    this.$nextTick(() => this.observe());
  },

  // The root carries wire:ignore, so a property the server changes after the
  // first render (reset, load) only reaches the tiles through this watcher.
  observe() {
    const wire = this.bridge();

    if (!wire || !this.property || typeof wire.$watch !== 'function') {
      return;
    }

    wire.$watch(this.property, (value) => this.follow(value));
  },

  // Our own sync() echoes back through the watcher with the exact list we
  // sent, so only a value that differs from it rebuilds the tiles. Files
  // still pending, uploading or failed are not the server's to replace.
  follow(value) {
    const incoming = this.stamp(value);

    if (incoming === this.last) {
      return;
    }

    this.last = incoming;
    this.files = [
      ...this.hydrate(value),
      ...this.files.filter((file) => file.status !== 'success'),
    ];
  },

  stamp(value) {
    return JSON.stringify(value ?? (this.multiple ? [] : null));
  },

  // The bottom fade only earns its place once there is something scrolled
  // under it; over a fully visible last row it just washes the tiles out.
  measure() {
    const grid = this.$refs.grid;

    this.scrollable = !!grid && grid.scrollHeight > grid.clientHeight;
  },

  get pending() {
    return this.files.filter((file) => file.status === 'pending');
  },

  get uploaded() {
    return this.files.filter((file) => file.status === 'success');
  },

  get broken() {
    return this.files.some((file) => this.failed(file));
  },

  hydrate(existing) {
    if (!existing) {
      return [];
    }

    const list = Array.isArray(existing) ? existing : [existing];

    return list
      .filter((file) => file && file.path)
      .map((file) => ({
        ...file,
        uuid: file.id ?? crypto.randomUUID(),
        preview: file.url,
        status: 'success',
        progress: 100,
        error: null,
      }));
  },

  failed(file) {
    return file.status === 'error' || file.status === 'rejected';
  },

  image(file) {
    return (file.mime || '').startsWith('image/') && !!file.preview;
  },

  extension(file) {
    const position = (file.real_name || '').lastIndexOf('.');

    return position >= 0 ? file.real_name.slice(position + 1).toUpperCase() : '';
  },

  size(bytes) {
    return readable(bytes);
  },

  summary() {
    const total = this.files.reduce((accumulator, file) => accumulator + (file.size || 0), 0);
    const template = this.files.length === 1 ? this.i18n.ready?.single : this.i18n.ready?.multiple;

    return (template ?? '').replace(':count', this.files.length).replace(':size', readable(total));
  },

  sendable() {
    return this.files.some((file) => file.status === 'pending');
  },

  pick() {
    if (this.locked()) {
      return;
    }

    this.$refs.input.click();
  },

  expand(file) {
    if (!this.image(file)) {
      return;
    }

    this.preview = { open: true, src: file.preview, name: file.real_name };
  },

  collapse() {
    this.preview = { open: false, src: null, name: null };
  },

  // A mouse lifts the tile after a small move, so a plain click still opens
  // the lightbox. Touch waits for a long press, so a swipe still scrolls.
  hold(event, file) {
    if (!this.sortable || this.locked() || event.button !== 0 || event.target.closest('button')) {
      return;
    }

    if (event.pointerType === 'mouse') {
      event.preventDefault();
    }

    this.press = {
      id: event.pointerId,
      uuid: file.uuid,
      tile: event.currentTarget,
      x: event.clientX,
      y: event.clientY,
      timer: event.pointerType === 'mouse' ? null : setTimeout(() => this.lift(), 300),
    };

    window.addEventListener('pointermove', this.handlers.move);
    window.addEventListener('pointerup', this.handlers.up);
    window.addEventListener('pointercancel', this.handlers.up);
  },

  lift() {
    const press = this.press;

    clearTimeout(press.timer);
    press.timer = null;

    const rect = press.tile.getBoundingClientRect();
    const ghost = press.tile.cloneNode(true);

    // A frozen picture of the tile: left with its directives, Alpine would
    // try to evaluate them outside the loop that gives them a file.
    for (const node of [ghost, ...ghost.querySelectorAll('*')]) {
      for (const attribute of [...node.attributes]) {
        if (/^(x-|:|@|wire:|dusk$|role$)/.test(attribute.name)) {
          node.removeAttribute(attribute.name);
        }
      }
    }

    ghost.classList.add(...options.ghost.split(' ').filter(Boolean));
    ghost.setAttribute('aria-hidden', 'true');
    ghost.setAttribute('dusk', 'tallstackui_upload_async_ghost');

    Object.assign(ghost.style, {
      position: 'fixed',
      left: `${rect.left}px`,
      top: `${rect.top}px`,
      width: `${rect.width}px`,
      height: `${rect.height}px`,
      margin: '0',
      pointerEvents: 'none',
    });

    document.body.appendChild(ghost);

    press.ghost = ghost;
    press.from = this.files.findIndex((file) => file.uuid === press.uuid);

    this.sorting = press.uuid;
  },

  track(event) {
    const press = this.press;

    if (!press || event.pointerId !== press.id) {
      return;
    }

    const distance = Math.hypot(event.clientX - press.x, event.clientY - press.y);

    if (!this.sorting) {
      // Moving before the long press ends means the finger is scrolling.
      if (press.timer) {
        if (distance > 10) {
          this.release();
        }

        return;
      }

      if (distance < 5) {
        return;
      }

      this.lift();
    }

    press.cx = event.clientX;
    press.cy = event.clientY;
    press.ghost.style.transform = `translate(${event.clientX - press.x}px, ${event.clientY - press.y}px)`;

    this.hover(event.clientX, event.clientY);
    this.edge(event.clientY);
  },

  hover(x, y) {
    const grid = this.$refs.grid;
    const target = document.elementFromPoint(x, y)?.closest('[role=listitem]');

    if (!target || target.parentElement !== grid) {
      return;
    }

    const to = [...grid.querySelectorAll(':scope > [role=listitem]')].indexOf(target);
    const from = this.files.findIndex((file) => file.uuid === this.sorting);

    if (to < 0 || to === from) {
      return;
    }

    this.files.splice(to, 0, ...this.files.splice(from, 1));
  },

  // The grid stops growing at its max height, so near its top or bottom edge
  // it scrolls by itself; otherwise a tile below the fold could never reach
  // the first slot.
  edge(y) {
    const press = this.press;
    const grid = this.$refs.grid;
    const rect = grid.getBoundingClientRect();

    press.speed = 0;

    if (grid.scrollHeight > grid.clientHeight) {
      if (y < rect.top + 40) {
        press.speed = -8;
      } else if (y > rect.bottom - 40) {
        press.speed = 8;
      }
    }

    if (press.speed && !press.frame) {
      press.frame = requestAnimationFrame(() => this.scroll());
    }
  },

  scroll() {
    const press = this.press;

    if (!press) {
      return;
    }

    if (!press.speed) {
      press.frame = null;

      return;
    }

    this.$refs.grid.scrollTop += press.speed;

    this.hover(press.cx, press.cy);

    press.frame = requestAnimationFrame(() => this.scroll());
  },

  release() {
    const press = this.press;

    if (!press) {
      return;
    }

    clearTimeout(press.timer);
    cancelAnimationFrame(press.frame);

    window.removeEventListener('pointermove', this.handlers.move);
    window.removeEventListener('pointerup', this.handlers.up);
    window.removeEventListener('pointercancel', this.handlers.up);

    this.press = null;

    if (!this.sorting) {
      return;
    }

    press.ghost.remove();

    const to = this.files.findIndex((file) => file.uuid === this.sorting);

    this.sorting = null;

    this.swallow();

    if (to === press.from) {
      return;
    }

    this.emit('sorted', { file: this.files[to], from: press.from, to });

    this.sync();
  },

  // The pointer still clicks wherever the drag ends: on a tile that opens the
  // lightbox, on the grid it bubbles up and opens the file picker.
  swallow() {
    const block = (event) => {
      event.stopPropagation();
      event.preventDefault();
    };

    window.addEventListener('click', block, true);

    setTimeout(() => window.removeEventListener('click', block, true));
  },

  select(event) {
    if (this.locked()) {
      return;
    }

    this.intake([...event.target.files]);

    event.target.value = '';
  },

  drop(event) {
    if (this.locked()) {
      return;
    }

    this.dragging = false;

    this.intake([...(event.dataTransfer?.files ?? [])]);
  },

  intake(list) {
    const usable = this.multiple ? list : list.slice(0, 1);

    // Single mode replaces whatever is there, aborting an upload in flight.
    if (!this.multiple && usable.length && this.files.length) {
      this.aborts.get(this.files[0].uuid)?.abort();
      this.files = [];
    }

    for (const raw of usable) {
      this.accept(raw);
    }
  },

  accept(raw) {
    const file = {
      uuid: crypto.randomUUID(),
      raw,
      real_name: raw.name,
      size: raw.size,
      mime: raw.type || 'application/octet-stream',
      // preview is what the tile renders, url is what the backend gets back.
      // They differ on purpose: a private disk may hand us a URL the browser
      // cannot reach, and losing a working thumbnail is worse than showing a
      // local one.
      preview: URL.createObjectURL(raw),
      url: null,
      path: null,
      status: 'pending',
      progress: 0,
      error: null,
    };

    if (!acceptable(raw, this.config.accept)) {
      return this.reject(file, 'mime', this.i18n.errors?.mime);
    }

    if (this.config.max_size && raw.size > this.config.max_size * 1024 * 1024) {
      return this.reject(
        file,
        'size',
        (this.i18n.errors?.size ?? '').replace(':max', this.config.max_size)
      );
    }

    const future = this.files.filter((item) => item.status !== 'rejected').length + 1;

    if (this.multiple && this.limit && future > this.limit) {
      return this.reject(
        file,
        'limit',
        (this.i18n.errors?.limit ?? '').replace(':max', this.limit)
      );
    }

    this.files.push(file);

    this.emit('added', { file });

    if (!this.manual) {
      this.upload(file);
    }
  },

  reject(file, reason, message) {
    file.status = 'rejected';
    file.error = message;

    this.files.push(file);

    this.emit('rejected', { file, reason });
  },

  remove(file) {
    if (this.locked()) {
      return;
    }

    this.aborts.get(file.uuid)?.abort();
    this.aborts.delete(file.uuid);

    this.files = this.files.filter((item) => item.uuid !== file.uuid);

    this.emit('removed', { file });

    this.sync();
  },

  clear() {
    if (this.locked()) {
      return;
    }

    for (const file of this.files) {
      this.aborts.get(file.uuid)?.abort();
    }

    this.aborts.clear();
    this.files = [];

    this.sync();
  },

  send() {
    if (this.locked()) {
      return;
    }

    for (const file of this.pending) {
      this.upload(file);
    }
  },

  upload(file) {
    if (file.status === 'uploading' || file.status === 'success') {
      return;
    }

    file.status = 'uploading';
    file.progress = 0;
    file.error = null;

    const chunks = Math.max(1, Math.ceil(file.size / this.config.chunk_size));
    const abort = new AbortController();

    this.aborts.set(file.uuid, abort);

    const tracker = { file, chunks, done: 0, payload: null, abort };

    this.emit('start', { file });

    for (let index = 0; index < chunks; index++) {
      this.queue.push({ tracker, index });
    }

    this.spawn();
  },

  spawn() {
    const cap = Math.min(this.config.concurrency, this.queue.length);

    while (this.workers < cap && this.queue.length) {
      this.workers++;
      this.work();
    }
  },

  async work() {
    while (this.queue.length) {
      const job = this.queue.shift();

      try {
        await this.chunk(job.tracker, job.index);
      } catch {
        // chunk() already marked the file; keep draining the queue.
      }
    }

    this.workers--;
  },

  async chunk(tracker, index) {
    const { file, chunks } = tracker;

    if (file.status !== 'uploading') {
      return;
    }

    const start = index * this.config.chunk_size;
    const end = Math.min(file.size, start + this.config.chunk_size);

    const body = new FormData();

    body.append('chunk', file.raw.slice(start, end), file.real_name);
    body.append('chunk_index', String(index));
    body.append('total_chunks', String(chunks));
    body.append('chunk_size', String(end - start));
    body.append('total_size', String(file.size));
    body.append('uuid', file.uuid);
    body.append('real_name', file.real_name);
    body.append('mime', file.mime);

    const headers = {
      Accept: 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN':
        document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '',
      ...this.headers,
    };

    let attempt = 0;
    let response = null;

    // Only the request itself belongs in the retry loop. Anything that runs
    // after a successful response must stay out of it, otherwise a throw in
    // the bookkeeping would be mistaken for a network failure and the very
    // same chunk would be uploaded twice.
    for (;;) {
      try {
        response = await fetch(this.route, {
          method: this.method,
          headers,
          credentials: 'same-origin',
          signal: tracker.abort.signal,
          body,
        });
      } catch (error) {
        if (error.name === 'AbortError') {
          return;
        }

        if (attempt >= this.config.retries) {
          this.fail(file, this.i18n.errors?.network, 0);

          return;
        }

        await this.backoff(attempt++);

        continue;
      }

      if (response.ok || response.status === 204) {
        break;
      }

      if (response.status >= 500 || [408, 429].includes(response.status)) {
        if (attempt >= this.config.retries) {
          this.fail(file, this.i18n.errors?.server, response.status);

          return;
        }

        await this.backoff(attempt++);

        continue;
      }

      const parsed = await response.json().catch(() => null);
      const errors = parsed?.errors ?? {};
      const message =
        errors[Object.keys(errors)[0]]?.[0] ?? parsed?.message ?? this.i18n.errors?.generic;

      this.fail(file, message, response.status);

      return;
    }

    tracker.done++;
    file.progress = Math.round((tracker.done / chunks) * 100);

    // The server elects its finalizer by an atomic rename, so the final
    // payload may come back on any chunk, not on the highest index.
    if (response.status !== 204) {
      tracker.payload = await response.json().catch(() => null);
    }

    this.emit('progress', { file, progress: file.progress });

    if (tracker.done === chunks) {
      this.settle(tracker);
    }
  },

  backoff(attempt) {
    return new Promise((resolve) =>
      setTimeout(resolve, this.config.retry_delay * Math.pow(2, attempt))
    );
  },

  settle(tracker) {
    const { file, payload } = tracker;

    if (!payload) {
      this.fail(file, this.i18n.errors?.server, 0);

      return;
    }

    file.path = payload.path;
    file.url = payload.url ?? null;
    file.mime = payload.mime || file.mime;
    file.real_name = payload.real_name || file.real_name;
    file.size = payload.size ?? file.size;
    file.progress = 100;
    file.status = 'success';

    this.aborts.delete(file.uuid);

    this.emit('success', { file, response: payload });

    this.sync();

    this.complete();
  },

  fail(file, message, status) {
    file.status = 'error';
    file.error = message;

    this.aborts.delete(file.uuid);

    this.emit('error', { file, error: message, status });

    this.complete();
  },

  complete() {
    if (this.files.some((file) => file.status === 'uploading' || file.status === 'pending')) {
      return;
    }

    this.emit('complete', { files: this.uploaded });
  },

  // Resolved from the DOM on demand. Doing it in init() is too early, the
  // component is not registered yet; and the id PHP knows about is not the
  // one the client ends up using, so the wire:id attribute is the only
  // reliable bridge. options.wire is just the "are we inside Livewire" flag.
  bridge() {
    if (!options.wire || !window.Livewire) {
      return null;
    }

    const host = this.$el.closest('[wire\\:id]');

    if (!host) {
      return null;
    }

    // Livewire 3 hands back a component that carries $wire; Livewire 4 hands
    // back the $wire proxy itself.
    const found = window.Livewire.find(host.getAttribute('wire:id'));
    const wire = typeof found?.$wire?.set === 'function' ? found.$wire : found;

    return typeof wire?.set === 'function' ? wire : null;
  },

  sync() {
    const wire = this.bridge();

    if (!wire || !this.property) {
      return;
    }

    const payload = this.uploaded.map((file) => ({
      id: file.uuid,
      path: file.path,
      real_name: file.real_name,
      size: file.size,
      mime: file.mime,
      url: file.url,
    }));

    const value = this.multiple ? payload : (payload[0] ?? null);

    this.last = this.stamp(value);

    wire.set(this.property, value, this.live);
  },

  emit(name, detail) {
    this.$root.dispatchEvent(new CustomEvent(name, { detail, bubbles: false }));
  },
});
