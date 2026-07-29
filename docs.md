# TallStackUI 4.x

Running record of everything that changed on the `4.x` branch relative to `3.x`.

Entries are grouped by component, most recently touched first. Within a component,
changes are split into **Added**, **Changed** and **Fixed**. Anything that requires
action from an upgrading application carries a **Migration** note.

Soft customization keys are part of the public API: renaming, nesting or removing a
block breaks applications that target it through `TallStackUi::customize()`. Every
such change is listed under **Migration**.

---

## Chart

### Added — `<x-chart />`, a dependency-free chart

Five types rendered as inline SVG, with no charting library involved. Every
path, rectangle and arc is computed server-side and shipped as markup, so there
is nothing to hydrate and no flash before Alpine boots:

```blade
{{-- A flat list of numbers is a single unnamed series --}}
<x-chart :series="[10, 40, 25, 60, 30, 80]" />

{{-- Several named series share one scale, so they compare at a glance --}}
<x-chart :labels="['Jan', 'Fev', 'Mar', 'Abr']"
         :series="[
             ['name' => '2026', 'data' => [10, 40, 25, 60]],
             ['name' => '2025', 'data' => [8, 30, 33, 41]],
         ]"
         type="bar"
         grid
         legend
         tooltip
         prefix="R$ " />
```

Types: `area` (default), `line`, `bar`, `pie` and `donut`. Area and bar also
accept `stacked`. Chrome is opt-in through `grid`, `legend`, `tooltip` and
`markers`, and values are formatted with `prefix`, `suffix` and `decimals`.

`height` and the four chrome flags take an application-wide default from the
config, each overridable at the call site. `type` deliberately does not: a
dashboard mixes bars, lines and pies, so it stays a per-chart decision.

**Formatting beyond a prefix takes a closure**, because a locale or a currency
is a decision per chart rather than per application:

```blade
<x-chart :series="$revenue" grid :formatter="fn (float $value) => Number::currency($value, 'BRL', 'pt_BR')" />
```

It receives the axis as a second argument, wins over `prefix`/`suffix`/
`decimals`, and covers the axis labels and the tooltip alike — every displayed
number is formatted server-side, so nothing has to cross over to JavaScript.

**A series can bind itself to a secondary axis** with `'axis' => 'right'`, for
when its magnitude would flatten everything else against a shared scale. Both
axes are pinned to the same tick count, so one set of gridlines serves either
side. Formatting resolves per axis: a scalar `prefix`, `suffix` or `decimals`
applies to both, an array picks the side.

**Interaction runs on Pointer Events**, so it works from mouse, touch and pen.
On touch a tap opens the tooltip and a tap outside closes it; dragging is left
to the page, because capturing it would need `preventDefault` and lock scrolling
inside a chart that often fills a small screen.

The component ships **no card of its own**, so the same class serves a
standalone chart inside `<x-card paddingless>` and the background layer of
`<x-stats>`.

**Interpolation is monotone cubic (Fritsch-Carlson), not Catmull-Rom.** The
curve is guaranteed never to leave the range of the data it passes through. A
plain Catmull-Rom spline overshoots by up to 7% of the plot height on the common
flat-then-jump series, which on an area chart self-intersects the fill under its
own baseline and draws a value lower than the series minimum.

**Nothing textual or circular lives inside the SVG.** The plot stretches through
`preserveAspectRatio="none"`, which would distort both, so axis labels and point
markers are HTML positioned over it — and gain Tailwind typography and dark mode
in the process.

**Toggling a series in the legend rescales the rest through an SVG `transform`,
not a recomputed curve.** Rescaling a domain is an affine map in y and Beziers
are affine invariant, so the interpolation exists in exactly one place, in PHP.
Rescaling is skipped where it would mislead: on a labelled grid, on stacked or
bar charts, and whenever a secondary axis exists.

A pie is the exception: removing a slice redistributes every remaining angle,
which is a real recomputation rather than a transform, so its arc trigonometry
is mirrored in the Alpine layer and the tooltip percentages follow along.

**Livewire lazy loading works natively.** The markup arrives already drawn, and
hit-testing measures the element on the pointer event rather than on `init()`,
which is the usual failure mode for charting libraries mounted before their
container has a size.

Series longer than 120 points are bucketed down, keeping each bucket's lowest
and highest value in the order they appeared, so peaks and the scale anchors
always survive. The chosen indexes are shared across series so multiple curves
stay aligned.

An empty series renders the plot at full height with no path, so a card holding
it does not jump, and a single value spans the plot as a constant series, the
same as `[7, 7, 7]` would.

Everything else fails loudly rather than degrading: non-numeric values, `NAN`
and `INF`, an unknown `type` or `axis`, `stacked` on a line or radial type,
`stacked` alongside a secondary axis, `grid` on a radial type, a negative or
non-integer `decimals`, and a formatting array keyed by anything other than
`left` and `right`. The last one is the quietest of them — an unrecognized key
used to be dropped without a word, which reads as if it had worked.

Full reference in `.ai/components/chart.md`.

### Changed — the chart ships in its own bundle

`js/tallstackui-chart.js` joined the entry points, weighing 3.9 kB, 1.6 kB
gzipped. Same reasoning as the editor and upload splits: not lazy loading —
`Directives::script()` emits every entry of the manifest on every page — but
cache granularity, so a change to the chart stops invalidating the bundle every
other component lives in. What a static chart skips is the work, not the bytes:
no `tallstackui_chart` instance is created without `tooltip` or `legend`.

## Floating

### Added — `floating_scroll_lock`, locking the page scroll while a popup is open

A modal locks the page behind it; a dropdown never did, so the content under an
open popup kept scrolling while the popup stayed anchored where it was. The lock
is now available to every component built on `<x-floating>`, through a single
top-level key rather than one per component:

```php
// config/tallstackui.php
'floating_scroll_lock' => true,
```

It reaches Dropdown and its Submenu, Autocomplete, Color, Date, Password, Select
Styled, Time, Upload, Calendar and the List Items menu at once. The mechanics are
the ones Modal and Slide already use: `overflow: hidden` on the `<body>` plus the
compensating `padding-right`.

Off by default, and there is no per-instance opt out. That compensating
`padding-right` shifts the layout on every open, which reads very differently on a
three-item dropdown than it does on a modal — enabling it is a deliberate choice
about how the whole application should feel, not a per-call-site one.

**Nested and stacked popups share a single lock.** A Dropdown Submenu renders a
floating of its own inside its parent, so a naive implementation would re-lock on
open and unlock on close, dropping the lock while the parent menu was still on
screen. References are counted in `window.__tsui_floating_locks`: the first popup
to open takes the lock, the last to close returns it.

The release also covers the paths that never run a close — a floating torn out of
the DOM by a Livewire morph or a collapsing `@if`, and an anchor leaving layout on
a Tab swap or an Accordion collapse.

**A popup opened inside a Modal or a Slide does not touch the lock.** The overlay
already owns it, and closing the popup leaves the body locked.

Floatings are deliberately kept out of `window.__tsui_elements`, the registry
behind `top_ui_element()`. Joining it would have given a refcount for free, but it
would also have made an open dropdown the topmost element, taking `Escape` and
click-outside away from the modal behind it — a behavioural change well outside
what a scroll-lock flag should carry.

**Migration:** nothing. The flag defaults to off and no customization block
changed.

### Fixed — an inner element could unlock a body it did not lock

`overflow()` let any component release the lock regardless of which one had taken
it. The gate for restoring the body tested `__tsui_elements.length === 1`, which a
single open overlay satisfies — so a Loading or an Upload preview closing inside
an open Modal restored the page scroll with the modal still on screen.

