# TallStackUI: Editor

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A WYSIWYG rich text editor with no external JavaScript dependency. It is built on `contenteditable`, outputs HTML or Markdown, and exposes a curated toolbar covering inline formatting, headings, lists, indentation, alignment, quotes, rules, code, links, images, history and fullscreen.

It is content focused rather than a document editor: no tables, no image resize handles, no slash commands, no embeds.

## Basic Usage

```blade
<x-editor wire:model="content" label="Post body" />
```

```blade
<x-editor wire:model="content"
          label="Article"
          hint="Keep it under a thousand words"
          :toolbar="['style', 'bold', 'italic', 'link', 'image']"
          min-height="20rem"
          max-height="60vh" />
```

```blade
<form method="POST" action="{{ route('posts.store') }}">
    @csrf
    <x-editor name="body" label="Body" />
    <x-button submit text="Save" />
</form>
```

Either `wire:model` or `name` is required. With `name` the HTML is mirrored into a hidden input, so the editor works in a plain form outside Livewire.

## Attributes

| Attribute       | Type                | Default          | Description                                                               |
|-----------------|---------------------|------------------|---------------------------------------------------------------------------|
| label           | string, slot        | —                | Label rendered above the editor                                           |
| hint            | string, slot        | —                | Hint rendered below the editor                                            |
| placeholder     | string              | from translation | Painted over the editable while it is empty                               |
| markdown        | bool                | from config      | Stores Markdown instead of HTML                                           |
| output-classes  | bool, string, array | from config      | Stamps a class on every element the editor writes; a string is the prefix |
| toolbar         | array               | from config      | Whitelist and order of the buttons                                        |
| upload-property | string              | —                | `WithFileUploads` property the image dialog uploads to                    |
| upload-method   | string              | —                | Component method returning the final URL of the uploaded image            |
| upload-mimes    | array               | from config      | Mime types the image dialog accepts                                       |
| upload-max-size | int                 | from config      | Ceiling for an uploaded image, in KB                                      |
| counters        | bool                | true             | Word and line counters in the footer                                      |
| min-height      | string              | 12rem            | Minimum height of the editable, in any CSS unit                           |
| max-height      | string              | 40rem            | Maximum height of the editable, in any CSS unit                           |
| readonly        | bool                | false            | Hides the toolbar and stops editing                                       |
| disabled        | bool                | false            | As readonly, plus the disabled styling                                    |
| required        | bool                | false            | Marks the editable as required for assistive technology                   |
| spellcheck      | bool                | true             | Native spellcheck on the editable                                         |
| invalidate      | bool                | from config      | Suppresses validation feedback                                            |

## Toolbar

Twenty buttons across eight groups. Dividers are inserted automatically wherever two consecutive buttons do not share a group.

| Slug           | Group      | Does                                                  |
|----------------|------------|-------------------------------------------------------|
| style          | formatting | Dropdown: Paragraph, Heading 1 to 3                   |
| blockquote     | formatting | Quote                                                 |
| bold           | inline     | Bold                                                  |
| italic         | inline     | Italic                                                |
| underline      | inline     | Underline                                             |
| strikethrough  | inline     | Strikethrough                                         |
| ordered-list   | lists      | Numbered list                                         |
| unordered-list | lists      | Bulleted list                                         |
| indent         | lists      | Nests a list item, or indents any other block by 2rem |
| outdent        | lists      | The reverse                                           |
| align          | align      | Dropdown: Left, Center, Right, Justify                |
| code           | code       | Inline code                                           |
| code-block     | code       | Code block                                            |
| clear-format   | code       | Strips formatting from the selection                  |
| link           | insert     | Opens the link dialog                                 |
| image          | insert     | Opens the image dialog                                |
| hr             | insert     | Inserts a horizontal rule                             |
| undo           | history    | Undo                                                  |
| redo           | history    | Redo                                                  |
| fullscreen     | view       | Fills the viewport                                    |

Passing a slug the list does not know throws. On a viewport too narrow to hold the toolbar it scrolls horizontally rather than collapsing, so a customized order stays as it was written.

Every button names itself through a tooltip, declared as `flash` so it opens on the tick the pointer arrives rather than after the default pause.

