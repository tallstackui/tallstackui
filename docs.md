# TallStackUI 4.x

Running record of everything that changed on the `4.x` branch relative to `3.x`.

Entries are grouped by component, most recently touched first. Within a component,
changes are split into **Added**, **Changed** and **Fixed**. Anything that requires
action from an upgrading application carries a **Migration** note.

Soft customization keys are part of the public API: renaming, nesting or removing a
block breaks applications that target it through `TallStackUi::customize()`. Every
such change is listed under **Migration**.

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

## Editor

### Added — `<x-editor />`, a WYSIWYG editor with no external dependency

A rich text editor built on `contenteditable`, shipping nothing but the package
itself. It outputs HTML, binds through `wire:model` or through a plain `name`,
and carries eighteen buttons across seven groups: headings, the four inline
marks, lists, indentation, alignment, code, links, images, history and
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
