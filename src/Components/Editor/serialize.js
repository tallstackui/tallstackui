const BLOCKS = ['P', 'DIV', 'H1', 'H2', 'H3', 'H4', 'H5', 'UL', 'OL', 'PRE', 'BLOCKQUOTE', 'HR'];

// The backslash goes first, or it escapes the escapes added after it.
const escapeInline = (text) =>
  text
    .replace(/\\/g, '\\\\')
    .replace(/([`*[\]])/g, '\\$1')
    .replace(/(^|[^A-Za-z0-9])_/g, '$1\\_');

// Only escaped at the start of a line, where they carry block meaning.
const escapeBlock = (line) =>
  line
    .replace(/^(\s*)(#{1,6}|>|[-+*])(\s|$)/, '$1\\$2$3')
    .replace(/^(\s*)(\d+)\.(\s)/, '$1$2\\.$3');

// A destination holding a space or a parenthesis closes the () early on the
// way back in, so both are percent-encoded, which the browser reads the same.
const destination = (value) =>
  String(value).replace(
    /[()\s]/g,
    (character) => `%${character.charCodeAt(0).toString(16).toUpperCase().padStart(2, '0')}`
  );

// `** bold **` is literal text: the space has to sit outside the markers.
const wrap = (marker, content) => {
  const [, lead, core, trail] = content.match(/^(\s*)([\s\S]*?)(\s*)$/);

  return core === '' ? content : `${lead}${marker}${core}${marker}${trail}`;
};

const inline = (nodes) => {
  let output = '';

  for (const child of nodes) {
    if (child.nodeType === 3) {
      output += escapeInline(child.textContent);

      continue;
    }

    if (child.nodeType !== 1) {
      continue;
    }

    switch (child.tagName) {
      case 'BR':
        output += '  \n';
        break;
      case 'STRONG':
      case 'B':
        output += wrap('**', inline(child.childNodes));
        break;
      case 'EM':
      case 'I':
        output += wrap('*', inline(child.childNodes));
        break;
      case 'S':
      case 'DEL':
      case 'STRIKE':
        output += wrap('~~', inline(child.childNodes));
        break;
      case 'CODE':
        output += child.textContent === '' ? '' : `\`${child.textContent}\``;
        break;
      case 'A':
        output += `[${inline(child.childNodes)}](${destination(child.getAttribute('href') ?? '')})`;
        break;
      case 'IMG':
        output += `![${escapeInline(child.getAttribute('alt') ?? '')}](${destination(child.getAttribute('src') ?? '')})`;
        break;
      // Handled by the list walker.
      case 'UL':
      case 'OL':
        break;
      // Contributes its children and nothing of itself: <u> and styled spans.
      default:
        output += inline(child.childNodes);
    }
  }

  return output;
};

const paragraph = (nodes) =>
  inline(nodes)
    .split('\n')
    .map((line) => escapeBlock(line))
    .join('\n');

// The fence outgrows the longest backtick run inside, or the content closes it.
const fence = (node) => {
  const code = node.querySelector('code') ?? node;
  const text = code.textContent.replace(/\n+$/, '');
  const longest = Math.max(0, ...[...text.matchAll(/`+/g)].map((match) => match[0].length));
  const ticks = '`'.repeat(Math.max(3, longest + 1));

  return `${ticks}\n${text}\n${ticks}`;
};

const list = (node, depth) => {
  const ordered = node.tagName === 'OL';
  const lines = [];
  let index = 1;

  for (const item of node.children) {
    if (item.tagName !== 'LI') {
      continue;
    }

    const marker = ordered ? `${index++}. ` : '- ';
    const content = escapeBlock(inline(item.childNodes));

    lines.push('  '.repeat(depth) + marker + content.replace(/\n/g, `\n${'  '.repeat(depth + 1)}`));

    for (const nested of item.children) {
      if (nested.tagName === 'UL' || nested.tagName === 'OL') {
        lines.push(list(nested, depth + 1));
      }
    }
  }

  return lines.join('\n');
};

// execCommand happily writes a <ul> inside a <p>, so a block element is never
// safe to read as inline content.
const holds = (node) => [...node.children].some((child) => BLOCKS.includes(child.tagName));

const contents = (node) => (holds(node) ? blocks(node).join('\n\n') : paragraph(node.childNodes));

const quote = (node) =>
  contents(node)
    .split('\n')
    .map((line) => (line === '' ? '>' : `> ${line}`))
    .join('\n');

const block = (node) => {
  switch (node.tagName) {
    case 'H1':
    case 'H2':
    case 'H3':
    case 'H4':
    case 'H5':
      return `${'#'.repeat(Number(node.tagName[1]))} ${inline(node.childNodes)}`;
    case 'HR':
      return '---';
    case 'PRE':
      return fence(node);
    case 'UL':
    case 'OL':
      return list(node, 0);
    case 'BLOCKQUOTE':
      return quote(node);
    default:
      return contents(node);
  }
};

// Anything that is not a block belongs to the paragraph being built around it:
// typing into an empty editable leaves bare text and bare <strong> at the root.
const blocks = (root) => {
  const output = [];
  let pending = [];

  const push = (content) => {
    if (content.trim() !== '') {
      output.push(content);
    }
  };

  const flush = () => {
    if (pending.length > 0) {
      push(paragraph(pending));
      pending = [];
    }
  };

  for (const node of root.childNodes) {
    if (node.nodeType === 1 && BLOCKS.includes(node.tagName)) {
      flush();
      push(block(node));

      continue;
    }

    pending.push(node);
  }

  flush();

  return output;
};

export default (root) =>
  blocks(root)
    .join('\n\n')
    .replace(/\n{3,}/g, '\n\n')
    .trim();