Indentation outside a list is a `margin-left` on the block, in steps of 2rem up to eight levels. The native browser command reaches for a `<blockquote>` there, which is a quote rather than an indent and would be stripped by the sanitizer on the way back in. The block is rebuilt through `insertHTML` rather than having the margin written into it, so the step lands in the browser's undo stack and the caret is carried across.

## Bound Value

The property receives the HTML of the editable, or its Markdown when `markdown` is on.

```php
public string $content = '';
```

A new line is a `<p>`. Engines default to a `<div>` there, which says nothing about the content and leaves the stored HTML without a paragraph to style, so `defaultParagraphSeparator` is pinned on boot. Content stored before that carries `<div>` and is styled alongside the paragraph.

`wire:model` defers the round trip and `wire:model.live` pushes on every sync, exactly as Livewire itself behaves. The sync is debounced at 250ms, so `.live` costs one round trip per pause in typing rather than one per keystroke. The payload is the whole document, which is the cost worth watching.

## Markdown

```blade
<x-editor wire:model="content" markdown />
```

The editing surface does not change: it stays a WYSIWYG, and bold text still looks bold while it is being written. Markdown is a serialization format at the boundary, so the property holds `**bold**` where it would otherwise hold `<strong>bold</strong>`. An initial value is read as Markdown too.

Turn it on for every editor at once through the `markdown` key in the config.

### Mapping

| HTML        | Markdown                |
|-------------|-------------------------|
| h1 to h5    | `#` to `#####`          |
| p           | line, then a blank line |
| strong      | `**text**`              |
| em          | `*text*`                |
| s           | `~~text~~`              |
| code        | `` `text` ``            |
| pre > code  | triple backtick fence   |
| ul > li     | `- `                    |
| ol > li     | `1. `                   |
| nested list | two-space indent        |
| blockquote  | `> `                    |
| hr          | `---`                   |
| a           | `[text](href)`          |
| img         | `![alt](src)`           |
| br          | two trailing spaces     |

Tables, task lists and footnotes are not covered, in either direction.

### What Markdown Cannot Carry

`underline` and `align` have no syntax, so they are dropped from the toolbar and their shortcut with them. `indent` and `outdent` survive, but only inside a list, where they nest: outside one they write a `margin-left` that would be lost on the next sync, so they do nothing.

The dropped buttons are removed quietly rather than refused, so a global `markdown` in the config does not invalidate an app-wide toolbar. A slug the component does not know still throws, exactly as it does in HTML mode.

### Pasting

A clipboard carrying structured `text/html`, which is what copying from a page or a document produces, is sanitized and inserted as rich content, then serialized to Markdown on the next sync. That path is the same in both modes.

While `markdown` is on, a clipboard that carries no structure is read as Markdown instead, so pasting the contents of a `.md` file arrives formatted rather than as literal characters. The parsed result still passes through the sanitizer. In HTML mode the same clipboard is inserted verbatim, exactly as before.

"No structure" is decided by looking for anything the serializer can name — a heading, a list, a quote, a fence, a rule, a link, an image or an inline mark. A code editor ships its syntax highlighting as a `text/html` flavour of nested `<span style>`, which holds none of those: the flavour being present is not the same as the clipboard carrying structure, and reading it as rich content is what would make a pasted `.md` file arrive as literal characters.

Ordinary prose is left alone: an isolated `*`, an `A-B-C`, a `snake_case_name` and a URL holding underscores all survive the parse untouched. What does change is a line opening with `- ` or `1. `, which becomes the list it reads as.

## Output Classes

The stored HTML carries the structure and nothing else: an `<ol>` is an `<ol>`, and the classes that make it look like a numbered list live on the editor's own wrapper. Rendered anywhere else, a CSS reset such as Tailwind's Preflight strips the markers and the heading sizes, and the content reads as plain lines.

```blade
<x-editor wire:model="content" output-classes />
```

With the option on, every element the editor writes carries a class of its own, so the same content can be styled wherever it is rendered:

```html
<ol class="tsui-editor-numeric-list"><li class="tsui-editor-list-item">…</li></ol>
```

```css
.tsui-editor-numeric-list { @apply my-2 list-decimal pl-6; }
```

The package defines none of them: they are hooks, empty until the application styles them.

