# TallStackUI: Stats

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A statistics card component for displaying numeric metrics with titles, icons, and trend indicators. Supports solid, light, and outline styles, animated number counting, colorized number text, and optional link/click behavior with Livewire navigation.

## Basic Usage

```blade
<x-stats number="1234" title="Total Users" icon="users" />
```

```blade
<x-stats number="89" title="Conversion Rate" icon="chart-bar" color="green" increase />
```

```blade
<x-stats number="42" title="Open Issues" color="red" outline decrease animated :duration="2" />
```

```blade
<x-stats number="567" title="Revenue" href="/reports" navigate>
    <x-slot:header>Monthly Report</x-slot:header>
    <x-slot:footer>Updated 5 min ago</x-slot:footer>
</x-stats>
```

```blade
{{-- Default slot is unstyled (free markup). Use :number for styled values. --}}
<x-stats header="Revenue" footer="This month">
    <p class="text-2xl font-bold text-primary-500">R$ 333,55</p>
</x-stats>
```

```blade
{{-- Livewire click stays a <div> (not <a>); cursor-pointer is applied --}}
<x-stats number="10" wire:click="refresh" />
```

## Attributes

| Attribute      | Type                                   | Default                   | Description                                                                                                                                |
|----------------|----------------------------------------|---------------------------|--------------------------------------------------------------------------------------------------------------------------------------------|
| number         | string\|int\|null                      | null                      | Value shown with number styles; preferred path for styled metrics                                                                          |
| title          | string\|null                           | null                      | Descriptive title above/beside the number                                                                                                  |
| icon           | ComponentSlot\|string\|null            | null                      | Heroicon name or a slot for fully custom icon markup                                                                                       |
| color          | string\|null                           | 'primary'                 | Color for icon background **and** number text (solid/light/outline palettes)                                                               |
| href           | string\|null                           | null                      | When set, root renders as `<a>` for click-through                                                                                          |
| solid          | bool                                   | true                      | Solid color style variant (default)                                                                                                        |
| light          | bool                                   | false                     | Light color style variant                                                                                                                  |
| outline        | bool                                   | false                     | Outline color style variant                                                                                                                |
| animated       | bool                                   | false                     | Count-up animation on viewport enter; **only when `number` is numeric**                                                                    |
| duration       | int\|null                              | 1                         | Animation duration in seconds (ignored when not animating)                                                                                 |
| increase       | bool                                   | false                     | Upward trend arrow on the right (mutually exclusive with `decrease`)                                                                       |
| decrease       | bool                                   | false                     | Downward trend arrow on the right (mutually exclusive with `increase`)                                                                     |
| navigate       | bool                                   | null                      | Livewire `wire:navigate` when using `href`                                                                                                 |
| navigate-hover | bool                                   | null                      | Livewire `wire:navigate.hover` when using `href`                                                                                           |
| shadowless     | bool\|null                             | null (from config: false) | Removes the wrapper shadow                                                                                                                 |
| bordered       | bool\|null                             | null (from config: false) | Adds a border to the wrapper. Combine with `shadowless` for a flat look                                                                    |
| chart          | array\|Collection\|ComponentSlot\|null | null                      | Background sparkline; the array shorthand renders `<x-chart>` at 64px and inherits `color`. Custom height or type goes in the `chart` slot |
| skeleton       | bool\|null                             | null                      | Renders a structural placeholder instead of the content. Flag only — an integer throws                                                     |

### Root element

| Condition                               | Tag     | Notes                                  |
|-----------------------------------------|---------|----------------------------------------|
| `href` filled                           | `<a>`   | Optional `navigate` / `navigate-hover` |
| `wire:click` / `x-on:click` (no `href`) | `<div>` | Still gets `cursor-pointer`            |
| Neither                                 | `<div>` | Static card                            |

## Global Configuration

```php
// config/tallstackui.php
'stats' => [
    \TallStackUi\Components\Stats\Component::class,
    [
        'shadowless' => false,
        'bordered' => false,
    ],
],
```

The inline prop always wins over the global default, so `:shadowless="false"` restores
the shadow on a single stats while the configuration keeps it off everywhere else. The
skeleton reads the same flags, so a stats configured as flat stays flat while it loads.

## Slots

| Slot      | Description                                                                                          |
|-----------|------------------------------------------------------------------------------------------------------|
| (default) | Free markup replacing the number area — **no** default number styles applied                         |
| header    | Above the body (string prop or slot); strings get muted text styles; slots keep positioning wrappers |
| footer    | Below the body (string prop or slot); same styling rules as header                                   |
| right     | Right side content (replaces increase/decrease arrow)                                                |
| icon      | Fully custom icon markup (replaces default icon rendering)                                           |
| chart     | Full-control replacement for the background layer, rendered full-bleed behind the content            |

### Background Chart

```blade
{{-- Array shorthand: renders <x-chart> internally, inheriting the card color --}}
<x-stats number="45231" title="Revenue" increase :chart="[10, 40, 25, 60, 30, 80]" />
```

```blade
{{-- Slot: full control, for a chart that should differ from the card --}}
<x-stats number="45231" title="Revenue">
    <x-slot:chart>
        <x-chart :series="$revenue" color="emerald" class="h-full w-full" />
    </x-slot:chart>
</x-stats>
```

