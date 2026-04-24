# TallStackUI: Timeline Items

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A child component of `<x-timeline>` that renders a single event in the timeline. Provides a marker (bullet / icon / custom slot), a content block with optional title, description, and date, plus a default slot for additional rich content.

## Basic Usage

```blade
<x-timeline>
    <x-timeline.items title="Shipped v3.0" date="Apr 2026" />
</x-timeline>
```

With a description and icon marker:

```blade
<x-timeline>
    <x-timeline.items
        title="Shipped v3.0"
        description="New component architecture released."
        date="Apr 2026"
        icon="rocket-launch"
    />
</x-timeline>
```

With rich content inside the default slot:

```blade
<x-timeline>
    <x-timeline.items title="Shipped v3.0" date="Apr 2026">
        <p class="mt-2 text-sm text-gray-600">
            See the <a href="/changelog" class="underline">full changelog</a>.
        </p>
    </x-timeline.items>
</x-timeline>
```

Custom marker via slot (replaces the default bullet / icon entirely):

```blade
<x-timeline>
    <x-timeline.items title="Highlight">
        <x-slot:marker>
            <span class="text-xs font-bold">99</span>
        </x-slot:marker>
    </x-timeline.items>
</x-timeline>
```

Per-item color override (wins over the container's `color`):

```blade
<x-timeline color="primary">
    <x-timeline.items title="Default color" />
    <x-timeline.items title="Green override" color="green" />
    <x-timeline.items title="Red override" color="red" />
</x-timeline>
```

## Attributes

| Attribute   | Type                        | Default   | Description                                                                                                                |
|-------------|-----------------------------|-----------|----------------------------------------------------------------------------------------------------------------------------|
| title       | string\|null                | null      | Heading shown above the description.                                                                                       |
| description | string\|null                | null      | Body text shown under the title.                                                                                           |
| date        | string\|null                | null      | Small label rendered before the title (e.g. `"Apr 2026"`). Free-form text — not a date type.                               |
| icon        | string\|null                | null      | Heroicon name rendered as the marker instead of the default bullet.                                                        |
| color       | string\|null                | `primary` | Per-item color for the marker. Overrides the container's color.                                                            |
| marker      | ComponentSlot\|string\|null | null      | Custom marker replacement via `<x-slot:marker>` — the slot content is emitted verbatim, replacing the default bullet/icon. |

### Slot-mode propagation

The container props `horizontal`, `compact`, `style`, `alternate` do NOT propagate automatically to `<x-timeline.items>` children declared inside a slot (a Laravel `@aware` limitation for class-based components). When using slot mode, pass these props explicitly on each `<x-timeline.items>`. In array mode (`:items`), the container handles propagation internally.

Example (slot-mode horizontal):

```blade
<x-timeline horizontal>
    <x-timeline.items title="A" horizontal />
    <x-timeline.items title="B" horizontal />
</x-timeline>
```

## Slots

| Slot      | Description                                                                              |
|-----------|------------------------------------------------------------------------------------------|
| (default) | Additional content displayed below the title/description.                                |
| marker    | Replaces the bullet/icon with arbitrary HTML. When provided, the `icon` prop is ignored. |

## Marker Resolution

Precedence from highest to lowest:

1. `<x-slot:marker>` — custom HTML.
2. `icon="..."` — Heroicon rendered inside a colored wrapper.
3. Default bullet — a small filled dot tinted by the container `color` + `style`.

## Array-Mode Compatibility

When the container uses `:items`, each entry accepts these keys:

```php
$items = [
    [
        'title'       => 'Shipped v3.0',
        'description' => 'New architecture',
        'date'        => 'Apr 2026',
        'icon'        => 'rocket-launch',
        'color'       => 'emerald',
    ],
];
```

HTML-bearing values (default slot, `<x-slot:marker>`) are NOT accepted through the array — use slot mode when you need rich content.

## Soft Customization

```php
TallStackUi::customize()
    ->timeline('items')
    ->block('title', 'text-base font-bold text-gray-900 dark:text-white');
```

### Available Blocks

| Block Name                         | Purpose                                                                             |
|------------------------------------|-------------------------------------------------------------------------------------|
| wrapper.flex-vertical              | Per-item flex container (default vertical mode).                                    |
| wrapper.flex-horizontal            | Per-item flex container (default horizontal mode, marker on top).                   |
| wrapper.grid-vertical              | Per-item grid container (alternate vertical mode) — `[1fr_auto_1fr]`.               |
| wrapper.grid-horizontal            | Per-item grid container (alternate horizontal mode) — `[1fr_auto_1fr]` rows.        |
| content.base                       | Content column base classes (applied in non-alternate mode).                        |
| content.flex-vertical              | Text alignment for default vertical mode.                                           |
| content.flex-horizontal            | Text alignment for default horizontal mode.                                         |
| content.grid-vertical-normal       | Content placement for non-reversed items in alternate vertical mode (right column). |
| content.grid-vertical-reversed     | Content placement for reversed items in alternate vertical mode (left column).      |
| content.grid-horizontal-normal     | Content placement for non-reversed items in alternate horizontal mode (bottom row). |
| content.grid-horizontal-reversed   | Content placement for reversed items in alternate horizontal mode (top row).        |
| title                              | Title typography.                                                                   |
| description                        | Description typography.                                                             |
| date                               | Date label typography.                                                              |
| line.flex-vertical                 | Vertical connecting line (default mode, with gap).                                  |
| line.flex-vertical-compact         | Vertical connecting line (compact mode, no gap).                                    |
| line.flex-horizontal-left          | Left half of the horizontal connecting line (default mode, with gap).               |
| line.flex-horizontal-right         | Right half of the horizontal connecting line (default mode, with gap).              |
| line.flex-horizontal-left-compact  | Left half of the horizontal connecting line (compact mode).                         |
| line.flex-horizontal-right-compact | Right half of the horizontal connecting line (compact mode).                        |
| line.grid-vertical                 | Vertical connecting line (alternate mode, with gap).                                |
| line.grid-vertical-compact         | Vertical connecting line (alternate mode, no gap).                                  |
| line.grid-horizontal-left          | Left half of the horizontal connecting line (alternate mode, with gap).             |
| line.grid-horizontal-right         | Right half of the horizontal connecting line (alternate mode, with gap).            |
| line.grid-horizontal-left-compact  | Left half of the horizontal connecting line (alternate, compact).                   |
| line.grid-horizontal-right-compact | Right half of the horizontal connecting line (alternate, compact).                  |
| marker.wrapper                     | Marker bounding box (size + shape).                                                 |
| marker.grid-vertical               | Marker grid placement in alternate vertical mode.                                   |
| marker.grid-horizontal             | Marker grid placement in alternate horizontal mode.                                 |
| marker.bullet                      | Default bullet styling.                                                             |
| marker.icon-wrapper                | Wrapper applied when `icon` prop is used.                                           |
| marker.custom                      | Wrapper applied when `<x-slot:marker>` is used.                                     |