| Tag      | Class                    | Tag        | Class                  |
|----------|--------------------------|------------|------------------------|
| p        | tsui-editor-paragraph    | blockquote | tsui-editor-quote      |
| div      | tsui-editor-block        | pre        | tsui-editor-code-block |
| h1 to h5 | tsui-editor-heading-1..5 | code       | tsui-editor-code       |
| ul       | tsui-editor-bullet-list  | hr         | tsui-editor-rule       |
| ol       | tsui-editor-numeric-list | a          | tsui-editor-link       |
| li       | tsui-editor-list-item    | img        | tsui-editor-image      |
| strong   | tsui-editor-bold         | u          | tsui-editor-underline  |
| em       | tsui-editor-italic       | s          | tsui-editor-strike     |

An array renames the tags it lists and leaves the rest alone. A name has to keep the prefix, which is what the sanitizer recognizes on the way back in; anything else throws.

```blade
<x-editor wire:model="content" :output-classes="['ol' => 'tsui-editor-steps']" />
```

### The Prefix

`tsui-editor-` by default, and `output_classes_prefix` in the config replaces it across every name, so the stored HTML carries the application's namespace rather than the package's.

```php
'output_classes_prefix' => 'blog-',
```

```html
<ol class="blog-numeric-list"><li class="blog-list-item">…</li></ol>
```

Inline, a string on the attribute is the prefix, and it wins over the config:

```blade
<x-editor wire:model="content" output-classes="blog-" />
```

It has to be lowercase, dash separated and end with a dash — `blog-`, `my-app-`. The prefix is the whole of what the sanitizer lets through on a `class`, so an empty or loose one would turn the attribute into an open door; anything outside that shape throws.

Two editors writing under different prefixes write content only they recognize: opening one editor's content in the other strips the classes, exactly as changing the prefix does.

**Choose it before there is content.** The prefix is written into the stored HTML, and the sanitizer only recognizes the one in force: changing it later means the classes already stored are stripped the next time that content is opened in the editor.

The stamp is authoritative rather than incremental. On the way in and after every command the classes are wiped and written again from the tag, so a renamed class, a duplicate, and a class the browser carried onto the wrong element all settle on the next pass.

Turning the option off stops the stamping; it does not rewrite content that already carries the classes. They pass the sanitizer either way, so the round trip leaves them untouched.

Content already stored is not restamped on its own: it picks the classes up the first time it is opened in the editor and saved again.

The option is ignored while `markdown` is on, since Markdown carries no classes.

## Autoformat

The block markers are applied as they are typed, in both modes.

| Type                       | Get             |
|----------------------------|-----------------|
| `# `, `## `, `### `        | Heading 1 to 3  |
| `- `, `* `                 | Bulleted list   |
| `1. `                      | Numbered list   |
| `> `                       | Quote           |
| `---` then Enter           | Horizontal rule |
| triple backtick then Enter | Code block      |

The inline pairs are Markdown syntax and only fire while `markdown` is on: their triggers are characters ordinary prose is written with, and an isolated `*` would keep turning into emphasis.

| Type         | Get           |
|--------------|---------------|
| `**text**`   | Bold          |
| `*text*`     | Italic        |
| `` `text` `` | Inline code   |
| `~~text~~`   | Strikethrough |

Every transform goes through the same command the toolbar uses, so it lands in the browser's undo stack: Ctrl+Z right after one reverts the formatting and leaves the characters that were typed. That is the way out when the marker was meant literally. Inside a code block nothing is transformed, since there the syntax is the content.

## Image Upload

The editor does not decide where an image lives. Point it at a `WithFileUploads` property and a method that persists the file and returns its URL.

```blade
<x-editor wire:model="content" upload-property="picture" upload-method="storeImage" />
```

```php
use Livewire\WithFileUploads;

class PostForm extends Component
{
    use WithFileUploads;

    public string $content = '';

    public $picture = null;

    public function storeImage(): string
    {
        $this->validate(['picture' => ['image', 'max:5120']]);

        return asset('storage/'.$this->picture->store('posts', 'public'));
    }
}
```

Both attributes are required together and only work inside Livewire; either rule broken throws. Without them the image dialog is URL only. The file rides the Livewire upload pipeline, so it has to fit inside the PHP request limits.