Releasing now requires owning the `data-overflow` marker, and the reset itself
requires that no other overlay is still registered. This predates the floating
work; the lock is reachable from more places now, which is what surfaced it.

---

## Modal, Slide, Card & Errors

### Added — footer slot alignment through `start`, `center`, `end`, `between` and `unwrapped`

The footer is where actions live, and until now the way they were distributed was
decided by the component, differently in each one. Modal always pushed them to
the right. Card pushed them right only when the footer came in as a string, and
left a `<x-slot:footer>` untouched. Slide understood `start` and `end` but
defaulted to neither. Errors understood `end` alone. The four now read the
alignment from the slot itself, through the same attributes.

```blade
<x-modal>
    Content

    <x-slot:footer between>
        <x-button color="red">Delete</x-button>
        <x-button>Save</x-button>
    </x-slot:footer>
</x-modal>
```

Five attributes, identical across the four:

| Attribute   | Result                                     |
|-------------|--------------------------------------------|
| *(none)*    | `justify-end` — the previous Modal default |
| `start`     | `justify-start`                            |
| `center`    | `justify-center`                           |
| `end`       | `justify-end`, written out                 |
| `between`   | `justify-between`                          |
| `unwrapped` | no aligning wrapper at all                 |

`unwrapped` drops the flex wrapper and nothing else: the footer area keeps its
border, its padding and its margin, and the slot content becomes their direct
child. It is for footers that lay themselves out — a grid, a full-width bar, a
form row that has its own idea of spacing.

Combining alignments, or mixing one with `unwrapped`, throws.

Every other attribute on the slot — `class`, `x-on:*`, `dusk` — is merged into
the footer container. Slide already did this and leaked the alignment keywords
into the markup as `start="start"`; the keywords are now stripped, and the other
three gained the merge they never had.

Alignment applies to the slot form only. A footer passed as a string attribute
carries no attributes to read, and keeps whatever the component already did with
it — the end-aligned row on Modal, Slide and Card, the plain paragraph on Errors.

The resolution lives in `AbstractRuntime::alignment()` and
`AbstractRuntime::alignable()`, so the two components still carrying an unaligned
footer — Table and Stats — can adopt the same attributes without repeating it.

**Migration:** the footer blocks were split.

| Component | Before          | After                                                                    |
|-----------|-----------------|--------------------------------------------------------------------------|
| Modal     | `footer`        | `footer.wrapper` (border, padding, color) + `footer.base` (`flex gap-2`) |
| Slide     | `footer.base`   | `footer.wrapper` (border, padding) + `footer.base` (`flex gap-2`)        |
| Card      | `footer.text`   | `footer.base` (`flex items-center gap-2`)                                |
| Errors    | `slots.footer`  | `slots.footer.wrapper` (margin) + `slots.footer.base` (`flex gap-2`)     |

All four gained the four alignment blocks under the same prefix — `footer.start`
and siblings, `slots.footer.start` and siblings on Errors. `footer.scrollable` on
Modal and `footer.wrapper` on Card are unchanged. An application customizing
`modal.footer`, `slide.footer.base`, `card.footer.text` or `errors.slots.footer`
has to point at the new block, choosing between the chrome and the alignment row.

Rendering changed in four ways:

- Modal, Slide and Errors footers now nest an extra `<div>` for the alignment.
- A Card footer passed as `<x-slot:footer>` is aligned to the end instead of
  falling through raw — the behaviour a string footer already had. A Card relying
  on that raw fall-through wants `unwrapped`.
- A Slide footer with no attribute used to sit at the start, since the base block
  carried `flex` with no `justify-*`. It now defaults to the end, and `start`
  restores the old look. The Slide base also gained `gap-2`, which it lacked
  while Modal and Card had it.
- An Errors footer slot with no attribute used to render raw, with no container
  at all. It now gets the `mt-2` container and the end alignment. `unwrapped`
  brings back the container without the alignment; the fully raw output is gone.

---

## Modal, Slide, Card & Tab

### Added — `paddingless`, removing the padding of the main slot

The four components that wrap content in a padded area now share a flag that
strips that padding, leaving the slot flush against the edges of the component.
It is what a table, an image or a nested list wants: whatever draws its own
spacing, or is meant to bleed.

```blade
<x-card paddingless>
    <x-table :$headers :$rows />
</x-card>
```

Only the main slot is affected. Headers and footers keep their padding, so a
titled modal holding a flush body still reads as a modal.

The flag maps to a new customization block per component — `body.paddingless`
on Modal, Slide and Card, `base.content-paddingless` on Tab — carrying `p-0!`.
The block is appended to the existing one rather than replacing it, so the
importance modifier is what wins over the padding already there. An application
customizing `body` is unaffected, and one that wants the flush body everywhere
can reach for the new block instead of passing the flag at every call site.

On Slide the result is flush horizontally but not vertically: the `py-6` that
insets the body lives on the outer panel, shared with the header and the footer,
and removing it would move all three. Vertical bleed on a Slide is a soft
customization of `wrapper.fifth`.

**Migration:** nothing. The flag defaults to off and no existing block changed.

---

## Editor

### Added — `<x-editor />`, a WYSIWYG editor with no external dependency

A rich text editor built on `contenteditable`, shipping nothing but the package
itself. It outputs HTML, binds through `wire:model` or through a plain `name`,
and carries twenty buttons across eight groups: headings, the four inline marks,
lists, indentation, alignment, quotes, rules, code, links, images, history and
fullscreen.

```blade
<x-editor wire:model="content" label="Post body" />
```

```blade
<x-editor wire:model="content"
          upload-property="picture"
          upload-method="storeImage"
          :toolbar="['style', 'bold', 'italic', 'link', 'image']"
          min-height="20rem" />
```

**The engine is a hybrid.** `document.execCommand` where the browsers agree, and
the Selection API by hand where they do not. Every structural change is routed
through `insertHTML` rather than through the DOM, because a node inserted by hand
is invisible to the browser's own undo stack and `Ctrl+Z` would walk straight past
it.

**Indentation is two different things.** Inside a list the browser nests, which is
the right shape there. Anywhere else it is a `margin-left` on the block, in steps
of `2rem` up to eight levels — the native command reaches for a `<blockquote>`
there, which is a quote rather than an indent and would be stripped by the
sanitizer on the way back in. That margin is the one action that stays outside the
undo stack: re-serializing the block to get it in there would drop the caret.

**Both dialogs are `<x-modal>` instances**, under the fixed scopes `editor-link`
and `editor-image`. The modal already owns the scroll lock, the overlay registry,
`Escape` with its topmost guard and the focus of its first field, and being
teleported to `<body>` takes its inputs out of any surrounding `<form>`, where
`Enter` would otherwise submit it. The toolbar dropdowns are `<x-dropdown>` under
`editor-toolbar`. What is left in the editor's own surface is the content: 40
blocks rather than the 55 a self-contained dialog would have needed.

**The whole component is `wire:ignore`d under Livewire**, and nothing about it
reacts to the server as a result: changing `readonly` or any other attribute
from a round trip leaves the rendered editor as it was, so a runtime change
needs a `wire:key` to force the replacement. The content travels
through the entangle, never through the HTML the server re-renders — and the
initial value is withheld from the `x-data` when a property is bound, since a
changing `x-data` attribute makes Alpine tear the component down and rebuild it,
which lands the caret back at the start of the document.

**The HTML is sanitized against a whitelist** of tags, attributes and style
properties, parsed in a `<template>` so nothing runs on the way through. It runs
over pasted markup and over anything arriving from the bound property, the value
the editor boots with included: setting `innerHTML` never runs a `<script>`, but
it does fire an `<img onerror>`, and stored content is the path that reaches
every reader. This is still defense in depth — the documentation is explicit that
the HTML must be sanitized again on the server before it is persisted and before
it is rendered back.

