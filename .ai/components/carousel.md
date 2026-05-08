# TallStackUI: Carousel

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

An image carousel/slider component with manual navigation or autoplay, optional indicators, looping, shuffle, rounded corners, an opt-in lightbox that expands the active image fullscreen, and support for image titles, descriptions, URLs, and custom header/footer slots.

## Basic Usage

```blade
<x-carousel :images="[
    ['src' => '/images/slide1.jpg', 'alt' => 'First slide'],
    ['src' => '/images/slide2.jpg', 'alt' => 'Second slide'],
    ['src' => '/images/slide3.jpg', 'alt' => 'Third slide'],
]" />
```

```blade
<x-carousel :images="[
    ['src' => '/images/hero.jpg', 'alt' => 'Hero', 'title' => 'Welcome', 'description' => 'Get started today', 'cover' => true],
    ['src' => '/images/feature.jpg', 'alt' => 'Feature', 'title' => 'New Features', 'url' => '/features', 'target' => '_blank'],
]" autoplay :interval="5" stop-on-hover round />
```

```blade
<x-carousel :images="$slides" without-loop without-indicators>
    <x-slot:header>
        <h2 class="text-xl font-bold p-4">Gallery</h2>
    </x-slot:header>
</x-carousel>
```

## Attributes

| Attribute         | Type                    | Default | Description                                                                                                                                                                                                                                                                                                                                                |
|-------------------|-------------------------|---------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| images            | Collection\|array\|null | null    | Array of image objects (required). Each must have `src` and `alt`. Optional: `title`, `description`, `url`, `target`, `cover`.                                                                                                                                                                                                                             |
| cover             | int\|null               | null    | 1-based index of the initial slide; or set `cover: true` on an image entry                                                                                                                                                                                                                                                                                 |
| autoplay          | bool                    | null    | Enables automatic slide advancement                                                                                                                                                                                                                                                                                                                        |
| interval          | int\|null               | 3       | Autoplay interval in seconds                                                                                                                                                                                                                                                                                                                               |
| withoutLoop       | bool                    | null    | Disables looping back to the first slide after the last                                                                                                                                                                                                                                                                                                    |
| withoutIndicators | bool                    | null    | Hides the bottom dot indicators                                                                                                                                                                                                                                                                                                                            |
| stopOnHover       | bool                    | null    | Pauses autoplay when the mouse hovers over the image                                                                                                                                                                                                                                                                                                       |
| round             | bool                    | null    | Applies rounded corners to images and overlay                                                                                                                                                                                                                                                                                                              |
| shuffle           | bool                    | null    | Randomizes the image order on initialization                                                                                                                                                                                                                                                                                                               |
| clickable         | bool                    | null    | Renders each slide as a button that opens the image in a fullscreen lightbox (overlay teleported to the body, closes on the X button, ESC, or click outside the image). When enabled, `url`/`target` on image entries are ignored.                                                                                                                         |
| navigable         | bool                    | null    | Adds prev/next arrow buttons and `←`/`→` keyboard shortcuts inside the lightbox so the user can step through every image without closing it. Requires `clickable`. Looping mirrors the carousel's own `withoutLoop` behavior — by default it wraps; with `withoutLoop` the buttons become disabled at the edges.                                           |
| caption           | string\|null            | null    | Renders the expanded image's `title` and `description` inside the lightbox. Accepts `overlay` (caption sits on top of the image, anchored to its bottom edge with a fade gradient) or `footer` (caption sits below the image on a separate row). Requires `clickable`. When the expanded image carries no title or description, the figcaption is skipped. |
| wrapper           | string\|null            | null    | Custom CSS class for the slide container height (overrides default `min-h-[50svh]`)                                                                                                                                                                                                                                                                        |
| header            | ComponentSlot\|null     | null    | Header slot content displayed above the carousel                                                                                                                                                                                                                                                                                                           |
| footer            | ComponentSlot\|null     | null    | Footer slot content displayed below the carousel                                                                                                                                                                                                                                                                                                           |

## Image Object Structure

| Key         | Type   | Required | Description                                 |
|-------------|--------|----------|---------------------------------------------|
| src         | string | Yes      | Image source URL                            |
| alt         | string | Yes      | Alt text for accessibility                  |
| title       | string | No       | Overlay title text                          |
| description | string | No       | Overlay description text below the title    |
| url         | string | No       | Makes the slide a clickable link            |
| target      | string | No       | Link target (e.g., `_blank`)                |
| cover       | bool   | No       | Marks this image as the initial cover slide |

## Slots

| Slot   | Description                         |
|--------|-------------------------------------|
| header | Content rendered above the carousel |
| footer | Content rendered below the carousel |

## Validation Constraints

- The `images` attribute is required and cannot be empty.
- The `caption` must be either `overlay` or `footer`.
- The `caption` requires `clickable` to be enabled.
- The `navigable` requires `clickable` to be enabled.

## Autoplay

```blade
<x-carousel :images="$images" autoplay />

<!-- Custom interval in seconds -->
<x-carousel :images="$images" autoplay interval="2" />
```

## Cover Image

Set a specific image as the initial cover:

```blade
<!-- By index (1-based) -->
<x-carousel :images="$images" :cover="2" />

<!-- Per image in the array -->
<x-carousel :images="[
    ['src' => '/img/1.jpg', 'alt' => 'Image 1'],
    ['src' => '/img/2.jpg', 'alt' => 'Image 2', 'cover' => true],
    ['src' => '/img/3.jpg', 'alt' => 'Image 3'],
]" />
```