The array shorthand pins the internal chart at 64px so the card does not inherit
the standalone chart default (240). A `height` attribute on `<x-stats>` is
ignored. To change the height, type or anything else, use the `chart` slot.

The array shorthand and the `chart` slot are mutually exclusive; combining them
throws. An absent chart, an empty array and an empty slot are all treated as no
chart, and none of the positioning classes are applied.

The layer sits at `absolute inset-0` with a negative z-index, inside a stacking
context created on the card. It clips itself rather than the card, so nothing a
slot renders outside the box gets cut.

Two behaviours change on a charted card: it becomes the containing block for
absolutely positioned slot content, and it traps positive `z-index` inside
itself. Everything TallStackUI teleports (floating, modal, tooltip) is
unaffected; only hand-rolled escaping markup is.

In `solid` style the icon tile is opaque and covers the watermark behind it.

String props `header="..."` / `footer="..."` and named slots both render with horizontal inset (`mx-2`) and muted typography for plain text.

## Skeleton

Renders a placeholder shaped like the card, for the first paint before any data
exists. Meant for the `placeholder()` of a `#[Lazy]` Livewire component.

```blade
<x-stats skeleton />
<x-stats skeleton icon="users" title="Total Users" header="Monthly" footer="Updated" />
```

The icon tile, title, header and footer are drawn only when the matching prop or
slot is present. There is nothing to count here, so `skeleton` is a flag: passing
an integer throws.

The background chart layer is deliberately omitted. It renders at
`absolute inset-0 -z-10`, taking no space in the flow, so leaving it out produces
no layout shift when the real content arrives. For a standalone placeholder chart,
see [`<x-chart skeleton>`](chart.md#skeleton).

`skeleton` is not `loading`: it stands in for content that does not exist yet,
rather than dimming content already on screen.

### Customizations Carry Over

The `skeleton.*` blocks are only the bars. Everything structural is resolved
from this component's **own, existing blocks**, because the skeleton view calls
the same `classes()` as the normal one — customization is resolved on the
component, not on the view. Whatever you already changed applies to the
placeholder too, so the box keeps matching the box it stands in for. Scopes
work the same, including when they target the placeholder alone.

Stats reuses `wrapper.first`, `wrapper.second`, `wrapper.second-no-header`, `wrapper.second-no-footer`, `slots.header.*` and `slots.footer.*`.

Blocks the placeholder does not render have nothing to act on there.
Customizing them is not an error; it simply has no effect while the skeleton
is on screen.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->stats()
    ->block('wrapper.first', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                 | Purpose                                                                   |
|----------------------------|---------------------------------------------------------------------------|
| wrapper.first              | Outer card container (flex column, rounded, shadow)                       |
| shadowless                 | Shadow reset applied when `shadowless` is set                             |
| bordered                   | Border classes applied when `bordered` is set                             |
| wrapper.first-clickable    | Cursor style when the card is clickable                                   |
| wrapper.first-chart        | Stacking context on the card, applied only when a chart is present        |
| chart.wrapper              | Full-bleed chart layer: placement, clipping and opacity                   |
| chart.element              | Sizing handed to the internal chart                                       |
| wrapper.second             | Body row (includes horizontal margin `mx-4`, flex, gap)                   |
| wrapper.second-no-header   | Extra top margin when header is absent                                    |
| wrapper.second-no-footer   | Extra bottom margin when footer is absent                                 |
| wrapper.third              | Icon container (flex, centered, rounded, dimensions)                      |
| slots.header.wrapper       | Outer inset around header                                                 |
| slots.header.text          | Header typography (string props / plain slot text)                        |
| slots.footer.wrapper       | Outer inset around footer                                                 |
| slots.footer.text          | Footer typography (string props / plain slot text)                        |
| slots.right.increase.icon  | Increase trend arrow icon name                                            |
| slots.right.increase.class | Increase trend arrow icon styles                                          |
| slots.right.decrease.icon  | Decrease trend arrow icon name                                            |
| slots.right.decrease.class | Decrease trend arrow icon styles                                          |
| icon                       | Icon dimensions inside the icon container                                 |
| title                      | Title text styles                                                         |
| number                     | Number layout styles (size/weight/leading; color comes from `color` prop) |
| skeleton.animation         | Pulse animation applied to the whole placeholder                          |
| skeleton.bar               | Base look of every placeholder bar                                        |
| skeleton.icon              | Icon tile placeholder dimensions                                          |
| skeleton.title             | Title bar dimensions                                                      |
| skeleton.number            | Number bar dimensions                                                     |
| skeleton.header            | Header bar dimensions                                                     |
| skeleton.footer            | Footer bar dimensions                                                     |

### Soft key renames (v4)

| Removed                       | Replacement                                    |
|-------------------------------|------------------------------------------------|
| `slots.header`                | `slots.header.text`                            |
| `slots.header-string-wrapper` | `slots.header.wrapper`                         |
| `slots.footer`                | `slots.footer.text`                            |
| `slots.footer-string-wrapper` | `slots.footer.wrapper`                         |
| `wrapper.second-no-slot`      | removed (`mx-4` is always on `wrapper.second`) |

### Predefined scope

`scope="stats-shadowless"` — removes card shadow and adds a light border (registered in the service provider).
The `shadowless` and `bordered` attributes produce the same look without the scope: `<x-stats shadowless bordered />`.