`image/svg+xml` is deliberately absent from the default upload mimes: SVG can
carry script, and a package default should not open that on its own.

**Nineteen internal icons** were added to the guide and to the published icon
map, so an application swapping its icon set keeps the toolbar working.

Full reference in `.ai/components/editor.md`.

### Changed — the editor ships in its own bundle

`js/tallstackui-editor.js` joined the entry points, weighing 11.5 kB, 3.7 kB
gzipped. Same reasoning as the upload split: not lazy loading, but cache
granularity, so a change to the editor stops invalidating the bundle every other
component lives in.

### Fixed — the sanitizer discarded alignment on pasted paragraphs

`allowed_attributes` let `style` through on `span` and `div` but not on `p` or the
headings, while the browser writes `text-align` straight onto the paragraph.
Copying a centered paragraph and pasting it back lost the alignment. `style` is
now allowed on `p`, `h1`–`h5` and `li`, which cannot widen the surface: the
`allowed_styles` whitelist is applied afterwards, so only `font-size`,
`text-align` and `margin-left` survive on any of them.

### Added — `markdown`, storing Markdown instead of HTML

The editing surface does not change: it stays a WYSIWYG, and bold text still
looks bold while it is written. Markdown is a serialization format at the
boundary, so the property receives `**bold**` where it would otherwise receive
`<strong>bold</strong>`. An initial value is read as Markdown too.

```blade
<x-editor wire:model="content" markdown />
```

Turn it on for every editor at once through the `markdown` key in the config.
Conversion happens in the browser, in two hand-written modules of roughly two
hundred lines each, so nothing is added to the dependency tree: the editor
bundle grows from 11.4 kB to 18.4 kB, 6.2 kB gzipped.

Covered in both directions: `h1`–`h5`, paragraphs, `strong`, `em`, `s`, inline
code, fenced code, ordered and unordered lists including nesting, blockquotes,
horizontal rules, links, images and hard breaks. Tables, task lists and
footnotes are not.

`underline` and `align` have no Markdown syntax, so they are dropped from the
toolbar along with their shortcut. They are removed quietly rather than refused,
so a global `markdown` in the config does not invalidate an app-wide toolbar; a
slug the component does not know still throws. `indent` and `outdent` survive,
but only inside a list, where they nest: outside one they write a `margin-left`
that would be lost on the next sync, so they do nothing.

`editor:change` keeps its `html` key and gains a `markdown` one while the mode
is on, so nothing already listening breaks.

### Added — `blockquote` and `hr` toolbar buttons

Two constructs Markdown names and the editor had no way to write, in either
mode. `blockquote` serializes to `> ` and `hr` to `---`. Both tags joined the
sanitization whitelist and both carry a customization block of their own,
`editable.typography.quote` and `editable.typography.rule`.

They also joined the default toolbar, which now holds twenty buttons across
eight groups. An application pinning its own `toolbar` array is unaffected.

### Added — Markdown autoformat while typing

While `markdown` is on, the syntax is applied as it is typed: `# `, `## ` and
`### ` open a heading, `- ` and `* ` a bulleted list, `1. ` a numbered one, `> `
a quote, `---` and a triple backtick followed by Enter a rule and a code block,
and `**text**`, `*text*`, `` `text` `` and `~~text~~` their inline marks.

Every transform goes through the same command the toolbar uses, so it lands in
the browser's undo stack: Ctrl+Z right after one reverts the formatting and
leaves the characters that were typed. That is the way out when the marker was
meant literally. Inside a code block nothing is transformed, since there the
syntax is the content.

### Changed — pasting text into a Markdown editor reads it as Markdown

A clipboard carrying structured HTML is sanitized and inserted as rich content,
exactly as before, in both modes. While `markdown` is on, a clipboard carrying
no structure is parsed as Markdown instead, so pasting the contents of a `.md`
file arrives formatted rather than as literal characters. The parsed result
still passes through the sanitizer.

"No structure" means holding none of the tags the serializer can name — a
heading, a list, a quote, a fence, a rule, a link, an image or an inline mark.
The distinction matters because a code editor ships its syntax highlighting as a
`text/html` flavour of nested `<span style>`: the flavour being present is not
the same as the clipboard carrying structure, and reading it as rich content is
what would make a pasted `.md` file arrive as literal characters anyway.

Ordinary prose is left alone. An isolated `*`, an `A-B-C`, a `snake_case_name`
and a URL holding underscores all survive untouched. What does change is a line
opening with `- ` or `1. `, which becomes the list it reads as.

**Migration:** nothing, unless an application relied on pasting raw Markdown
into a Markdown editor and getting literal characters back.

---

## Form / Radio & Checkbox / Group

### Added — `<x-radio.group />` and `<x-checkbox.group />`, a whole set of options from one array

`<x-radio />` and `<x-checkbox />` render a single control. Anything resembling a
plan picker, a segmented control or a feature list meant writing the loop, the
`<label>`, the wrapper and the selected-state classes by hand, every time. Two new
components take the array instead, and are otherwise unrelated to the singular ones,
which are untouched:

```blade
<x-radio.group wire:model="plan" label="Plan" :options="[
    ['label' => 'Startup', 'value' => 'startup', 'description' => 'Up to 5 job postings', 'aside' => '$29 / mo'],
    ['label' => 'Business', 'value' => 'business', 'description' => 'Up to 25 job postings', 'aside' => '$99 / mo'],
]" />

<x-checkbox.group wire:model="features" card :columns="2" color="green" :options="$features" />
```

Extending `<x-radio />` with an `options` attribute was considered and rejected: the
singular component is one input inside a `<x-wrapper.radio>`, while a group is a
`<fieldset>` with a `<legend>` and its own layout, and the two share no markup. The
attribute would have been a second component hiding inside the first.

### Added — four presentations, selected by flag

```blade
<x-radio.group panel :options="$options" />
```

| Variant  | Layout                                        | Control     |
|----------|-----------------------------------------------|-------------|
| `list`   | Stacked rows sharing borders                  | Visible     |
| `card`   | Independent cards in a responsive grid        | Visible     |
| `panel`  | Cards with a check icon marking the selection | `sr-only`   |
| `inline` | Horizontal segmented control with solid fill  | `sr-only`   |

Each presentation is a boolean flag rather than a `variant="panel"` string, matching
how `<x-gallery>` and `<x-carousel>` already read. `variant` survives as an internal
`#[SkipDebug]` property resolved in `SelectionSetup::setup()`, so the templates keep
a single string to switch on.

Passing none renders `list`. Passing more than one resolves to the first of `card`,
`panel`, `inline` — silent precedence, the same rule `xs`/`sm`/`lg` already follow in
the same trait. Throwing on the combination was considered; it would have been the
only size-style flag group in the library that does.

`panel` is `card` plus an `sr-only` control and a check icon. It deliberately does
**not** thicken the border when selected: a `border-2` on the checked state shifts
the card's content by a pixel, and a grid of panels visibly twitches as the selection
moves across it.

`inline` drops `description`, `aside`, `image` and `badge` — a segmented control has
no room for them, and rendering them would break the row rather than merely look
crowded. They are ignored, not rejected, so the same `$options` array can be handed
to any variant.

### Added — selected state with no JavaScript at all

The whole selected appearance is CSS, through the `has-checked` and
`group-has-checked` Tailwind variants:

```html
<label class="group ... has-checked:bg-primary-50 has-checked:border-primary-500">
    <input type="radio" value="business">
    <span class="group-has-checked:text-primary-900">Business</span>
</label>
```