## Livewire

The component is rendered with `wire:ignore` inside Livewire. Its content travels through the entangle rather than through the HTML the server re-renders, which is what keeps the caret still while the editor is being typed into.

The cost is that nothing else about the editor reacts to the server either. Changing `readonly`, `placeholder`, `toolbar` or any other attribute from a Livewire round trip leaves the rendered editor as it was. Reach for `wire:key` on the tag when an attribute has to change at runtime, so Livewire replaces the component instead of trying to update it in place.

## Security

The sanitizer strips tags, attributes and style properties outside the configured whitelist. It runs over pasted markup and over any HTML arriving from the bound property, including the value the editor boots with: setting `innerHTML` never runs a `<script>`, but it does fire an `<img onerror>`, and stored content is the path that reaches every reader. It is defense in depth, not the defense.

`href` and `src` are additionally checked by scheme: `javascript:`, `vbscript:` and `data:` are dropped — `data:image/` stays allowed on an `img` src — with whitespace stripped before the check, so an entity-obfuscated scheme does not slip past. The link dialog refuses the same schemes.

Markdown mode is not the safer path it reads as: Markdown permits raw HTML. The parser refuses to emit any it finds, and the sanitizer runs over the result anyway. Both are defense in depth.

**Sanitize the content on the server before persisting it and before rendering it back.** Use `mews/purifier`, `HTMLPurifier` or an equivalent, and sanitize the HTML your Markdown renderer produces. Nothing the browser does can be trusted by the time it reaches a database.

## Events

Dispatched on the component root, so `x-on:` on the tag itself picks them up.

| Event                     | Detail                       | Fires                                                |
|---------------------------|------------------------------|------------------------------------------------------|
| editor:change             | `{ id, html, words, lines }` | After the debounced sync, on a real change           |
| editor:link-inserted      | `{ id, href, text }`         | A link was inserted                                  |
| editor:image-inserted     | `{ id, src, alt, source }`   | An image was inserted, `source` is `url` or `upload` |
| editor:fullscreen-toggled | `{ id, on }`                 | Fullscreen was toggled                               |

```blade
<x-editor wire:model="content" x-on:editor:change="words = $event.detail.words" />
```

`editor:change` always carries the internal `html`, and adds a `markdown` key while `markdown` is on.

## Keyboard

| Shortcut                         | Does                                            |
|----------------------------------|-------------------------------------------------|
| Ctrl/Cmd + B, I, U               | Bold, italic, underline (no U in Markdown mode) |
| Ctrl/Cmd + Z                     | Undo                                            |
| Ctrl/Cmd + Shift + Z, Ctrl + Y   | Redo                                            |
| Ctrl/Cmd + K                     | Opens the link dialog with the selection filled |
| Ctrl/Cmd + 0 to 3                | Paragraph, Heading 1 to 3                       |
| Ctrl/Cmd + \|Clears formatting   |                                                 |
| Tab, Shift + Tab in a list       | Indent, outdent                                 |
| Enter in a code block            | Newline instead of a new paragraph              |
| Ctrl/Cmd + Enter in a code block | Leaves the block                                |
| Escape                           | Closes the dialog, then leaves fullscreen       |
| Arrow keys on the toolbar        | Moves between buttons                           |
| Enter, Space on a toolbar button | Activates it                                    |

## Accessibility

The editable is a `role="textbox"` with `aria-multiline`, labelled by the `label` when there is one and by the placeholder otherwise. The toolbar is a `role="toolbar"` with a roving tabindex, so the arrow keys move between buttons and Tab leaves the whole group. Toggle buttons carry `aria-pressed`. The counters are a polite live region and the upload error is an assertive one.

## Configuration

```php
'editor' => [
    'markdown' => false,
    'output_classes' => false,
    'output_classes_prefix' => null,
    'toolbar' => ['style', 'blockquote', 'bold', ..., 'redo', 'fullscreen'],
    'counters' => true,
    'min_height' => '12rem',
    'max_height' => '40rem',
    'upload' => [
        'mimes' => ['image/png', 'image/jpeg', 'image/gif', 'image/webp'],
        'max_size' => 5120,
    ],
    'sanitization' => [
        'allowed_tags' => ['p', 'br', 'strong', ..., 'blockquote', 'hr'],
        'allowed_attributes' => ['a' => ['href', 'target', 'rel'], ...],
        'allowed_styles' => ['font-size', 'text-align', 'margin-left'],
    ],
],
```

