# TallStackUI: Gallery

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

An image gallery component with three layouts — a uniform responsive grid, a masonry column flow, and a feature layout with one large cover above a thumbnail row. Every layout supports an opt-in lightbox that expands an image fullscreen with prev/next navigation, keyboard control, and optional captions.

## Basic Usage

```blade
<x-gallery :images="[
    ['src' => '/images/photo-1.jpg', 'alt' => 'First photo'],
    ['src' => '/images/photo-2.jpg', 'alt' => 'Second photo'],
    ['src' => '/images/photo-3.jpg', 'alt' => 'Third photo'],
]" />
```

```blade
<x-gallery masonry :columns="4" clickable navigable caption="overlay" :images="$images" />
```

## Layouts

The layout is chosen by a boolean flag. They are mutually exclusive, and `grid` applies when none is given.

```blade
<x-gallery grid :images="$images" />
<x-gallery masonry :images="$images" />
<x-gallery feature :images="$images" />
```

### Grid

Uniform tiles in a responsive grid. Tile shape comes from `ratio`, and the image is cropped with `object-cover`.

```blade
<x-gallery :images="$images" :columns="4" ratio="portrait" />
```

### Masonry

Equal-width columns where each image keeps its natural height, built on CSS multi-column. Reading order runs down each column before moving to the next: images 1, 2, 3 fill the first column, 4, 5, 6 the second. The `ratio` attribute does not apply.

```blade
<x-gallery masonry :images="$images" :columns="3" />
```

Supply `width` and `height` on each image here. Without them the layout shifts as images load, because no aspect-ratio class reserves the space in advance.

### Feature

One large cover image above a row of up to `limit - 1` thumbnails.

```blade
<x-gallery feature :images="$images" :limit="7" />
```

The cover is the image flagged `'cover' => true`, falling back to the first entry. When the array holds more images than `limit`, the last thumbnail gets a `+N` overlay where `N = count($images) - $limit`. Clicking it opens the lightbox at that image, and the arrows browse the whole array — nothing is dropped. When the array holds fewer images than `limit`, the row simply renders fewer tiles and no overlay appears.

In this layout `ratio` controls the cover image and defaults to `video`; thumbnails are always square.

#### Thumbnail position

`thumbnails` moves the thumbnail row beside the cover, which is the usual product-page arrangement. Below the `sm` breakpoint they always wrap under the cover, so the layout stays usable on mobile.

```blade
<x-gallery feature thumbnails="left" ratio="square" :limit="5" :images="$images" class="max-w-md" />
<x-gallery feature thumbnails="right" ratio="square" :limit="5" :images="$images" />
```

The side column is absolutely positioned with `inset-y-0`, so it inherits the cover's height rather than growing past it, and scrolls inside with `custom-scrollbar`. The cover reserves the space with `ml-26`/`mr-26` — the column's `w-24` plus the `gap-2`. Raising `limit` therefore adds scrollable thumbnails instead of making the column taller than the image beside it.

## Sizing and scrolling

Attributes from the consumer are merged onto the root element, so `class`, `id`, `style`, `data-*` and Livewire directives all reach it. Use a width utility to contain the component:

```blade
<x-gallery feature ratio="square" :limit="5" :images="$images" class="max-w-md" />
```

`height` caps the tile area and scrolls inside it, taking the same values as `<x-list>`: `40`, `60`, `80` or `96`, mapping to `max-h-40` and friends. The scrolling wrapper is rendered by the component and carries `custom-scrollbar`, so it matches the rest of the library without any work from the consumer. The `header` and `footer` slots stay outside the scrolling region, and the lightbox is unaffected because it teleports to the `<body>`.

```blade
<x-gallery grid height="80" :columns="3" :images="$images" />
<x-gallery masonry height="80" :columns="3" :images="$images" />
```

An arbitrary height can still be had with utilities, since attributes reach the root — but then the scrollbar styling is on you:

```blade
<x-gallery grid :images="$images" class="custom-scrollbar max-h-64 overflow-y-auto" />
```

## Attributes

| Attribute    | Type                | Default | Description                                                                   |
|--------------|---------------------|---------|-------------------------------------------------------------------------------|
| images       | Collection\|array   | —       | Required. The images to render                                                |
| grid         | bool                | false   | Uniform responsive grid. Applied when no layout flag is given                 |
| masonry      | bool                | false   | Multi-column flow with natural image heights                                  |
| feature      | bool                | false   | Large cover above a thumbnail row                                             |
| columns      | int                 | 3       | Column count (2–6) for `grid` and `masonry`                                   |
| ratio        | string              | square  | Tile shape: `square`, `video` or `portrait`. Defaults to `video` on `feature` |
| limit        | int                 | 7       | Total tiles rendered by `feature`, cover included                             |
| thumbnails   | string              | bottom  | Thumbnail position on `feature`: `bottom`, `left` or `right`                  |
| height       | string\|null        | null    | Caps the tile area and scrolls inside it: `40`, `60`, `80` or `96`            |
| clickable    | bool                | false   | Enables the lightbox                                                          |
| navigable    | bool                | false   | Prev/next buttons and arrow keys inside the lightbox                          |
| caption      | string\|null        | null    | Lightbox caption layout: `overlay` or `footer`                                |
| without-loop | bool                | false   | Stops the lightbox from wrapping around at the ends                           |
| round        | bool                | false   | Rounds the tile corners                                                       |
| header       | ComponentSlot\|null | null    | Header slot content displayed above the gallery                               |
| footer       | ComponentSlot\|null | null    | Footer slot content displayed below the gallery                               |