`has-*` styles the `<label>` from the state of the input inside it; `group-has-*`
reaches the descendants that are not the input's siblings. Neither needs `peer`,
which only walks forward from a sibling and could not reach the wrapper.

No `x-data`, no Alpine component, no new entry in the bundle — only
`dist/tallstackui.css` grew. Which also means the groups work identically in plain
Blade and under Livewire, and that a `wire:model` round trip cannot desynchronize the
highlight from the checked input, because the highlight *is* the checked input.

`has-focus-visible` puts the focus ring on the `<label>` rather than the control, so
the `sr-only` variants stay keyboard-navigable: the control keeps its place in the
tab order and toggles with Space, and the ring is drawn around what the user actually
sees.

### Added — the option array, and an escape hatch when it is not enough

| Key           | Type   | Required | Ignored by |
|---------------|--------|----------|------------|
| `label`       | string | yes      | —          |
| `value`       | scalar | yes      | —          |
| `description` | string | no       | `inline`   |
| `aside`       | string | no       | `inline`   |
| `icon`        | string | no       | —          |
| `image`       | string | no       | `inline`   |
| `badge`       | string | no       | `inline`   |
| `disabled`    | bool   | no       | —          |

`image` wins over `icon` when both are present. A missing `label` or `value`, or an
option that is not an array, throws rather than rendering an empty row.

`select` remaps the source keys with the syntax `<x-select.styled>` already uses, so
an array coming from the database does not have to be reshaped first:

```blade
<x-radio.group select="label:name|value:id|description:note" :options="$plans" />
```

`@interact('option', $option)` replaces the body of every item while the `<label>`,
the `<input>` and the selected-state classes stay owned by the component. The closure
receives the option with its **original** keys still reachable, since normalization
spreads the source array before writing the canonical keys over it:

```blade
<x-checkbox.group card :options="$addons">
    @interact('option', $option)
        <span class="font-semibold">{{ $option['name'] }}</span>
        <span class="font-mono">${{ $option['price'] }}</span>
    @endinteract
</x-checkbox.group>
```

The loop variable inside the item template is `$item`, not `$option`, precisely so it
cannot collide with the slot variable the directive introduces.

### Added — one shared `name`, derived when it is not given

Every input carries the same `name`, falling back to `id` and then to the bound
property, suffixed with `[]` on checkbox groups:

```html
<input type="radio"    id="plan-0"     name="plan">
<input type="checkbox" id="features-0" name="features[]">
```

Without it a radio group is not a group. In plain Blade the options stop being
mutually exclusive, and — more subtly — under Livewire a `required` group inside a
`<form wire:submit>` never submits: each input is its own constraint-validation
group, so the browser demands all of them be checked and blocks the submit event
before Livewire's listener ever runs.

`required` behaves differently across the two components, which is why the attribute
tables differ. On the radio group it marks the legend **and** sets the native
attribute. On the checkbox group it marks the legend only: the native attribute there
would demand every box be ticked.

Out of Livewire, `value` drives the checked state — a scalar for radio, an array for
checkbox. Inside Livewire it is ignored, since the bound property is the source of
truth.

### Added — `SelectionColors`, six palettes across 29 colors

`color` drives more than one thing, so the class exposes six palettes rather than the
usual two:

| Palette      | Drives                                                  |
|--------------|---------------------------------------------------------|
| `background` | The selected item's background                          |
| `border`     | The selected item's border                              |
| `control`    | The input itself, and its focus ring                    |
| `text`       | The selected label and icon                             |
| `muted`      | The selected description and aside                      |
| `solid`      | The fill of a selected segment on `inline`              |

`muted` exists because a single `text` palette rendered the label and the description
in the same tint, which flattened the row — the description has to stay secondary
after selection, not just before it. `solid` carries only the background: the label
on top of it switches to white through the `content.inline` block, since
`text-primary-900` on `bg-primary-500` is unreadable.

One class serves both components, the way `ProgressColors` and `TimelineColors`
already serve two each. `SetupColors` resolves a published override through the
`class_basename` of the `#[ColorsThroughOf]` argument, so a single
`SelectionColors.php` in an application customizes both groups at once.

### Changed — the radio and checkbox views moved into their own directories

```
form/radio.blade.php     →  form/radio/main.blade.php
form/checkbox.blade.php  →  form/checkbox/main.blade.php
                            form/radio/group/{main,item}.blade.php
                            form/checkbox/group/{main,item}.blade.php
```

This follows the `main.blade.php` convention the package already uses for `card`,
`dropdown`, `modal`, `timeline` and others. The group templates started life in a
shared `form/selection-group/` directory rendering both components from one copy;
they were split so each component owns its markup, at the cost of `item.blade.php`
existing twice.

**No migration.** Customization block names, scope names and the component classes are
unchanged; only the view paths behind them moved, and nothing publishes these views.

### Notes on internals

`SelectionSetup` normalizes the options and resolves `variant`, `size`, `position`
and the `select` mapping. `SelectionCustomization` returns the block tree, taking the
control's shape (`form-radio rounded-full` / `form-checkbox rounded`) as its only
argument. `SelectionGroupRuntime` resolves the legend, the shared `name`, the
out-of-Livewire selection and a per-variant render profile — that last one is what
lets a single `item.blade.php` serve all four presentations instead of four
near-identical partials. Each template keeps a single `@php` block.

The `<legend>` renders its text directly rather than nesting a `<x-label>`: a
`<label>` with no control inside a `<legend>` is invalid, so the group carries its own
`wrapper.legend`, `wrapper.legend-error` and `wrapper.asterisk` blocks mirroring the
Label's styling. The `label="Plan *"` asterisk convention is preserved.

Attributes are routed rather than merged wholesale — `class` lands on the
`<fieldset>`, everything else (`wire:model`, `x-on:*`, `data-*`) lands on every
`<input>`.

Customization blocks: `wrapper.*`, `container.*`, `columns.*`, `item.*`, `control.*`,
`check` and `content.*`. Internal scopes `form.radio.group.{hint,error}` and
`form.checkbox.group.{hint,error}`.

Covered by 52 feature tests and 6 browser tests. Full reference in
`.ai/components/form/radio/group.md` and `.ai/components/form/checkbox/group.md`.

---

## Gallery

### Added — `<x-gallery />`, an image gallery with three layouts and a shared lightbox

One component covering the three arrangements an image gallery usually needs,
selected by mutually exclusive boolean flags rather than a string attribute, which
matches how `<x-carousel>` already reads:

```blade
<x-gallery :images="$images" />                          {{-- grid, the fallback --}}
<x-gallery masonry :columns="4" :images="$images" />
<x-gallery feature :limit="7" :images="$images" />
```

`grid` lays out uniform tiles whose shape comes from `ratio` (`square`, `video`,
`portrait`). `masonry` uses CSS multi-column, so each image keeps its natural
height; reading order runs down each column before moving to the next, which is
inherent to multi-column and the reason the CSS Grid alternative was rejected — it
would require a known aspect ratio for every image.

`feature` renders one cover above a thumbnail row. The cover is the entry flagged
`'cover' => true`, falling back to the first, the same rule `<x-carousel>` uses.
When the array holds more images than `limit`, the last thumbnail gets a `+N`
overlay where `N = count($images) - $limit`; clicking it opens the lightbox at that
image and the arrows traverse the whole array, so nothing is silently dropped.
When the array is shorter than `limit`, the row simply renders fewer tiles.

`thumbnails` moves that row beside the cover for product-page layouts:

```blade
<x-gallery feature thumbnails="left" ratio="square" :limit="5" :images="$images" class="max-w-md" />
```

