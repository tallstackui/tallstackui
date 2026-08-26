import { lockable, overflow } from '../../../../../js/helpers';
import editor from '../editor';

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
  ...lockable(options.disabled, options.readonly),
  ...editor(options.editor),
  limit: options.limit,
  config: options.config,
  i18n: options.i18n || {},
  headers: options.headers || {},

  live: options.live,

  aborts: new Map(),
  queue: [],
  workers: 0,

  init() {
    // options.wire only carries an id when PHP rendered us inside a Livewire
    // component, which is exactly when the $wire magic is safe to touch.
    this.hydrate(options.existing);

    this.$watch('preview.open', (value) => overflow(value, 'upload-async', false));
    this.$watch('files', () => this.$nextTick(() => this.measure()));
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
      return;
    }

    const list = Array.isArray(existing) ? existing : [existing];

    this.files = list
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

  async intake(list) {
    const usable = this.multiple ? list : list.slice(0, 1);

    // Sequential on purpose: the editor handles one image at a time.
    for (const raw of usable) {
      await this.accept(raw);
    }
  },

  // Single mode replaces whatever is there, aborting an upload in flight.
  // Called only once the newcomer is settled, so a cancelled edit leaves
  // the previous file untouched.
  replace() {
    if (this.multiple || !this.files.length) {
      return;
    }

    this.aborts.get(this.files[0].uuid)?.abort();
    this.files = [];
  },

  describe(raw) {
    return {
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
  },

  async accept(raw) {
    if (!acceptable(raw, this.config.accept)) {
      return this.reject(this.describe(raw), 'mime', this.i18n.errors?.mime);
    }

    if (this.config.max_size && raw.size > this.config.max_size * 1024 * 1024) {
      return this.reject(
        this.describe(raw),
        'size',
        (this.i18n.errors?.size ?? '').replace(':max', this.config.max_size)
      );
    }

    const future = this.files.filter((item) => item.status !== 'rejected').length + 1;

    if (this.multiple && this.limit && future > this.limit) {
      return this.reject(
        this.describe(raw),
        'limit',
        (this.i18n.errors?.limit ?? '').replace(':max', this.limit)
      );
    }

    const edited = await this.edit(raw);

    if (!edited) {
      return;
    }

    const file = this.describe(edited);

    this.replace();

    this.files.push(file);

    this.emit('added', { file });

    if (!this.manual) {
      this.upload(file);
    }
  },

  reject(file, reason, message) {
    file.status = 'rejected';
    file.error = message;

    this.replace();

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

    wire.set(this.property, this.multiple ? payload : (payload[0] ?? null), this.live);
  },

  emit(name, detail) {
    this.$el.dispatchEvent(new CustomEvent(name, { detail, bubbles: false }));
  },
});
