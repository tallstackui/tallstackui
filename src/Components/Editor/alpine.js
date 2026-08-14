import { overflow } from '../../../js/helpers';
import autoformat from './autoformat';
import parse from './parse';
import serialize from './serialize';

// Fixed on purpose: the value is written into the HTML that gets saved.
const INDENT_STEP = 2;
const INDENT_LIMIT = 8;

// Chrome emits <b>/<i> and Safari sprinkles <font>: folded back into one shape
// so the stored HTML does not depend on who typed it.
const normalize = (root) => {
  for (const node of root.querySelectorAll('b, i, font')) {
    const tag = node.tagName.toLowerCase();

    if (tag === 'font') {
      node.replaceWith(...node.childNodes);

      continue;
    }

    const replacement = document.createElement(tag === 'b' ? 'strong' : 'em');

    replacement.append(...node.childNodes);
    node.replaceWith(replacement);
  }
};

// innerText is no measure for the counters: it writes two breaks between
// paragraphs plus one for the filler <br> engines keep inside an empty block,
// and while the component is still hidden behind x-cloak it degrades to
// textContent, which holds no breaks at all.
const BLOCKS = [
  'P',
  'DIV',
  'H1',
  'H2',
  'H3',
  'H4',
  'H5',
  'LI',
  'UL',
  'OL',
  'PRE',
  'BLOCKQUOTE',
  'HR',
];

const rows = (element) => {
  let lines = 0;
  let content = false;

  for (const child of element.childNodes) {
    if (child.nodeType === 3) {
      content = content || child.textContent.trim() !== '';

      continue;
    }

    if (child.nodeType !== 1) {
      continue;
    }

    if (child.tagName === 'BR') {
      lines++;
      content = false;

      continue;
    }

    if (!BLOCKS.includes(child.tagName)) {
      content = true;

      continue;
    }

    if (content) {
      lines++;
      content = false;
    }

    lines +=
      child.tagName === 'PRE'
        ? child.textContent.replace(/\n$/, '').split('\n').length
        : Math.max(rows(child), 1);
  }

  return content ? lines + 1 : lines;
};

const textual = (element) => {
  let output = '';

  for (const child of element.childNodes) {
    if (child.nodeType === 3) {
      output += child.textContent;

      continue;
    }

    if (child.nodeType !== 1) {
      continue;
    }

    if (child.tagName === 'BR') {
      output += '\n';

      continue;
    }

    output += BLOCKS.includes(child.tagName) ? `\n${textual(child)}\n` : textual(child);
  }

  return output;
};

// A contenteditable is never truly empty: engines keep a filler node to hold
// the caret, and without one the caret collapses to a minimum height beside
// the placeholder.
const FILLERS = ['', '<br>', '<p><br></p>', '<div><br></div>'];

const seeded = (html) => (html === '' ? '<br>' : html);

// Attribute filtering keeps href and src, and the scheme is what carries the
// script. Whitespace survives entity decoding and hides the scheme from a
// plain prefix check while the browser ignores it, so it is stripped first.
const compacted = (value) => String(value).replace(/\s/g, '').toLowerCase();

const dangerous = (value) => /^(javascript|vbscript|data):/.test(compacted(value));

