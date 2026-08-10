# TallStackUI: Floating

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

> **Internal Component:** This component is used internally by other TallStackUI components (such as Dropdown and Submenu) and is not typically used directly.

A floating panel utility component powered by Alpine.js `x-anchor` for positioning relative to a reference element. Provides automatic show/hide behavior with click-outside and Escape key dismissal, plus configurable transitions and positioning.

## Basic Usage

```blade
<div x-data="{ show: false }">
    <button x-ref="anchor" x-on:click="show = !show">Toggle</button>

    <x-floating x-show="show" x-anchor="$refs.anchor" position="bottom-start" offset="8">
        <div class="p-4">Floating content</div>
    </x-floating>
</div>
```

## Attributes

| Attribute  | Type                | Default      | Description                                                       |
|------------|---------------------|--------------|-------------------------------------------------------------------|
| offset     | string\|null        | '10'         | Distance in pixels between the anchor and the floating panel      |
| position   | string\|null        | 'bottom-end' | Anchor position relative to the reference element. See Position   |
| transition | ComponentSlot\|null | null         | Custom transition slot to override default enter/leave animations |
| footer     | ComponentSlot\|null | null         | Footer content slot                                               |

## Slots

| Slot       | Description                                 |
|------------|---------------------------------------------|
| (default)  | Main content of the floating panel          |
| transition | Custom Alpine.js transition directives      |
| footer     | Footer content rendered after the main slot |

## Configuration

In `config/tallstackui.php`, at the top level — not under `components`:

| Option                 | Type | Default | Description                                               |
|------------------------|------|---------|-----------------------------------------------------------|
| `floating_scroll_lock` | bool | false   | When true, locks the page scroll while a floating is open |

The lock is the same one modals and slides use: `overflow: hidden` plus a
compensating `padding-right` on the `<body>`. Because every consumer renders this
component, the flag reaches all of them at once:

Dropdown, Dropdown Submenu, Autocomplete, Color, Date, Password, Select Styled,
Time, Upload and Calendar, plus the List Items menu.

There is no per-instance opt out. The flag is off by default, and the
compensating `padding-right` shifts the layout on every open — noticeable on a
small dropdown in a way it is not on a modal.

### Reference counting

Nested and stacked floatings share a single lock, tracked in
`window.__tsui_floating_locks`. The first to open takes it and only the last to
close gives it back, so a Dropdown Submenu opening inside its parent adds a
reference instead of re-locking, and closing it does not unlock the body while
the parent is still open.

The lock is also released when a floating is torn out of the DOM while open (a
Livewire morph, a collapsing `@if`) and when its anchor leaves layout (a Tab
swap, an Accordion collapse), neither of which runs a normal close.

A floating opened inside a Modal or a Slide never takes the lock: the overlay
already owns it, and only whoever wrote the `data-overflow` marker on the
`<body>` is allowed to clear it.

Floatings are deliberately kept out of `window.__tsui_elements`, the registry that
decides which overlay owns click-outside, so an open dropdown does not take that
away from the modal behind it.

## Escape

`Escape` closes the panel, and an open panel owns that press: pressing it inside a
Modal or a Slide closes the popup and leaves the overlay open, so a form in progress
survives. A second press then closes the overlay.

Ownership is tracked in `window.__tsui_floating_open`, a registry separate from
`__tsui_elements` for the reason above. The open panel also marks the event itself.
Both halves are needed because every listener sits on `window` and runs in
registration order: an overlay running before the panel sees it still open in the
registry, and one running after sees the mark left on the event.

## Position

`position` accepts the twelve concrete placements, `top`, `bottom`, `left` and
`right`, each optionally suffixed with `-start` or `-end`.

`auto`, `auto-start` and `auto-end` are accepted for compatibility with Tooltip and
Reaction, which resolve them through a different engine. Here they resolve to
`bottom`, `bottom-start` and `bottom-end`, because Alpine's anchor plugin only knows
the concrete twelve. Prefer naming the placement you want.

## Matching the anchor width

The panel is teleported to the end of `<body>`, so it cannot inherit its anchor's width
and sizes to its own content by default — a list of short options comes out narrow.

Adding `w-full` to the panel opts into the width sync:

```blade
<x-floating class="w-full">
```

The class doubles as the switch. Nothing reads it as CSS; the component checks for it and
then writes the anchor's `offsetWidth` onto the panel, re-applying it on every open, on a
`MutationObserver` over the panel content, and on Livewire's `commit` hook — so the width
survives a round trip that replaces the teleported node while the panel is open.

`Form/Select/Styled`, `Form/Autocomplete` and `Form/Tag` carry it in their
`floating.class` block. Customizing that block without keeping `w-full` silently drops the
sync and the panel starts sizing to its longest option.

Leave it off for panels that should size to their content, such as `Dropdown`.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->floating()
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name | Purpose                                                                        |
|------------|--------------------------------------------------------------------------------|
| wrapper    | Floating panel container with background, border, rounded corners, and z-index |
