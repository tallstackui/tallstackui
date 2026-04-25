# TallStackUI: Timeline

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A server-side component for displaying sequential events or milestones. Supports two orientations (vertical / horizontal), an optional alternating layout, Tailwind-palette colors with solid / light / outline styles, and two render modes: passing an array/Collection via `:items` OR declaring items directly via `<x-timeline.items>` slot children. No JavaScript — purely rendered on the server.

## Basic Usage

```blade
<x-timeline>
    <x-timeline.items title="v1.0" description="First release" date="Jan 2026" />
    <x-timeline.items title="v2.0" description="Second release" date="Feb 2026" />
</x-timeline>
```

Horizontal orientation:

```blade
<x-timeline horizontal>
    <x-timeline.items title="Step A" description="Initial setup" horizontal />
    <x-timeline.items title="Step B" description="Configuration" horizontal />
    <x-timeline.items title="Step C" description="Production" horizontal />
</x-timeline>
```

> **Slot-mode note:** Laravel's `@aware` directive does not reliably propagate props from class-based parents to slot children. For slot-mode timelines, pass the `horizontal` flag explicitly on each `<x-timeline.items>`. In array mode (`:items`) the container's props propagate automatically.

Items from an array / Collection:

```php
$releases = [
    ['title' => 'v1.0', 'description' => 'First release', 'date' => 'Jan 2026', 'icon' => 'rocket-launch'],
    ['title' => 'v1.1', 'description' => 'Patch', 'date' => 'Feb 2026'],
    ['title' => 'v2.0', 'description' => 'Second major', 'date' => 'Apr 2026', 'color' => 'green'],
];
```

```blade
<x-timeline :items="$releases" />
```

Alternate layout (line centered, items alternating between columns):

```blade
<x-timeline :items="$releases" alternate />
```

> Alternate mode relies on a 3-column grid that distributes the container's horizontal space (`[1fr_auto_1fr]`). The markers land at `50%` of the timeline's width. Constrain the timeline with a wrapping `<div class="max-w-*">` or a `class="max-w-*"` on `<x-timeline>` when you do not want it to fill the parent.

Color and style:

```blade
<x-timeline color="emerald" style="light">
    <x-timeline.items title="Shipped" description="Deployed to production" />
</x-timeline>
```

Compact spacing (no gap between items, continuous line):

```blade
<x-timeline compact>
    <x-timeline.items title="Step A" description="First." />
    <x-timeline.items title="Step B" description="Second." />
    <x-timeline.items title="Step C" description="Third." />
</x-timeline>
```

## Attributes

| Attribute  | Type                    | Default     | Description                                                                                                                                                                |
|------------|-------------------------|-------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| items      | array\|Collection\|null | null        | Items rendered server-side. Each entry accepts keys `title`, `description`, `date`, `icon`, `color`. When omitted, items come from slot children.                          |
| horizontal | bool\|null              | false       | When true, the timeline renders horizontally. Default is vertical.                                                                                                         |
| alternate  | bool\|null              | false       | When true, the layout switches to a 3-column (vertical) or 3-row (horizontal) grid with the line centered and items alternating between sides. Requires a container width. |
| compact    | bool\|null              | false       | Removes the gap between items so the line runs continuously, with no visible break between segments. Default is a 24px gap.                                                |
| color      | string\|null            | `'primary'` | Tailwind palette name for the markers and line. Accepts any v4 palette (primary, secondary, red, green, amber, blue, indigo, violet, pink, etc.).                          |
| style      | string\|null            | `'solid'`   | Color style variant: `'solid'`, `'light'`, or `'outline'`.                                                                                                                 |

## Slots

| Slot      | Description                                                                           |
|-----------|---------------------------------------------------------------------------------------|
| (default) | `<x-timeline.items>` children defining each event. Ignored when `:items` is provided. |

## Render Modes

The component accepts **either** `:items` (array/Collection) **or** slot children — never both. Providing both simultaneously throws `InvalidArgumentException` (wrapped as `ViewException` at render time).

### Array / Collection mode

Each entry accepts these keys:

```php
$items = collect([
    [
        'title'       => 'v3.0 released',
        'description' => 'Major rewrite',
        'date'        => 'Apr 2026',
        'icon'        => 'rocket-launch',
        'color'       => 'primary',
    ],
]);
```

Unknown keys are ignored. Raw HTML content is NOT accepted through the array — for rich content (custom markers), use slot mode.

### Slot mode

Declare items explicitly using `<x-timeline.items>` children. Slot mode unlocks `<x-slot:marker>` overrides and default content inside each item. Remember to pass `horizontal` and/or `compact` on each item when using those modes in slot form (see the slot-mode note above).

## Validation

- `style` must be one of `'solid'`, `'light'`, `'outline'` — otherwise raises `InvalidArgumentException`.
- Passing both `:items` and slot children simultaneously raises `InvalidArgumentException` (enforced in `TimelineRuntime`).

## Colors

Timeline uses the project's `ColorsThroughOf` system with a `TimelineColors` palette. Three style variants:

- `solid` — filled markers (e.g. `bg-primary-600`).
- `light` — tinted markers (e.g. `bg-primary-200`).
- `outline` — bordered markers (e.g. `border-primary-500`).

Supported palettes: `primary`, `secondary`, `black`, and the full Tailwind v4 color set (`red`, `orange`, `amber`, `yellow`, `lime`, `green`, `emerald`, `teal`, `cyan`, `sky`, `blue`, `indigo`, `violet`, `purple`, `fuchsia`, `pink`, `rose`, `slate`, `gray`, `zinc`, `neutral`, `stone`, plus the project's `mauve`, `olive`, `mist`, `taupe`).

## Soft Customization

```php
TallStackUi::customize()
    ->timeline()
    ->block('wrapper.vertical', 'flex flex-col gap-10');
```

### Scoped

```php
TallStackUi::customize('timeline', scope: 'compact')
    ->block('wrapper.vertical', 'flex flex-col gap-3');
```

```blade
<x-timeline scope="compact">
    <x-timeline.items title="Dense" />
</x-timeline>
```

### Available Blocks

| Block Name                 | Purpose                                                      |
|----------------------------|--------------------------------------------------------------|
| wrapper.vertical           | Outer flex container in vertical mode (with gap).            |
| wrapper.vertical-compact   | Outer flex container in vertical mode (no gap, `compact`).   |
| wrapper.horizontal         | Outer flex container in horizontal mode (with gap).          |
| wrapper.horizontal-compact | Outer flex container in horizontal mode (no gap, `compact`). |

See `<x-timeline.items>` for item-level customization blocks (`timeline.items` scope).