`allowed_styles` is applied after `allowed_attributes`, so widening the tags that may carry a `style` cannot widen what that style is able to do.

SVG is deliberately absent from the upload mimes: it can carry script, and a package default should not open that on its own. Add it per instance through `upload-mimes` when the destination is trusted.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->editor()
    ->block('toolbar.wrapper', 'your-tailwind-classes');
```

The two dialogs are `<x-modal>` instances, so their chrome is customized through the modal itself under a fixed scope:

```php
TallStackUi::customize('modal', scope: 'editor.modal.link')->block('wrapper.fourth', 'rounded-2xl');
TallStackUi::customize('modal', scope: 'editor.modal.image')->block('wrapper.fourth', 'rounded-2xl');
```

The toolbar dropdowns are `<x-dropdown>` instances under their own scope:

```php
TallStackUi::customize('dropdown', scope: 'editor.toolbar')->block('slot.wrapper', 'p-1');
```

### Available Blocks

| Block Name                       | Purpose                                                 |
|----------------------------------|---------------------------------------------------------|
| wrapper.base                     | Outermost frame of the component                        |
| wrapper.fullscreen               | Added to the frame while fullscreen is on               |
| wrapper.disabled                 | Added to the frame while disabled                       |
| toolbar.wrapper                  | Toolbar bar, including its horizontal scrolling         |
| toolbar.divider                  | Separator drawn between two groups                      |
| toolbar.button.base              | Toolbar button                                          |
| toolbar.button.active            | Toolbar button while its format is applied              |
| toolbar.dropdown.trigger         | Button that opens a toolbar dropdown                    |
| toolbar.dropdown.active          | Dropdown entry matching the current block               |
| toolbar.dropdown.style.paragraph | Preview size of the paragraph entry                     |
| toolbar.dropdown.style.h1        | Preview size of the heading 1 entry                     |
| toolbar.dropdown.style.h2        | Preview size of the heading 2 entry                     |
| toolbar.dropdown.style.h3        | Preview size of the heading 3 entry                     |
| toolbar.icon                     | Size of the icons inside the toolbar                    |
| editable.container               | Row holding the editable                                |
| editable.wrapper                 | Scroll container around the editable                    |
| editable.content                 | Editable itself: padding, colour and typography base    |
| editable.placeholder             | Placeholder painted while the editable is empty         |
| editable.typography.headings     | Headings rendered inside the content                    |
| editable.typography.lists        | Lists rendered inside the content                       |
| editable.typography.code         | Inline code and code blocks rendered inside the content |
| editable.typography.link         | Links rendered inside the content                       |
| editable.typography.image        | Images rendered inside the content                      |
| editable.typography.paragraph    | Paragraphs rendered inside the content                  |
| editable.typography.quote        | Blockquotes rendered inside the content                 |
| editable.typography.rule         | Horizontal rules rendered inside the content            |
| footer.wrapper                   | Footer holding the counters                             |
| footer.counter                   | A single counter                                        |
| dialog.fields                    | Spacing between the fields inside a dialog              |
| dialog.error                     | Error line inside the image dialog                      |
| image.upload.area                | Drop area inside the image dialog                       |
| image.upload.button              | Label and icon inside the drop area                     |
| image.upload.hint                | Accepted types and size below the drop area             |
| image.upload.progress.wrapper    | Progress bar track                                      |
| image.upload.progress.bar        | Progress bar fill                                       |
| image.divider                    | "or paste a URL" separator                              |
| image.preview                    | Thumbnail of the image about to be inserted             |

The blocks above style the content while it is being edited, and go on doing so whether `output-classes` is on or off: the two are separate, one dressing the editable and the other travelling with the stored HTML.

### Scoped Customization

```php
TallStackUi::customize('editor', scope: 'compact')->block('toolbar.wrapper', 'py-0.5');
```

```blade
<x-editor wire:model="content" scope="compact" />
```