## Image Object Structure

| Key         | Type   | Required | Description                                             |
|-------------|--------|----------|---------------------------------------------------------|
| src         | string | Yes      | Image source URL                                        |
| alt         | string | Yes      | Alt text for accessibility                              |
| title       | string | No       | Lightbox caption title                                  |
| description | string | No       | Lightbox caption description below the title            |
| width       | int    | No       | Intrinsic width, reserving space while the image loads  |
| height      | int    | No       | Intrinsic height, reserving space while the image loads |
| url         | string | No       | Makes the tile a link. Ignored when `clickable` is set  |
| target      | string | No       | Link target (e.g., `_blank`)                            |
| cover       | bool   | No       | Marks this image as the cover in the `feature` layout   |

## Slots

| Slot   | Description                        |
|--------|------------------------------------|
| header | Content rendered above the gallery |
| footer | Content rendered below the gallery |

## Validation Constraints

- The `images` attribute is required and cannot be empty.
- The `grid`, `masonry` and `feature` flags cannot be used together.
- The `caption` must be either `overlay` or `footer`.
- The `caption`, `navigable` and `without-loop` require `clickable` to be enabled.
- The `columns` must be between 2 and 6.
- The `ratio` must be `square`, `video` or `portrait`.
- The `limit` must be at least 2.
- The `columns` cannot be used with `feature`.
- The `limit` can only be used with `feature`.
- The `ratio` cannot be used with `masonry`.
- The `thumbnails` must be `bottom`, `left` or `right`.
- The `thumbnails` can only be used with `feature`.
- The `height` must be `40`, `60`, `80` or `96`.

Attributes passed to a layout that ignores them raise an exception rather than being silently dropped.

## Clickable (Lightbox)

Add `clickable` to let users open any tile fullscreen. The lightbox renders an overlay teleported to the `<body>` (so it never gets trapped inside ancestors that create a containing block) with a close button at the top-right, and also closes on `Esc` or by clicking the dark backdrop.

```blade
<x-gallery clickable :images="$images" />
```

When `clickable` is set, each image's `url`/`target` is ignored — the click expands the image instead of navigating. Without `clickable` the component renders no JavaScript at all: tiles become plain links, or plain images when no `url` is given.

## Lightbox Caption

```blade
<x-gallery clickable caption="overlay" :images="$images" />
<x-gallery clickable caption="footer" :images="$images" />
```

`overlay` keeps the caption visually attached to the image (good for short titles); `footer` separates the caption onto its own row beneath the image (good for long descriptions).

## Lightbox Navigation

Add `navigable` together with `clickable` to browse the whole gallery from inside the lightbox, with prev/next buttons on the sides and the `←` / `→` keys.

```blade
<x-gallery clickable navigable :images="$images" />
```

By default navigation wraps around at both ends. Add `without-loop` to stop at the first and last image, disabling the button that would go past the edge.

```blade
<x-gallery clickable navigable without-loop :images="$images" />
```

## Events

When `clickable` is enabled, the root element dispatches:

| Event      | Detail               | Fired when                  |
|------------|----------------------|-----------------------------|
| `expand`   | `{ image }`          | The lightbox opens          |
| `collapse` | `{ image: null }`    | The lightbox closes         |
| `next`     | `{ current, image }` | The lightbox steps forward  |
| `previous` | `{ current, image }` | The lightbox steps backward |

```blade
<x-gallery clickable navigable :images="$images"
           x-on:expand="console.log($event.detail.image)"
           x-on:next="$wire.set('viewed', $event.detail.current)" />
```

## Performance

Every tile renders with `loading="lazy"` and `decoding="async"`, so the browser skips downloading off-screen images, and with `content-visibility: auto` plus `contain-intrinsic-size`, so it also skips their layout and paint. Tiles render as real HTML through a server-side loop rather than a client-side template, keeping images indexable and browser find working.

Supply `width` and `height` on each image to avoid layout shift, which matters most in the `masonry` layout.

The `content-visibility` declaration lives in its own customization block and can be dropped:

```php
TallStackUi::customize()->gallery()->block('tile.performance', '');
```

## Customization

```php
TallStackUi::customize()->gallery()->block('tile.rounded', 'rounded-2xl');

TallStackUi::customize()
    ->gallery()
    ->block('grid.gap')
    ->replace('gap-2', 'gap-4');

// Redefine the breakpoints behind a column count
TallStackUi::customize()->gallery()->block('grid.columns.4', 'grid-cols-1 md:grid-cols-4');
```

Available blocks: `wrapper`, `scroll`, `height.*`, `grid.*`, `masonry.*`, `feature.*`, `tile.*` and `lightbox.*`.

The feature wrappers are keyed by thumbnail position, so each arrangement can be restyled on its own:

```php
TallStackUi::customize()->gallery()->block('feature.thumbnails.wrapper.left', 'grid grid-cols-1 gap-3 sm:w-32');
```
