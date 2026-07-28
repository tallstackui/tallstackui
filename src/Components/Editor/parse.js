const FENCE = /^ {0,3}(`{3,}|~{3,})\s*\S*\s*$/;
const RULE = /^ {0,3}(-{3,}|\*{3,}|_{3,})\s*$/;
const HEADING = /^ {0,3}(#{1,6})\s+(.*?)\s*#*\s*$/;
const QUOTE = /^ {0,3}> ?(.*)$/;
const ITEM = /^(\s*)([-+*]|\d+[.)])\s+(.*)$/;

// The whitelist stops at h5, so a deeper heading lands on the last one it holds.
const LEVEL = 5;

// Private use characters: they cannot collide with anything the user typed.
const OPEN = '\uE000';
const CLOSE = '\uE001';
const PARKED = new RegExp(`${OPEN}(\\d+)${CLOSE}`, 'g');

const escapeHtml = (value) =>
  value.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

const inline = (text) => {
  const shelf = [];
  const park = (html) => `${OPEN}${shelf.push(html) - 1}${CLOSE}`;

  // Backslash escapes are shelved before anything can read them as syntax, and
  // code spans before the emphasis pass, so a marker inside code survives.
  const output = escapeHtml(
    String(text).replace(/\\([\\`*_[\]#>~\-+.!()])/g, (_, character) => park(escapeHtml(character)))
  )
    .replace(/(`+)([^`]+?)\1/g, (_, __, code) => park(`<code>${code}</code>`))
    .replace(/!\[([^\]]*)]\(([^)\s]+)\)/g, '<img src="$2" alt="$1" />')
    .replace(/\[([^\]]+)]\(([^)\s]+)\)/g, '<a href="$2">$1</a>')
    .replace(/(\*\*|__)(?=\S)([\s\S]*?\S)\1/g, '<strong>$2</strong>')
    .replace(/(^|[^*])\*(?=\S)([^*]*?\S)\*/g, '$1<em>$2</em>')
    .replace(/(^|[^\w_])_(?=\S)([^_]*?\S)_(?![\w_])/g, '$1<em>$2</em>')
    .replace(/~~(?=\S)([\s\S]*?\S)~~/g, '<s>$1</s>')
    .replace(/ {2,}\n/g, '<br>');

  return output.replace(PARKED, (_, index) => shelf[index]);
};

// Collect the run of lines the current block owns, letting the caller say what
// closes it.
const gather = (lines, start, closes) => {
  let end = start;

  while (end < lines.length && !closes(lines[end])) {
    end++;
  }

  return end;
};

const fence = (lines, start) => {
  const marker = lines[start].match(FENCE)[1][0];
  const closes = new RegExp(`^ {0,3}\\${marker}{3,}\\s*$`);
  const end = gather(lines, start + 1, (line) => closes.test(line));
  const code = lines.slice(start + 1, end).join('\n');

  return [`<pre><code>${escapeHtml(code)}</code></pre>`, Math.min(end + 1, lines.length)];
};

const quote = (lines, start) => {
  const end = gather(lines, start, (line) => !QUOTE.test(line) && line.trim() !== '');
  const inner = lines.slice(start, end).map((line) => line.match(QUOTE)?.[1] ?? '');

  return [`<blockquote>${compile(inner)}</blockquote>`, end];
};

const list = (lines, start) => {
  const [, indentation, first] = lines[start].match(ITEM);
  const base = indentation.length;
  const ordered = /\d/.test(first);
  const items = [];
  let index = start;

  while (index < lines.length) {
    const match = lines[index].match(ITEM);

    if (!match) {
      break;
    }

    const indent = match[1].length;

    if (indent < base) {
      break;
    }

    if (indent > base) {
      const [html, next] = list(lines, index);

      items[items.length - 1].nested.push(html);
      index = next;

      continue;
    }

    // A bullet meeting a number is a second list, not a sibling item.
    if (ordered !== /\d/.test(match[2])) {
      break;
    }

    items.push({ text: match[3], nested: [] });
    index++;
  }

  const tag = ordered ? 'ol' : 'ul';
  const html = items.map((item) => `<li>${inline(item.text)}${item.nested.join('')}</li>`).join('');

  return [`<${tag}>${html}</${tag}>`, index];
};

const interrupts = (line) =>
  line.trim() === '' ||
  FENCE.test(line) ||
  RULE.test(line) ||
  HEADING.test(line) ||
  QUOTE.test(line) ||
  ITEM.test(line);

const paragraph = (lines, start) => {
  const end = gather(lines, start, interrupts);

  return [`<p>${inline(lines.slice(start, end).join('\n'))}</p>`, Math.max(end, start + 1)];
};

const compile = (lines) => {
  const html = [];
  let index = 0;

  while (index < lines.length) {
    const line = lines[index];

    if (line.trim() === '') {
      index++;

      continue;
    }

    if (RULE.test(line)) {
      html.push('<hr>');
      index++;

      continue;
    }

    const heading = line.match(HEADING);

    if (heading) {
      const level = Math.min(heading[1].length, LEVEL);

      html.push(`<h${level}>${inline(heading[2])}</h${level}>`);
      index++;

      continue;
    }

    const block = FENCE.test(line)
      ? fence
      : QUOTE.test(line)
        ? quote
        : ITEM.test(line)
          ? list
          : paragraph;
    const [content, next] = block(lines, index);

    html.push(content);
    index = next;
  }

  return html.join('');
};

export default (markdown) =>
  compile(
    String(markdown ?? '')
      .replace(/\r\n?/g, '\n')
      .split('\n')
  );
