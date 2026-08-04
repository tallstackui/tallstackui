# TallStackUI: Button

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A versatile button component supporting solid, light, outline, and flat styles with optional icons, loading states, tooltips, and link behavior.

## Basic Usage

```blade
<x-button text="Save" />
```

```blade
<x-button text="Delete" color="red" outline icon="trash" />
```

```blade
<x-button text="Processing..." loading="save" color="green" />
```

```blade
<x-button text="Visit Site" href="https://example.com" flat />
```

Controlling the border radius. `round` on its own gives a pill; with a size it gives
that exact radius. `square` drops the radius altogether and wins over `round`:

```blade
<x-button text="Default" />           {{-- rounded-md --}}
<x-button text="Pill" round />        {{-- rounded-full --}}
<x-button text="Large" round="lg" />  {{-- rounded-lg --}}
<x-button text="Sharp" square />      {{-- no radius --}}
```

| Value          | Class          |
|----------------|----------------|
| (none)         | `rounded-md`   |
| `round`        | `rounded-full` |
| `round="xs"`   | `rounded-xs`   |
| `round="sm"`   | `rounded-sm`   |
| `round="md"`   | `rounded-md`   |
| `round="lg"`   | `rounded-lg`   |
| `round="xl"`   | `rounded-xl`   |
| `round="full"` | `rounded-full` |

## Attributes

| Attribute | Type               | Default   | Description                                                                                                                            |
|-----------|--------------------|-----------|----------------------------------------------------------------------------------------------------------------------------------------|
| text      | string\|null       | null      | Button label text                                                                                                                      |
| icon      | string\|null       | null      | Heroicon name displayed alongside the text                                                                                             |
| position  | string\|null       | 'left'    | Icon position relative to text: 'left' or 'right'                                                                                      |
| xs        | bool               | null      | Extra-small size                                                                                                                       |
| sm        | bool               | null      | Small size                                                                                                                             |
| md        | bool               | null      | Medium size (default)                                                                                                                  |
| lg        | bool               | null      | Large size                                                                                                                             |
| color     | string\|null       | 'primary' | Color theme (e.g., primary, red, green, yellow)                                                                                        |
| square    | string\|null       | null      | Removes border radius for square corners. Wins over `round`                                                                            |
| round     | bool\|string\|null | false     | `true` gives a pill (`rounded-full`). A size (xs, sm, md, lg, xl, full) gives that exact radius. Defaults to `rounded-md` when omitted |
| block     | bool               | false     | Expands button to full width (`w-full`)                                                                                                |
| href      | string\|null       | null      | When set, renders as an anchor tag instead of a button                                                                                 |
| loading   | string\|null       | null      | Livewire action name to show a loading spinner during execution                                                                        |
| delay     | string\|null       | null      | Delay duration for the loading indicator (e.g., 'longest')                                                                             |
| solid     | bool               | true      | Uses the solid color style variant (default)                                                                                           |
| outline   | bool               | false     | Uses the outline color style variant                                                                                                   |
| light     | bool               | false     | Uses the light color style variant                                                                                                     |
| flat      | bool               | false     | Uses the flat color style variant (no border)                                                                                          |
| submit    | bool               | false     | Sets button type to 'submit' for form submission                                                                                       |
| unfocus   | bool               | false     | No focus on mouse click (no ring/color); keyboard focus kept                                                                           |
| tooltip   | string\|null       | null      | Tooltip text shown on hover                                                                                                            |

The balloon accepts the same attributes as anywhere else — `data-position`,
`data-tooltip-delay`, `data-tooltip-color` and `data-tooltip-disabled`. See
[Tooltip](../tooltip.md#the-x-tooltip-directive).

```blade
<x-button text="Save" tooltip="Saves and closes" data-tooltip-delay="flash" />
```

## Slots

| Slot      | Description                                                           |
|-----------|-----------------------------------------------------------------------|
| (default) | Custom content, used when `text` is not provided                      |
| left      | Custom HTML rendered before the text (overrides left-positioned icon) |
| right     | Custom HTML rendered after the text (overrides right-positioned icon) |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->button()
    ->block('wrapper.class', 'your-tailwind-classes');
```

### Available Blocks

| Block Name             | Purpose                                                             |
|------------------------|---------------------------------------------------------------------|
| wrapper.class          | Base button styles (inline-flex, border, transition, cursor states) |
| wrapper.sizes.xs       | Extra-small text and padding                                        |
| wrapper.sizes.sm       | Small text and padding                                              |
| wrapper.sizes.md       | Medium text and padding                                             |
| wrapper.sizes.lg       | Large text and padding                                              |
| wrapper.block          | Full-width class applied by `block`                                 |
| border.radius.xs       | Radius applied by `round="xs"`                                      |
| border.radius.sm       | Radius applied by `round="sm"`                                      |
| border.radius.md       | Radius applied by `round="md"` and by default                       |
| border.radius.lg       | Radius applied by `round="lg"`                                      |
| border.radius.xl       | Radius applied by `round="xl"`                                      |
| border.radius.full     | Radius applied by `round` and by `round="full"`                     |
| wire.loading-cursor    | Cursor applied while a `loading` action runs                        |
| icon.sizes.xs          | Extra-small icon dimensions                                         |
| icon.sizes.sm          | Small icon dimensions                                               |
| icon.sizes.md          | Medium icon dimensions                                              |
| icon.sizes.lg          | Large icon dimensions                                               |
| icon.spinner-animation | Spin animation applied to the loading icon                          |