## Clickable (Lightbox)

Add the `clickable` attribute to let users open the active image fullscreen. The lightbox renders an overlay teleported to the `<body>` (so it never gets trapped inside ancestors that create a containing block) with a close button at the top-right, and also closes on `Esc` or by clicking the dark backdrop.

```blade
<x-carousel clickable :images="[
    ['src' => '/images/photo-1.jpg', 'alt' => 'Photo 1'],
    ['src' => '/images/photo-2.jpg', 'alt' => 'Photo 2'],
]" />
```

When `clickable` is set, each slide's `url`/`target` is ignored — the click expands the image instead of navigating.

## Lightbox Caption

When the lightbox is enabled, opt into a caption layout via `caption`. The `title` and `description` from the image entry are reused.

```blade
<x-carousel clickable caption="overlay" :images="$images" />
```

```blade
<x-carousel clickable caption="footer" :images="$images" />
```

`overlay` keeps the caption visually attached to the image (good for short titles); `footer` separates the caption onto its own row beneath the image (good for long descriptions or print-style layouts).

## Lightbox Navigation

Add `navigable` together with `clickable` to let the user browse the whole gallery from inside the lightbox. The expanded view gets prev/next buttons on the sides and listens for the `←` / `→` keys.

```blade
<x-carousel clickable navigable :images="$images" />
```

```blade
<x-carousel clickable navigable caption="overlay" :images="$images" />
```

Looping behavior follows `withoutLoop`. By default the lightbox wraps from the last image back to the first; with `withoutLoop` set, the buttons become disabled at the edges.

```blade
<x-carousel clickable navigable without-loop :images="$images" />
```

When the user navigates inside the lightbox, the same `next` / `previous` events the carousel uses for its main view are dispatched, so existing listeners keep working.

The main carousel position stays in sync with the lightbox: when the user closes the overlay, the underlying carousel jumps to whichever image was last viewed in the lightbox. Opening the lightbox on image 1 and closing on image 4 leaves the main carousel showing image 4.

## Alpine.js Event Payloads

```blade
<!-- $event.detail: { current: integer, image: object } -->
<x-carousel :images="$images"
    x-on:next="alert('Navigated to the next image')"
    x-on:previous="alert('Navigated to the previous image')" />

<!-- clickable lightbox events; $event.detail: { image: object|null } -->
<x-carousel clickable :images="$images"
    x-on:expand="console.log('Opened lightbox', $event.detail.image)"
    x-on:collapse="console.log('Closed lightbox')" />
```

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->carousel()
    ->block('wrapper.first', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                                 | Purpose                                                          |
|--------------------------------------------|------------------------------------------------------------------|
| wrapper.first                              | Outer overflow container                                         |
| wrapper.second                             | Inner relative container for slides                              |
| images.wrapper.first                       | Absolute positioning for each slide                              |
| images.wrapper.second                      | Overlay container with gradient background for title/description |
| images.content.title                       | Slide title text styles                                          |
| images.content.description                 | Slide description text styles                                    |
| images.base                                | Image element base styles (object-cover, absolute positioning)   |
| buttons.left.base                          | Left navigation button styles                                    |
| buttons.left.icon.size                     | Left button icon dimensions                                      |
| buttons.right.base                         | Right navigation button styles                                   |
| buttons.right.icon.size                    | Right button icon dimensions                                     |
| indicators.wrapper                         | Bottom indicator bar container                                   |
| indicators.buttons.base                    | Individual indicator dot base styles                             |
| indicators.buttons.current                 | Active indicator dot styles                                      |
| indicators.buttons.inactive                | Inactive indicator dot styles                                    |
| clickable.trigger                          | Button wrapping each slide when `clickable` is set               |
| clickable.overlay                          | Fullscreen lightbox backdrop (teleported to body)                |
| clickable.image                            | Expanded image inside the lightbox (no caption layout)           |
| clickable.close.button                     | Lightbox close button                                            |
| clickable.close.icon                       | Lightbox close icon dimensions                                   |
| clickable.navigable.button.left.base       | Lightbox previous button (visible when `navigable` is set)       |
| clickable.navigable.button.left.icon.size  | Lightbox previous button icon dimensions                         |
| clickable.navigable.button.right.base      | Lightbox next button (visible when `navigable` is set)           |
| clickable.navigable.button.right.icon.size | Lightbox next button icon dimensions                             |
| clickable.caption.overlay.figure           | Figure container when `caption="overlay"`                        |
| clickable.caption.overlay.image            | Image styles when `caption="overlay"`                            |
| clickable.caption.overlay.wrapper          | Gradient caption wrapper anchored to the image bottom            |
| clickable.caption.overlay.title            | Title styles inside the overlay caption                          |
| clickable.caption.overlay.description      | Description styles inside the overlay caption                    |
| clickable.caption.footer.figure            | Flex column container when `caption="footer"`                    |
| clickable.caption.footer.image             | Image styles when `caption="footer"`                             |
| clickable.caption.footer.wrapper           | Caption row sitting below the image                              |
| clickable.caption.footer.title             | Title styles inside the footer caption                           |
| clickable.caption.footer.description       | Description styles inside the footer caption                     |