Below `sm` the thumbnails always wrap under the cover, so the arrangement stays
usable on mobile.

The side column is locked to the cover's height instead of growing past it, which
is what a product page expects. It is absolutely positioned with `inset-y-0`, so it
inherits the height the cover sets, and scrolls inside with `custom-scrollbar`; the
cover reserves the space with `ml-26`/`mr-26`, the column's `w-24` plus the `gap-2`.
Raising `limit` adds scrollable thumbnails rather than a column taller than the
image beside it.

Flexbox alternatives were tried and discarded: `h-0` plus `min-h-full` on a flex
item collapses the column, because a percentage `min-height` needs a resolved
height on the parent and a flex container without an explicit height gives it
`auto` — with `overflow-y-auto` on top, the thumbnails disappear entirely.

### Added — an opt-in lightbox mirroring the Carousel

`clickable` expands a tile fullscreen through an overlay teleported to the `<body>`,
with a close button, `Esc`, backdrop click, and — with `navigable` — side arrows
and the `←`/`→` keys. `caption` renders the entry's `title`/`description` as
`overlay` or `footer`, and `without-loop` stops the traversal at both ends. Events
`expand`, `collapse`, `next` and `previous` fire on the root element.

The behaviour, the scroll-lock through `overflow()`, the overlay stacking through
`register_ui_element`/`top_ui_element`, and the teardown that releases an orphaned
lock are a deliberate mirror of `Carousel/alpine.js`. It was implemented inside
`Gallery/` rather than extracted from the Carousel: extraction would have moved the
Carousel's `clickable.*` customization blocks and broken every application
targeting them, for no gain in this release.

Without `clickable` the component emits no JavaScript at all — no `x-data`, no
teleported template. Tiles fall back to the `url`/`target` from the array, or to
plain images.

### Added — sizing through merged attributes and a `height` attribute

Attributes from the consumer are merged onto the root element, so `class`, `id`,
`style`, `data-*` and Livewire directives reach it:

```blade
<x-gallery feature ratio="square" :limit="5" :images="$images" class="max-w-md" />
```

`height` caps the tile area and scrolls inside it, taking the same enumerated
values as `<x-list>` — `40`, `60`, `80`, `96`, mapping to `max-h-40` and friends —
rather than a free CSS length, so the two components stay consistent. The wrapper
is rendered by the component and carries `custom-scrollbar`, following how every
other scrolling container in the library is styled. The `header` and `footer` slots
stay outside the scrolling region, and the lightbox is unaffected because it
teleports out of the container:

```blade
<x-gallery grid height="80" :columns="3" :images="$images" />
```

An arbitrary height is still reachable through utilities, since attributes reach
the root — but the scrollbar styling then belongs to the consumer:

```blade
<x-gallery grid :images="$images" class="custom-scrollbar max-h-64 overflow-y-auto" />
```

### Changed — no DOM virtualization; lazy loading and `content-visibility` instead

Every tile carries `loading="lazy"`, `decoding="async"` and, when the entry
supplies `width`/`height`, those attributes too, plus `content-visibility: auto`
with `contain-intrinsic-size`. The browser skips downloading off-screen images and
skips their layout and paint, which is most of what virtualization buys, with no
JavaScript and without breaking browser find or anchors.

Virtualizing the DOM was considered and rejected: it needs a known item height, so
it could only ever work in `grid` and would make the attribute inconsistent across
layouts; it forces a fixed container height with internal scrolling; it breaks
browser find, anchor deep-links and printing; it conflicts with Livewire DOM
morphing; and it makes the lightbox index map to rendered DOM rather than to the
full array.

Supplying `width`/`height` matters most in `masonry`, where no aspect-ratio class
reserves the space in advance.

### Added — validation that fails loudly on the wrong layout

Attributes passed to a layout that ignores them raise rather than being dropped,
following how `<x-carousel>` rejects `caption` without `clickable`: `columns`
cannot be used with `feature`, `limit` only with `feature`, `ratio` not with
`masonry`, `thumbnails` only with `feature`. `caption`, `navigable` and
`without-loop` all require `clickable`.

This is why the layout flags default to `null` and are never mutated. `validate()`
runs before configurations in `ManagesCompilation::compile()`, so it always sees
the raw attributes and can tell an explicitly passed value from a resolved default.
Resolving `grid` to `true` in the constructor would make `<x-gallery masonry />`
carry two layout flags by the time validation ran, and the mutual-exclusivity rule
would reject valid usage.

### Notes on internals

`layout` and the defaults for `columns`, `ratio` and `limit` resolve in
`CompileConfigurations::gallery()`, which maps them to classes through `match`
exactly as the Modal maps `size`. The feature slicing — cover, thumbnail row and
the `+N` count — lives in `GalleryRuntime`. The component constructor holds no
logic beyond normalising `images` into a zero-indexed array, and the template keeps
a single `@php` block.

The tile markup is a sub-view rendered through
`<x-dynamic-component component="ts-ui::gallery.tile" />`, the mechanism `Progress`,
`Step` and `ThemeSwitch` already use, so the tile is defined once instead of
repeated across layouts.

Customization blocks: `wrapper`, `scroll`, `height.*`, `grid.*`, `masonry.*`,
`feature.*`, `tile.*` and `lightbox.*`. The `columns` and `ratio` maps are *not*
customization blocks — numeric keys such as `grid.columns.4` would be awkward to
target and inconsistent with the rest of the library. The feature wrappers are
keyed by thumbnail position (`feature.wrapper.left`,
`feature.thumbnails.wrapper.left`), so each arrangement can be restyled on its own.

---

## Form / Upload / Async

### Added — `<x-upload.async />`, chunked uploads straight to your own controller

`<x-upload />` rides the Livewire upload pipeline, so a file has to fit inside PHP's
request limits. The new component does not: the browser slices each file and posts
the pieces to an endpoint you own, which means files around 1 GB stop being a
problem. Nothing is shared between the two beyond the namespace, and unlike the old
one this works outside Livewire too.

```blade
<x-upload.async wire:model="gallery"
                :route="route('uploads.gallery')"
                accept="image/*"
                multiple
                :limit="6"
                :max-size="512" />
```

```php
use TallStackUi\Http\AsyncUpload\Uploader;

class UploadController
{
    use Uploader;

    public function store(Request $request)
    {
        return $this->upload($request, [
            'disk' => 'public',
            'directory' => 'posts/attachments',
            'rules' => ['file' => ['mimes:jpg,png,pdf']],
        ]);
    }
}
```

The method is called once per chunk. Intermediate chunks answer `204`; the last one
assembles the file, validates it, stores it and answers `200`.

**Chunks are staged as one part file per index, joined at the end.** They are
uploaded `concurrency`-at-a-time and arrive out of order, so appending them to a
single file interleaves the payload. One `{index}.part` per chunk removes ordering
from the equation and turns "is it complete?" into a file count.

Two atomic filesystem operations carry the coordination. `mkdir()` elects the request
that fires `AsyncUploadStarted`, and renaming the staging directory elects the single
request that finalizes. Counting parts alone is not enough — two requests can observe
a complete set at the same moment.

**Staging is always local, the destination is not.** Joining the pieces needs real
paths and stream handles, which object stores do not have. The finished file then
goes wherever you name, S3 included.

**There is no global default destination directory.** A package-wide fallback would
quietly pile every upload in an application into one folder, so `directory` is
required per endpoint and the handler throws without it. `disk` stays global,
since a project usually has one uploads disk.

**The size ceiling is re-checked server side.** The `max-size` prop is feedback for
the user; a request built by hand ignores it. The handler compares the declared size
on every chunk and the assembled bytes at the end, so neither can be lied about, and
`rules` run against the real bytes rather than the mime the browser claimed.

