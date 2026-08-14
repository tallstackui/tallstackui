// Fire on the space that completes the marker, once, at the start of a block.
const BLOCK = [
  { pattern: /^(#{1,3})$/, apply: (editor, match) => editor.toggleBlock(`h${match[1].length}`) },
  { pattern: /^[-*+]$/, apply: (editor) => editor.exec('insertUnorderedList') },
  { pattern: /^\d+[.)]$/, apply: (editor) => editor.exec('insertOrderedList') },
  { pattern: /^>$/, apply: (editor) => editor.toggleBlockquote() },
];

// Fire on the character that closes the pair. Group 1 is what gets replaced,
// group 2 the text it decorates.
const INLINE = [
  { trigger: '*', pattern: /(\*\*(?=\S)([\s\S]*?\S)\*\*)$/, tag: 'strong' },
  { trigger: '*', pattern: /(?:^|[^*])(\*(?=[^*\s])([^*]*?\S)\*)$/, tag: 'em' },
  { trigger: '`', pattern: /(`(?=\S)([^`]*?\S)`)$/, tag: 'code' },
  { trigger: '~', pattern: /(~~(?=\S)([\s\S]*?\S)~~)$/, tag: 's' },
];

// Fire on Enter, on a line holding nothing else.
const ENTER = [
  { pattern: /^-{3,}$/, apply: (editor) => editor.insertRule() },
  { pattern: /^(`{3,}|~{3,})$/, apply: (editor) => editor.toggleCodeBlock() },
];

const caret = () => {
  const selection = window.getSelection();

  return selection?.rangeCount ? selection.getRangeAt(0) : null;
};

// Everything between the start of the current block and the caret.
const preceding = (editor, range) => {
  const probe = document.createRange();

  probe.setStart(editor.block() ?? editor.$refs.editable, 0);
  probe.setEnd(range.endContainer, range.endOffset);

  return probe;
};

const select = (range) => {
  const selection = window.getSelection();

  selection.removeAllRanges();
  selection.addRange(range);
};

const applyBlock = (editor, range, rule, match) => {
  select(preceding(editor, range));
  document.execCommand('delete');

  rule.apply(editor, match);
};

const applyInline = (editor, range, rule, match) => {
  const node = range.endContainer;
  const consumed = match[1].length;

  // The construct has to sit in the node holding the caret, which is where the
  // browser puts freshly typed text.
  if (node.nodeType !== 3 || range.endOffset < consumed) {
    return false;
  }

  const target = document.createRange();

  target.setStart(node, range.endOffset - consumed);
  target.setEnd(node, range.endOffset);
  select(target);

  editor.replaceSelection(`<${rule.tag}>${editor.escapeHtml(match[2])}</${rule.tag}>`);

  return true;
};

export default (editor, character) => {
  const range = caret();

  // Inside a code block the syntax is the content.
  if (!range || editor.ancestor('PRE')) {
    return false;
  }

  const before = preceding(editor, range).toString();

  if (character === ' ') {
    const marker = before.slice(0, -1);
    const rule = BLOCK.find((candidate) => candidate.pattern.test(marker));

    if (!rule) {
      return false;
    }

    applyBlock(editor, range, rule, marker.match(rule.pattern));

    return true;
  }

  if (character === 'Enter') {
    const rule = ENTER.find((candidate) => candidate.pattern.test(before.trim()));

    if (!rule) {
      return false;
    }

    applyBlock(editor, range, rule, null);

    return true;
  }

  // The inline pairs are Markdown syntax, and their triggers are characters
  // ordinary prose is written with. Only the block markers cross into HTML.
  if (!editor.config.markdown) {
    return false;
  }

  for (const rule of INLINE) {
    if (rule.trigger !== character) {
      continue;
    }

    const match = before.match(rule.pattern);

    if (match && applyInline(editor, range, rule, match)) {
      return true;
    }
  }

  return false;
};