export default (options) => ({
  // Holds Markdown or HTML, whichever the component was told to store.
  content: options.entangle ?? options.value ?? '',
  empty: true,

  blockType: 'p',
  listed: false,
  // Spelled out because Alpine keeps aria-pressed when the value is falsy, and
  // an empty object would paint aria-pressed="undefined".
  activeFormats: {
    bold: false,
    italic: false,
    underline: false,
    strikethrough: false,
    orderedList: false,
    unorderedList: false,
    justifyLeft: false,
    justifyCenter: false,
    justifyRight: false,
    justifyFull: false,
    code: false,
    codeBlock: false,
    blockquote: false,
    link: false,
  },

  fullscreen: false,

  dialog: null,
  linkText: '',
  linkUrl: '',
  imageUrl: '',
  imageAlt: '',
  imageError: '',
  imageSource: 'url',
  uploading: false,
  uploadProgress: 0,

  words: 0,
  lines: 0,

  range: null,
  syncTimeout: null,
  formatsTimeout: null,
  config: options,

  init() {
    this.$refs.editable.innerHTML = seeded(this.sanitize(this.incoming(this.content ?? '')));

    this.refreshEmpty();
    this.recount();
    this.rove();

    this.selectionListener = () => this.scheduleFormats();
    this.escapeListener = (event) => this.escape(event);

    document.addEventListener('selectionchange', this.selectionListener);
    window.addEventListener('keydown', this.escapeListener);

    // The property can also be written from the outside, either by the parent
    // Livewire component or by another editor bound to the same property.
    this.$watch('content', (value) => {
      // While the caret is here the DOM is the source of truth: a live echo
      // arrives a round trip late and would scramble what came after it.
      if (this.focused()) {
        return;
      }

      if ((value ?? '') === this.outgoing()) {
        return;
      }

      this.$refs.editable.innerHTML = seeded(this.sanitize(this.incoming(value ?? '')));

      this.refreshEmpty();
      this.recount();
    });
  },

  // The two ends of the stored format. Both are the identity in HTML mode.
  incoming(value) {
    return this.config.markdown ? parse(value) : value;
  },

  outgoing() {
    // The seeded filler is presentation, not content: it never reaches the
    // bound property.
    if (FILLERS.includes(this.$refs.editable.innerHTML.trim())) {
      return '';
    }

    return this.config.markdown ? serialize(this.$refs.editable) : this.$refs.editable.innerHTML;
  },

  destroy() {
    document.removeEventListener('selectionchange', this.selectionListener);
    window.removeEventListener('keydown', this.escapeListener);

    clearTimeout(this.syncTimeout);
    clearTimeout(this.formatsTimeout);

    if (this.fullscreen) {
      overflow(false, 'editor');
    }
  },

  dispatch(name, detail = {}) {
    this.$root.dispatchEvent(
      new CustomEvent(name, { detail: { id: this.config.id, ...detail }, bubbles: true })
    );
  },

  scheduleSync() {
    // Answered outside the debounce, or the placeholder sits over the first
    // keystrokes.
    this.refreshEmpty();

    clearTimeout(this.syncTimeout);

    this.syncTimeout = setTimeout(() => this.syncFromDom(), 250);
  },

  syncFromDom() {
    normalize(this.$refs.editable);

    const html = this.$refs.editable.innerHTML;
    const output = this.outgoing();

    this.refreshEmpty();

    if (output === this.content) {
      return;
    }

    this.content = output;

    this.recount();

    const detail = { html, words: this.words, lines: this.lines };

    if (this.config.markdown) {
      detail.markdown = output;
    }

    this.dispatch('editor:change', detail);
  },

  focused() {
    const active = document.activeElement;

    return active === this.$refs.editable || this.$refs.editable.contains(active);
  },

  refreshEmpty() {
    this.empty = FILLERS.includes(this.$refs.editable.innerHTML.trim());
  },

  recount() {
    const text = textual(this.$refs.editable).trim();

    this.words = text === '' ? 0 : text.split(/\s+/).filter(Boolean).length;
    this.lines = text === '' ? 0 : Math.max(rows(this.$refs.editable), 1);
  },

  formatCount(count, template) {
    const [singular, plural] = template.split('|');

    return (count === 1 ? singular : (plural ?? singular)).replace(':count', count);
  },

  scheduleFormats() {
    clearTimeout(this.formatsTimeout);

    this.formatsTimeout = setTimeout(() => this.syncFormats(), 50);
  },

  syncFormats() {
    const selection = window.getSelection();

    if (!selection?.anchorNode || !this.$refs.editable.contains(selection.anchorNode)) {
      return;
    }

    this.activeFormats = {
      bold: document.queryCommandState('bold'),
      italic: document.queryCommandState('italic'),
      underline: document.queryCommandState('underline'),
      strikethrough: document.queryCommandState('strikeThrough'),
      orderedList: document.queryCommandState('insertOrderedList'),
      unorderedList: document.queryCommandState('insertUnorderedList'),
      justifyLeft: document.queryCommandState('justifyLeft'),
      justifyCenter: document.queryCommandState('justifyCenter'),
      justifyRight: document.queryCommandState('justifyRight'),
      justifyFull: document.queryCommandState('justifyFull'),
      code: this.ancestor('CODE') !== null && this.ancestor('PRE') === null,
      codeBlock: this.ancestor('PRE') !== null,
      blockquote: this.ancestor('BLOCKQUOTE') !== null,
      link: this.ancestor('A') !== null,
    };

    this.listed = this.ancestor('LI') !== null;
    this.blockType = (document.queryCommandValue('formatBlock') || 'p')
      .toLowerCase()
      .replace(/[<>]/g, '');
  },

  ancestor(tag) {
    const selection = window.getSelection();

    if (!selection?.anchorNode) {
      return null;
    }

    let node = selection.anchorNode;

    while (node && node !== this.$refs.editable) {
      if (node.nodeType === 1 && node.tagName === tag) {
        return node;
      }

      node = node.parentNode;
    }

    return null;
  },

  capture() {
    const selection = window.getSelection();

    if (!selection?.rangeCount) {
      return;
    }

    const range = selection.getRangeAt(0);

    if (!this.$refs.editable.contains(range.commonAncestorContainer)) {
      return;
    }

    this.range = range.cloneRange();
  },

  restore() {
    this.$refs.editable.focus();

    if (!this.range) {
      const range = document.createRange();

      range.selectNodeContents(this.$refs.editable);
      range.collapse(false);

      this.range = range;
    }

    const selection = window.getSelection();

    selection.removeAllRanges();
    selection.addRange(this.range);
  },

  exec(command, value = null) {
    this.$refs.editable.focus();

    try {
      // With styleWithCSS on, indent writes an inline margin instead of nesting
      // a list. The flag is document wide, so it is pinned on every command.
      document.execCommand('styleWithCSS', false, false);
      document.execCommand(command, false, value);
    } catch {
      // Not every engine implements every command, and the ones missing
      // are the ones this component can live without.
    }

    this.scheduleSync();
    this.scheduleFormats();
  },

  // The direct child of the editable holding the caret. Anything deeper is
  // inline and carries no indentation.
  block() {
    const selection = window.getSelection();

    if (!selection?.anchorNode) {
      return null;
    }

    let node =
      selection.anchorNode.nodeType === 1
        ? selection.anchorNode
        : selection.anchorNode.parentElement;

    while (node && node !== this.$refs.editable) {
      if (node.parentNode === this.$refs.editable) {
        return node;
      }

      node = node.parentElement;
    }

    return null;
  },

  // Inside a list the browser nests. Anywhere else the indent is a margin on
  // the block: the native command reaches for a <blockquote>, which is a quote
  // and gets stripped on the way back in. The margin is written straight into
  // the DOM, so it stays outside the undo stack — re-serialising the block to
  // get it in there would drop the caret.
  shiftIndent(direction) {
    if (this.listed) {
      this.exec(direction > 0 ? 'indent' : 'outdent');

      return;
    }

    // Markdown carries no block indent, so it would be dropped on the next sync.
    if (this.config.markdown) {
      return;
    }

    this.$refs.editable.focus();

    let block = this.block();

    // A bare text node has no block to carry the margin.
    if (!block) {
      document.execCommand('styleWithCSS', false, false);
      document.execCommand('formatBlock', false, '<p>');

      block = this.block();
    }

    if (!block) {
      return;
    }

    const current = parseFloat(block.style.marginLeft) || 0;
    const level = Math.min(Math.max(current / INDENT_STEP + direction, 0), INDENT_LIMIT);
    const next = level * INDENT_STEP;

    if (next === current) {
      return;
    }

    if (next === 0) {
      block.style.removeProperty('margin-left');

      if (!block.getAttribute('style')) {
        block.removeAttribute('style');
      }
    } else {
      block.style.marginLeft = `${next}rem`;
    }

    this.scheduleSync();
    this.scheduleFormats();
  },

  toggleBlock(tag) {
    if (this.ancestor('PRE')) {
      return;
    }

    if (this.listed) {
      this.formatItem(tag);

      return;
    }

    this.exec('formatBlock', `<${tag}>`);
    this.blockType = tag;
  },

  // formatBlock only behaves inside a list when there is already a heading to
  // rewrite. Handed a bare item it wraps the whole list in the heading, and
  // splits the list when the item is not the first one.
  formatItem(tag) {
    const item = this.ancestor('LI');

    if (!item) {
      return;
    }

    // execCommand is a no-op on an editable that does not hold focus, and the
    // toolbar button took it.
    this.$refs.editable.focus();

    const heading = item.querySelector('h1, h2, h3, h4, h5, h6');
    const selection = window.getSelection();
    const range = document.createRange();

    if (heading) {
      range.selectNodeContents(heading);
      selection.removeAllRanges();
      selection.addRange(range);

      this.exec('formatBlock', `<${tag}>`);
      this.blockType = tag;

      return;
    }

    if (tag === 'p') {
      return;
    }

    range.selectNodeContents(item);
    selection.removeAllRanges();
    selection.addRange(range);

    this.replaceSelection(`<${tag}>${item.innerHTML}</${tag}>`);

    this.blockType = tag;
  },

  toggleBlockquote() {
    this.$refs.editable.focus();

    this.exec('formatBlock', this.ancestor('BLOCKQUOTE') ? '<p>' : '<blockquote>');
  },

  // The trailing paragraph gives the caret somewhere to land after the rule.
  insertRule() {
    this.$refs.editable.focus();

    this.replaceSelection('<hr><p><br></p>');
  },

  blockLabel() {
    const known = this.config.i18n.style[this.blockType];

    if (known) {
      return known;
    }

    // A pasted heading names itself; the div execCommand leaves behind when it
    // aligns, and anything else unnamed, answers as the plain one.
    return /^h[1-6]$/.test(this.blockType)
      ? this.blockType.toUpperCase()
      : this.config.i18n.style.paragraph;
  },

  // Structural changes go through insertHTML: a node inserted by hand is
  // invisible to the browser's undo stack.
  replaceSelection(html) {
    document.execCommand('insertHTML', false, html);

    this.scheduleSync();
    this.scheduleFormats();
  },

  replaceNode(node, text, tag = null) {
    const selection = window.getSelection();
    const range = document.createRange();

    range.selectNode(node);
    selection.removeAllRanges();
    selection.addRange(range);

    const html = this.escapeHtml(text);

    this.replaceSelection(tag ? `<${tag}>${html}</${tag}>` : html);
  },

  toggleCode() {
    this.$refs.editable.focus();

    const code = this.ancestor('CODE');

    if (code && !this.ancestor('PRE')) {
      this.replaceNode(code, code.textContent);

      return;
    }

    const selection = window.getSelection();

    if (!selection?.rangeCount || selection.getRangeAt(0).collapsed) {
      return;
    }

    this.replaceSelection(`<code>${this.escapeHtml(selection.getRangeAt(0).toString())}</code>`);
  },

  toggleCodeBlock() {
    this.$refs.editable.focus();

    const pre = this.ancestor('PRE');

    if (pre) {
      this.replaceNode(pre, pre.textContent, 'p');

      return;
    }

    const selection = window.getSelection();

    if (!selection?.rangeCount) {
      return;
    }

    this.replaceSelection(
      `<pre><code>${this.escapeHtml(selection.getRangeAt(0).toString() || '\n')}</code></pre>`
    );
  },

  clearFormat() {
    this.$refs.editable.focus();

    document.execCommand('removeFormat', false, null);

    const selection = window.getSelection();

    if (!selection?.rangeCount || selection.getRangeAt(0).collapsed) {
      this.scheduleSync();
      this.scheduleFormats();

      return;
    }

    // removeFormat leaves inline styles alone, so the spans are swept by hand.
    const container = document.createElement('div');

    container.appendChild(selection.getRangeAt(0).cloneContents());

    for (const span of container.querySelectorAll('span[style]')) {
      span.replaceWith(...span.childNodes);
    }

    this.replaceSelection(container.innerHTML);
  },

  handleInput(event) {
    if (this.config.markdown && event.inputType === 'insertText' && event.data) {
      autoformat(this, event.data);
    }

    this.scheduleSync();
  },

  handlePaste(event) {
    event.preventDefault();

    const html = event.clipboardData.getData('text/html');
    const text = event.clipboardData.getData('text/plain');

    // A code editor ships its syntax highlighting as a text/html flavour, so
    // the flavour being there does not mean the clipboard carries structure.
    const rich = html !== '' && (!this.config.markdown || this.structured(html));

    if (rich) {
      document.execCommand('insertHTML', false, this.sanitize(html));
    } else if (this.config.markdown) {
      // Text pasted into a Markdown editor is read as Markdown: anything else
      // drops the source on the floor.
      document.execCommand('insertHTML', false, this.sanitize(parse(text)));
    } else {
      document.execCommand('insertText', false, text);
    }

    this.scheduleSync();
  },

  // Everything the serializer can name. A payload holding none of it says
  // nothing the plain text does not already say.
  structured(html) {
    const template = document.createElement('template');

    template.innerHTML = html;

    return (
      template.content.querySelector(
        'h1, h2, h3, h4, h5, h6, ul, ol, li, blockquote, pre, hr, table, a, img, strong, b, em, i, s, del, code'
      ) !== null
    );
  },

  sanitize(raw) {
    // A <template> parses without running: no script, no onerror, no handler.
    const template = document.createElement('template');

    template.innerHTML = raw;

    const {
      allowed_tags: tags,
      allowed_attributes: attributes,
      allowed_styles: styles,
    } = this.config.sanitization;

    const walk = (node) => {
      for (const child of [...node.childNodes]) {
        walk(child);
      }

      if (node.nodeType === 8) {
        node.remove();

        return;
      }

      if (node.nodeType !== 1) {
        return;
      }

      const tag = node.tagName.toLowerCase();

      if (!tags.includes(tag)) {
        node.replaceWith(...node.childNodes);

        return;
      }

      const allowed = attributes[tag] ?? [];

      for (const attribute of [...node.attributes]) {
        if (!allowed.includes(attribute.name)) {
          node.removeAttribute(attribute.name);
        }
      }

      for (const attribute of ['href', 'src']) {
        const value = node.getAttribute(attribute);

        if (value === null || !dangerous(value)) {
          continue;
        }

        if (tag === 'img' && attribute === 'src' && compacted(value).startsWith('data:image/')) {
          continue;
        }

        node.removeAttribute(attribute);
      }

      for (const property of [...node.style]) {
        if (!styles.includes(property)) {
          node.style.removeProperty(property);
        }
      }

      if (node.hasAttribute('style') && node.getAttribute('style') === '') {
        node.removeAttribute('style');
      }
    };

    walk(template.content);

    return template.innerHTML;
  },

  openLink() {
    this.capture();

    this.linkText = window.getSelection()?.toString() ?? '';
    this.linkUrl = this.ancestor('A')?.getAttribute('href') ?? '';

    this.open('link');
  },

  openImage() {
    this.capture();

    this.imageUrl = '';
    this.imageAlt = '';
    this.imageError = '';
    this.imageSource = 'url';

    this.open('image');
  },

  // Both dialogs ride on <x-modal>, which owns the scroll lock, the
  // overlay registry and Escape. No field is focused on open.
  open(name) {
    this.dialog = name;

    this.modal(name, 'open');
  },

  modal(name, action) {
    window.dispatchEvent(new CustomEvent(`modal:${this.config.dialogs[name]}-${action}`));
  },

  closeDialog() {
    if (!this.dialog) {
      return;
    }

    const name = this.dialog;

    this.dialog = null;

    this.modal(name, 'close');

    this.restore();
  },

  // Fired when the modal closes on its own terms. Anything we closed ourselves
  // has already dropped the state.
  dialogClosed() {
    if (!this.dialog) {
      return;
    }

    this.dialog = null;

    this.restore();
  },

  insertLink() {
    if (!this.linkUrl || !this.validLinkUrl) {
      return;
    }

    this.restore();

    const text = this.escapeHtml(this.linkText || this.linkUrl);
    const href = this.escapeHtml(this.linkUrl);

    document.execCommand('insertHTML', false, `<a href="${href}">${text}</a>`);

    this.dialog = null;

    this.modal('link', 'close');

    this.dispatch('editor:link-inserted', { href: this.linkUrl, text: this.linkText });

    this.linkText = '';
    this.linkUrl = '';
    this.range = null;

    this.syncFromDom();
  },

  insertImage() {
    if (!this.imageUrl || !this.validImageUrl) {
      return;
    }

    this.restore();

    const src = this.escapeHtml(this.imageUrl);
    const alt = this.escapeHtml(this.imageAlt ?? '');

    document.execCommand('insertHTML', false, `<img src="${src}" alt="${alt}" />`);

    this.dialog = null;

    this.modal('image', 'close');

    this.dispatch('editor:image-inserted', {
      src: this.imageUrl,
      alt: this.imageAlt,
      source: this.imageSource,
    });

    this.imageUrl = '';
    this.imageAlt = '';
    this.imageError = '';
    this.range = null;

    this.syncFromDom();
  },

  get validImageUrl() {
    return /^(https?:\/\/|data:image\/|\/)/.test(this.imageUrl);
  },

  get validLinkUrl() {
    return !dangerous(this.linkUrl);
  },

  escapeHtml(value) {
    return String(value)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  },

  uploadImage(file) {
    if (!file) {
      return;
    }

    const { mimes, max_size: max, property, method } = this.config.upload;

    if (!mimes.includes(file.type)) {
      this.imageError = this.config.i18n.image.errors.mime;

      return;
    }

    if (file.size > max * 1024) {
      this.imageError = this.config.i18n.image.errors.size.replace(':max', max);

      return;
    }

    this.uploading = true;
    this.uploadProgress = 0;
    this.imageError = '';

    this.$wire.upload(
      property,
      file,
      async () => {
        try {
          this.imageUrl = await this.$wire.call(method);
          this.imageSource = 'upload';
        } catch (error) {
          this.imageError = error?.message ?? this.config.i18n.image.errors.failed;
        }

        this.uploading = false;
        this.uploadProgress = 0;
      },
      () => {
        this.uploading = false;
        this.imageError = this.config.i18n.image.errors.failed;
      },
      (event) => (this.uploadProgress = Math.round(event.detail.progress))
    );
  },

  toggleFullscreen() {
    this.fullscreen = !this.fullscreen;

    overflow(this.fullscreen, 'editor');

    this.dispatch('editor:fullscreen-toggled', { on: this.fullscreen });
  },

  escape(event) {
    if (event.key !== 'Escape') {
      return;
    }

    // The dialogs answer Escape on their own.
    if (!this.dialog && this.fullscreen) {
      this.toggleFullscreen();
    }
  },

  handleKeydown(event) {
    const modifier = event.metaKey || event.ctrlKey;

    if (event.key === 'Tab' && this.listed) {
      event.preventDefault();
      this.shiftIndent(event.shiftKey ? -1 : 1);

      return;
    }

    if (event.key === 'Enter' && this.ancestor('PRE')) {
      event.preventDefault();

      if (modifier) {
        this.exitCodeBlock();

        return;
      }

      document.execCommand('insertText', false, '\n');
      this.scheduleSync();

      return;
    }

    if (event.key === 'Enter' && this.config.markdown && autoformat(this, 'Enter')) {
      event.preventDefault();

      return;
    }

    if (!modifier) {
      return;
    }

    const shortcuts = {
      b: () => this.exec('bold'),
      i: () => this.exec('italic'),
      k: () => this.openLink(),
      '\\': () => this.clearFormat(),
      y: () => this.exec('redo'),
    };

    // Markdown has no underline, so the shortcut would write something the
    // next sync throws away.
    if (!this.config.markdown) {
      shortcuts.u = () => this.exec('underline');
    }

    if (event.key === 'z') {
      event.preventDefault();
      this.exec(event.shiftKey ? 'redo' : 'undo');

      return;
    }

    if (event.key >= '0' && event.key <= '3') {
      event.preventDefault();
      this.toggleBlock(event.key === '0' ? 'p' : `h${event.key}`);

      return;
    }

    const shortcut = shortcuts[event.key.toLowerCase()];

    if (!shortcut) {
      return;
    }

    event.preventDefault();
    shortcut();
  },

  exitCodeBlock() {
    const pre = this.ancestor('PRE');

    if (!pre) {
      return;
    }

    const paragraph = document.createElement('p');

    paragraph.innerHTML = '<br>';
    pre.parentNode.insertBefore(paragraph, pre.nextSibling);

    const range = document.createRange();

    range.selectNodeContents(paragraph);
    range.collapse(true);

    const selection = window.getSelection();

    selection.removeAllRanges();
    selection.addRange(range);

    this.scheduleSync();
  },

  controls() {
    if (!this.$refs.toolbar) {
      return [];
    }

    // The dropdown panels live inside the toolbar, so their items are kept out.
    return [...this.$refs.toolbar.querySelectorAll('button')].filter(
      (button) => !button.closest('[role="menu"]')
    );
  },

  rove() {
    const controls = this.controls();

    controls.forEach((control, index) =>
      control.setAttribute('tabindex', index === 0 ? '0' : '-1')
    );
  },

  roving(direction) {
    const controls = this.controls();
    const current = controls.indexOf(document.activeElement);

    if (current === -1) {
      return;
    }

    const next = (current + direction + controls.length) % controls.length;

    controls[current].setAttribute('tabindex', '-1');
    controls[next].setAttribute('tabindex', '0');
    controls[next].focus();
  },
});