**State binds two ways.** Through `wire:model`, honouring the `.live` modifier the
way Livewire itself does, or through a `name` attribute that generates hidden inputs
for a plain form submit. Both receive the same array:

```php
[
    ['id' => '...', 'path' => '...', 'real_name' => '...', 'size' => 0, 'mime' => '...', 'url' => '...'],
]
```

**Three Laravel events** — `AsyncUploadStarted`, `AsyncUploadCompleted` and
`AsyncUploadFailed` — cover the server side. They exist for side effects: queueing a
thumbnail, scanning, auditing. A finished upload is not a submitted form, so writing
a database row from `AsyncUploadCompleted` would orphan it; that write belongs where
the form is handled, reading the array the component synced out. There is
deliberately no per-chunk event, since a 500 MB file would fire hundreds.

**Eight Alpine events** are dispatched on the component root: `added`, `rejected`,
`start`, `progress`, `success`, `error`, `removed` and `complete`.

`chunk_size` defaults to 2 MB because that is the stock PHP `upload_max_filesize`;
anything larger has every chunk rejected before it reaches Laravel.

`tallstackui:async-upload:clear` discards staging directories idle for longer than
the `keep` setting. Nothing else collects them, so without scheduling it the staging
directory grows forever. It never touches the destination disk: telling an orphan
from a saved file there would need your database.

Full reference in `.ai/components/form/upload/async.md`.

### Changed — both upload components moved to their own bundle

`js/tallstackui-upload.js` joined the entry points carrying `<x-upload />` and
`<x-upload.async />`. The main bundle went from 56.4 kB to 47.9 kB and the new one
weighs 8.55 kB, 3.24 kB gzipped.

This is not lazy loading. The script directive emits every entry of the manifest on
every page, so the bytes a visitor downloads are the same, only split across one more
request. What it buys is cache granularity: a change to either upload component no
longer invalidates the bundle every other component lives in.

Splitting an entry made rolldown hoist a small shared runtime chunk, which the
directive already preloads along with the other underscore-prefixed chunks, and
redistributed the code shared between entries.

**No migration.** Applications load the assets through `<tallstackui:script />`,
which picks the new entry up on its own.

---

## Dialog

### Added — `Enter` confirms the dialog

A dialog could be dismissed from the keyboard but never accepted: `Escape` closed it,
and the confirm button answered only to the mouse. Anyone using the keyboard had to
reach for it, or `Tab` across to it first.

`Enter` now presses the confirm button:

| Dialog                                           | `Enter`                              |
|--------------------------------------------------|--------------------------------------|
| `success()` / `error()` / `info()` / `warning()` | closes it, like clicking **OK**      |
| `question()->confirm('Yes', 'method')`           | runs `method`, like clicking **Yes** |

The binding lives on the same root element as the `Escape` one and shares its
`top_ui` guard, so a dialog opened underneath a modal or slide does not answer for
the element on top of it.

It fires only while the focus is **outside** the dialog:

```blade
x-on:keydown.enter.window="top_ui && !$el.contains($event.target) && $refs.confirm?.click()"
```

A `<button>` already activates on `Enter` while focused, so without that guard a
dialog whose cancel button had been reached with `Tab` would cancel *and* confirm on
a single keystroke.

`Enter` works on a `persistent()` dialog, where `Escape` does not. Persistence exists
to stop a dialog from being dismissed by accident — pressing the confirm button is
the answer it is waiting for, not a way around it.

The confirm button is always present, including for the four non-question types
where it renders as a centered **OK**, so there is no dialog that `Enter` cannot
answer.

---

## Soft Customization

### Added — `extend()` to change a scope that is already defined

Scopes could only be created, never touched. That made the scopes the package
ships in `registerPredefinedScopes()` — `card-shadowless`, `stats-shadowless`,
`calendar-shadowless`, `tab-shadowless`, `table-shadowless` — read only from an
application's point of view. Calling `scope()` with the same name did not extend
the existing one, it started over from the component's original classes.

`extend()` reuses the scope instead of redefining it:

```php
TallStackUi::customize()
    ->extend(scope: 'card-shadowless')
    ->card()
    ->block('wrapper.second')
    ->append('ring-1 ring-gray-100');
```

The block keeps everything the original definition did to it — the removed
`shadow-md` stays removed, the appended border stays — and the new classes go on
top.

The scope has to exist for the component being customized. Scopes are stored per
component, so `extend(scope: 'card-shadowless')->stats()` throws: that name was
never defined for Stats.

```
InvalidArgumentException: The scope [card-shadowless] was not defined
for the component [stats] and therefore cannot be extended.
```

Requiring the scope to exist is the point of having a separate verb. `scope()`
creates and silently accepts a typo; `extend()` refuses one.

Order matters: the package's own scopes are registered in the service provider's
`boot()`, which runs before the application's providers under Laravel's default
discovery. Applications that disable discovery for TallStackUI have to make sure
their provider boots afterwards.

### Fixed — customizing the same block twice kept only the last change

Two chains touching one block did not stack. The second silently discarded the
first:

```php
TallStackUi::customize('alert')->block('wrapper')->append('from-a');
TallStackUi::customize('alert')->block('wrapper')->append('from-b');
// 3.x: 'p-4 from-b'   — from-a lost
// 4.x: 'p-4 from-a from-b'
```

Inside a single chain it already stacked, which is what made the behaviour hard
to spot: `->append('one')->append('two')` produced both. The inconsistency came
from `block()` reseeding its working copy from the component's original classes
on every call, discarding whatever earlier chains had compiled.

It now resumes from the compiled state, so a package and an application can each
customize the same block without one erasing the other. This is also what makes
`extend()` work.

**Migration.** Anything relying on the last chain winning has to be collapsed
into one chain, or the earlier customization removed. The practical case to watch
is a customization that runs more than once in the same process — it now
accumulates rather than settling on a fixed result.

### Fixed — `remove()` matched substrings instead of classes

`remove()` ran a plain `str_replace`, so removing a class also chewed through
every longer class that contained its name:

```php
// block: 'mb-2 rounded-md border border-gray-300 dark:border-dark-700'
->remove('border')
// 3.x: 'mb-2 rounded-md -gray-300 dark:-dark-700'
// 4.x: 'mb-2 rounded-md border-gray-300 dark:border-dark-700'
```

Removal now works on whitespace-separated tokens and drops only whole classes.
Passing several at once still works, either as a list or as one string:

```php
->remove(['shadow-md', 'rounded-lg'])
->remove('shadow-md rounded-lg')
```

`replace()` deliberately stays a substring operation — swapping a palette with
`->replace('gray-', 'zinc-')` depends on it. Which means `replace('rounded',
'rounded-full')` still turns `rounded-md` into `rounded-full-md`; target the full
class name when that is not what you want.

### Fixed — a shortcut chained after `block($name, $code)` was dropped

Providing the code inline and then reaching for a shortcut wrote the shortcut's
result to an empty key, and nothing reached the component:

```php
TallStackUi::customize('alert')->block('wrapper', 'p-8')->append('foo-bar');
// 3.x: 'p-8'          — the append vanished
// 4.x: 'p-8 foo-bar'
```

Calling a shortcut before any block now throws instead of writing nowhere:

```
RuntimeException: No block has been set. Call block() before
append(), prepend(), replace() or remove().
```

### Fixed — `<x-avatar.group>` could not be customized

`avatar.group` is a registered customization key with blocks of its own, but
`Customization::avatar()` took no sub-component. `customize('avatar.group')`
resolved to the plain Avatar and quietly treated `group` as a **scope name**, so
the customization compiled against the wrong component and never applied.

`avatar()` now accepts a sub-component, matching `accordion()`, `button()`,
`dial()`, `dropdown()`, `timeline()` and `wrapper()`:

```php
TallStackUi::customize()->avatar('group')->block('wrapper', '...');
TallStackUi::customize('avatar.group')->block('wrapper', '...');
```

All 78 registered customization keys now resolve to the component that declared
them.

### Fixed — an unknown sub-component became a scope instead of an error

Any dotted name whose second segment was not a real sub-component fell through to
the `$scope` parameter. `customize('badge.main')` built a scope called `main`
that no component ever reads, and reported nothing.

The segment is now rejected when the target does not accept one:

```
RuntimeException: The component [badge] does not have the sub-component [main]
```

This also fixes `customize('accordion.accordion')`, which used to fail with the
nonsensical `Component [1] is not allowed to be customized`.

### Fixed — the unknown-block error named a component you cannot pass back

The message derived the component from its Blade view name, so it said
`badge.main`. Feeding that back into `customize()` hit the bug above. It now
reports the customization key:

```
Component [badge] does not have the block [nope] to be customized. Allowed: ...
```

### Fixed — `get()` returned null for scoped customizations

Scoped blocks are stored nested under the scope name, and `get()` only looked at
the flat top level. It is now scope-aware.

### Fixed — the `square` global mangled arbitrary values and unrelated classes

The global strips border-radius utilities with a regular expression that had no
token boundaries, so it ate parts of classes it should not have touched and left
fragments behind:

| Class              | 3.x       | 4.x            |
|--------------------|-----------|----------------|
| `rounded-[10px]`   | `-[10px]` | removed        |
| `rounded-tl-[2px]` | `-[2px]`  | removed        |
| `not-rounded`      | `not-`    | `not-rounded`  |
| `unrounded-md`     | `un`      | `unrounded-md` |

It now matches whole tokens, so arbitrary values are removed cleanly and classes
that merely contain `rounded` are left alone.

### Changed — `colorful()` assigns instead of appending

`colorful()` pushed onto its list where `flash()` and `square()` assign, so
calling it twice registered duplicate entries and narrowing it never took effect:
`colorful()` followed by `colorful(toast: false)` still left Toast enabled. It now
replaces the list, matching the other two globals.

---

## Step

### Fixed — the horizontal scrollbar of the `panels` variation squared off the rounded corners

With enough steps to overflow, the `panels` variation grows a horizontal scrollbar
whose thumb ran flat into the bottom corners, flattening the radius and sitting on
top of the bottom border instead of inside the frame.

WebKit paints a scrollbar as chrome in the border box, outside the element's own
content clip, so a border-radius on the scrolling element does not shape it. The
only thing that does is an ancestor with `overflow-hidden` and the radius. The
`<ul>` carried the border, the radius **and** the scroll all at once, and its
`mb-2` pushed it clear of the `<nav>` that could otherwise have clipped it.

The frame moved out to the `<nav>`, leaving the `<ul>` as a bare scroll container:

```html
<!-- before -->
<nav class="overflow-hidden rounded-md">
    <ul class="rounded-md border border-gray-300 md:flex overflow-auto soft-scrollbar mb-2">

<!-- after -->
<nav class="overflow-hidden mb-2 rounded-md border border-gray-300">
    <ul class="md:flex overflow-auto soft-scrollbar">
```

This is the arrangement `<x-table>` already used, and the thumb now ends on the
same curve there as it does here.

The `simple` and `circles` variations are untouched — neither draws a border around
the scroll area, so neither had a corner to lose.

**Migration.** Soft customization keys kept their names but swapped roles:

| Block            | 3.x                                      | 4.x                                                           |
|------------------|------------------------------------------|---------------------------------------------------------------|
| `panels-shape`   | `rounded-md`                             | `mb-2 rounded-md border border-gray-300 dark:border-dark-700` |
| `wrapper.panels` | border, radius, bottom margin and scroll | scroll only                                                   |

Applications restyling the panels frame through `wrapper.panels` have to target
`panels-shape` instead. Moving the border back onto `wrapper.panels` brings the
artifact back.

The dead `dark:divide-dark-700` on `wrapper.panels` went away with it; the panels
list separates its items with `border-b` on `panels.li`, never with `divide-*`.

---

## Form / Select / Styled

### Fixed — grouped children were unreachable under a custom `value` key

The grouped template read children from a hardcoded `option.value` while the rest
of the component resolved them through the configured mapping. With
`select="label:name|value:id"` the dropdown rendered the group headers and nothing
else — the select opened but nothing could be picked.

The template now reads `option[selectable.value]`, matching what `_flatItems()` and
the `available` getter already did.

Note the shape this implies: the mapped `value` key carries the child list on a
group **and** the scalar value on a child, so both levels use the same key.

```php
// select="label:name|value:id"
['name' => 'Brazil', 'id' => [
    ['name' => 'São Paulo', 'id' => 4],
]]
```

### Fixed — a grouped child hid its image when it had no description

The `<img>` inside a grouped row bound its `src` to the image but gated its
visibility on the **description**. A child with an image and no description
rendered the image with `display: none`; a child with a description and no image
rendered an empty `<img>`. Visibility now follows the image, as it already did for
non-grouped rows.

The group header had the sibling problem: it read `option.image` and
`option.description` raw while the label next to it went through the mapping. Both
now respect `select`.

### Fixed — a list mixing groups and loose options dropped the grouped items

`grouped` was assigned inside the loop that normalizes options, so it kept only the
**last** option's answer. A list whose last entry was a plain option resolved to
"not grouped", the grouped branch never rendered, and every nested item vanished.
Worse, the group itself became a selectable row whose value was the whole child
array, so picking it pushed an array into a scalar `wire:model`.

The flag is now the union across all options, and it no longer overwrites an
explicit `grouped` attribute. On the JavaScript side `_flatItems()` and the
`available` getter probed only the first option for the same decision; both now
test every option.

Groups and loose options render side by side, the way `<optgroup>` and `<option>`
coexist in a native select:

```php
:options="[
    ['label' => 'Brazil', 'value' => [
        ['label' => 'São Paulo', 'value' => 4],
    ]],
    ['label' => 'Uncategorized', 'value' => 99],
]"
```

Loose rows render without the group indent and are selectable like any other item.

### Fixed — multiple grouped select closed the panel after the wrong number of picks

In multiple mode the dropdown is meant to stay open until every option has been
taken. The check compared the number of selections against `available.length`, but
for grouped options `available` holds the **groups**, not the selectable items.

With two groups of two cities each, the panel closed after the second pick and then
never closed at all:

| Pick           | selections | `available.length` | result         | expected   |
|----------------|------------|--------------------|----------------|------------|
| São Paulo      | 1          | 2                  | stays open     | stays open |
| Rio de Janeiro | 2          | 2                  | **closes**     | stays open |
| New York       | 3          | 2                  | stays open     | stays open |
| Los Angeles    | 4          | 2                  | **stays open** | closes     |

The count now comes from the flattened item list, which is what `_flatItems()`
already produces for hydration. Non-grouped selects are unaffected — `_flatItems()`
returns its input untouched when the list is not grouped.

### Changed — selected items from a grouped list are labelled `group > item`

Inside the open dropdown an item sits under its group header, so its own label is
enough to identify it. Once the dropdown closes that context disappears: a select
showing `São Paulo` no longer says which country it came from, and two groups
holding an item of the same name became indistinguishable.

Selected items are now qualified with their group:

```
single:   [ Brazil > São Paulo                          ✕ ⌵ ]

multiple: [ (Brazil > São Paulo ✕) (United States > New York ✕) ]
```

This covers the closed single-select label, the chips in multiple mode, and labels
restored on page load from `wire:model`.

Only the display changes. `wire:model` still receives the item's raw `value`, and
the rows inside the open dropdown keep showing their plain label under the group
header. Non-grouped selects are untouched.

Mechanically, `preNormalize()` now stamps each child with its group label while it
already walks the group tree, and a `display()` helper builds the qualified string
at the three points that render a selection. Because the stamp rides on the option
objects themselves, it survives `_flatItems()` and works the same for local
`:options` and remote `:request` sources.

**Migration.** The separator is fixed as ` > ` and there is no attribute to opt out.
Applications that render grouped selects in narrow containers may need to widen
them, and any test asserting the exact text of a selected grouped item has to
expect the qualified form.

---

## Form / Autocomplete

### Added — `metadata` passthrough on items

Items now accept a `metadata` key carrying arbitrary consumer data. The component
never reads, filters or renders it — it only keeps it reachable from the Alpine
`selected` state and from the `select` event payload, so the application can react
to which item was picked.

```php
[
    'value' => 'Alice',
    'description' => 'admin',
    'metadata' => ['id' => 42, 'role' => 'admin', 'team_id' => 7],
]
```

```json
[
  { "value": "Alice", "description": "admin", "metadata": { "id": 42, "role": "admin" } }
]
```

```blade
<x-autocomplete wire:model="user"
                :request="route('api.users')"
                x-on:select="$wire.userPicked($event.detail.item.metadata)" />
```

Works identically for local items (`:items`) and remote results (`:request`), since
both go through the same normalization step.

`metadata` is deliberately a namespaced bucket rather than a flat passthrough of
unknown keys. Flattening would mean that any internal key the component adds in a
future version silently overwrites consumer data.

What `metadata` does **not** do:

- it is not matched by the search filter (only `value` and `description` are);
- it is not rendered in the dropdown row;
- it is not sent to `wire:model` — the model still receives `value`.

### Fixed — documented item passthrough that never worked

The 3.x documentation described `$event.detail.item` as
`{ value, description?, image?, disabled?, ...whatever you put there }` and showed
`$event.detail.item.id` in an example. That was never true: normalization rebuilt
each item from the four known keys and dropped everything else, so any extra field
arrived as `undefined`. The docs now describe the real shape, and `metadata` is the
supported way to attach custom data.

---

## List

### Fixed — phantom divider above the first visible row after a search

Filtering a searchable list down to a row that was not the first row in the DOM
painted a spurious top border against the search row's bottom border, reading as a
single thick divider.

The dividers were keyed on an adjacent-sibling selector over `data-list-row`. Rows
are hidden with `x-show`, which sets `display: none`, and hidden elements still
participate in CSS sibling matching — so a visible row preceded only by *hidden*
rows still matched `[data-list-row] + [data-list-row]` and got a `border-t`.

Every row now also carries `data-list-on`, which Alpine drops while the row is
filtered out, and the divider is keyed on the general-sibling combinator:

```
[&>[data-list-on]~[data-list-on]]:border-t
```

`A ~ B` matches a visible row that has at least one *visible* row before it, which
is exactly "every visible row except the first visible one". No DOM-position
selector (`+`, `:not(:first-child)`, `divide-y`) can express this while hidden
siblings are still in the tree.

**Migration.** Applications overriding the `items.wrapper` block of `<x-list>` must
key their dividers on `data-list-on` rather than `data-list-row`, or the artifact
comes back.

### Added — raw content in the caption and a new `action` slot

`<x-list.items>` accepts consumer markup in two positions.

`caption` keeps working as a plain string attribute (HTML-escaped) and additionally
accepts a slot for arbitrary markup:

```blade
<x-list.items name="production">
    <x-slot:caption>
        <x-badge text="12 servers" color="red" sm />
    </x-slot:caption>
</x-list.items>
```

The new `action` slot renders controls on the right of the row without the ellipsis
dropdown chrome, and coexists with `<x-slot:menu>`:

```blade
<x-list.items name="general" caption="1 server">
    <x-slot:action>
        <x-button sm wire:click="deploy('general')">Deploy</x-button>
    </x-slot:action>
    <x-slot:menu>
        <x-dropdown.items text="Edit" wire:click="edit('general')" />
    </x-slot:menu>
</x-list.items>
```

Both are mirrored in data-driven mode through `@interact('item_caption', $item)` and
`@interact('item_action', $item)`, alongside the existing `@interact('item_menu')`.

Search still works when the caption is markup: the component registers a plain-text
projection of the slot (tags stripped, whitespace collapsed), so a caption rendered
as a badge continues to match its visible text.

**Migration.** When `action` and/or `menu` are present, both are grouped inside a new
`content.aside` wrapper (`flex shrink-0 items-center gap-x-2`). Rows that previously
rendered only a menu now carry one extra `<div>`. Applications selecting the menu
wrapper by DOM position rather than by class may need adjusting.

### Changed — `caption` excluded from the debug overlay

`caption` gained `#[SkipDebug]`, matching `menu` and `empty`, because a
`ComponentSlot` value would otherwise dump raw HTML into the debug panel. The
caption no longer appears in the overlay when `TALLSTACKUI_DEBUG_MODE` is on.

---

## Stats

### Added — optional background chart

A card can now carry a `<x-chart>` behind its content, full-bleed and dimmed, in
two forms. The array shorthand renders the chart internally and inherits the
card's `color`:

```blade
<x-stats number="45231" title="Revenue" increase :chart="[10, 40, 25, 60, 30, 80]" />
```

The slot takes over completely, for a chart that should differ from the card:

```blade
<x-stats number="45231" title="Revenue">
    <x-slot:chart>
        <x-chart :series="$revenue" color="emerald" class="h-full w-full" />
    </x-slot:chart>
</x-stats>
```

The two are mutually exclusive and throw when combined. An absent chart, an empty
array and an empty slot are all treated as no chart, and none of the positioning
classes are applied in that case.

Three new soft customization blocks:

| Block               | Purpose                                                       |
|---------------------|---------------------------------------------------------------|
| `wrapper.first-chart` | Stacking context on the card, only when a chart is present  |
| `chart.wrapper`     | The full-bleed layer: placement, clipping and opacity          |
| `chart.element`     | Sizing handed to the internal chart                            |

The layer clips itself rather than the card, so nothing a slot renders outside
the box gets cut. Two behaviours change on a charted card: it becomes the
containing block for absolutely positioned slot content, and it traps positive
`z-index` inside itself. Everything TallStackUI teleports (floating, modal,
tooltip) is unaffected.

In `solid` style the icon tile is opaque and covers the watermark behind it.

### Added — `duration` prop

Controls the count-up animation length. Defaults to `1` and is clamped to a
non-negative integer.

### Changed — number styling driven by the `number` prop and the `color` prop

Number styles are applied only through the `number` prop, leaving the default slot
free for raw markup. The `number` customization block no longer hardcodes
`text-primary-500` — the text color now follows the component's `color` prop. The
count-up animation is gated to numeric values.

Non-`href` clickable roots render as a `div` with a pointer cursor instead of an
anchor.

**Migration.** Soft customization keys changed shape:

| 3.x                                | 4.x                                   |
|------------------------------------|---------------------------------------|
| `wrapper.second-no-slot`           | removed, folded into `wrapper.second` |
| `header` / `header-string-wrapper` | `header.text` / `header.wrapper`      |
| `footer` / `footer-string-wrapper` | `footer.text` / `footer.wrapper`      |

### Added — validation for conflicting direction flags

`increase` and `decrease` can no longer be used together; doing so throws
`InvalidArgumentException` (surfaced by Blade as `ViewException`).
