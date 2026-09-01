# TallStackUI: Range

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A range slider input component with configurable sizes, color themes, and support for labels and hints. Renders as a native HTML range input with customized track and thumb styling.

The `dual` attribute turns it into an interval picker with two thumbs. See [Dual mode](#dual-mode).

## Basic Usage

```blade
<x-range wire:model="volume" label="Volume" hint="Adjust the volume level" />
```

```blade
<x-range wire:model="brightness" label="Brightness" color="amber" lg />
```

```blade
<x-range wire:model="opacity" label="Opacity" sm min="0" max="100" />
```

## Dual Mode

Two native range inputs are stacked over a shared track, so keyboard support, ARIA semantics and touch behaviour come from the platform. The segment between the thumbs is painted with the component colour.

Unlike the single mode, it binds to an array of exactly two values.

```blade
<x-range dual wire:model="price" :min="0" :max="1000" :step="10" label="Price range" />
```

```php
public array $price = [200, 800];
```

The bound property is always an indexed pair, `[start, end]`.

```blade
<x-range dual wire:model="price" :min="0" :max="1000" color="emerald" tooltip lg />
```

`tooltip` shows the value of the thumb being dragged and hides it on release. The bubble is not a component of its own: it is the same balloon the [Tooltip](../tooltip.md) component and the `x-tooltip` directive render, anchored on an invisible marker kept over the thumb. It therefore follows the tooltip [global configuration](../tooltip.md#global-configuration) (`color`, `size`, `invert`, `flash`) and the `[data-tsui-tooltip]` styling, and has no color or size settings of its own.

Outside Livewire, `value` sets the initial pair and `name` is forwarded to both inputs. Use the array form so the browser submits them both:

```blade
<form method="GET" action="{{ route('products.index') }}">
    <x-range dual name="price[]" :value="[200, 800]" :min="0" :max="1000" />
    <x-button submit text="Filter" />
</form>
```

The thumbs never cross. Dragging the starting thumb past the ending one stops it at that value, and the other way around. Both landing on the same value is allowed.

While the pair is collapsed the two thumbs overlap, so the one that still has room to move is stacked on top: the ending thumb near the minimum, the starting thumb near the maximum. Neither gets stuck.

The pair reaches the server on `change` — when the thumb is released — rather than on every intermediate value. A drag therefore costs a single round trip, `wire:model.live` included.

## Attributes

| Attribute  | Type                            | Default   | Description                                                                          |
|------------|---------------------------------|-----------|--------------------------------------------------------------------------------------|
| label      | string\|ComponentSlot\|null     | null      | Label text displayed above the range input                                           |
| hint       | string\|ComponentSlot\|null     | null      | Hint text displayed below the range input                                            |
| dual       | bool\|null                      | null      | Renders two thumbs, binding to a pair                                                |
| min        | int\|float\|null                | null      | Lower boundary. Defaults to 0 in dual mode.                                          |
| max        | int\|float\|null                | null      | Upper boundary. Defaults to 100 in dual mode.                                        |
| step       | int\|float\|null                | null      | Increment. Defaults to 1 in dual mode.                                               |
| value      | array\|int\|float\|string\|null | null      | Initial value, or pair in dual mode                                                  |
| tooltip    | bool\|null                      | null      | Shows the dragged value above the thumb through the page tooltip balloon. Dual only. |
| sm         | bool\|null                      | null      | Sets small size for the range slider                                                 |
| md         | bool\|null                      | null      | Sets medium size for the range slider (default)                                      |
| lg         | bool\|null                      | null      | Sets large size for the range slider                                                 |
| color      | string\|null                    | 'primary' | Colour of the thumb and of the filled track                                          |
| invalidate | bool\|null                      | null      | Prevents displaying validation error messages                                        |
| disabled   | bool                            | false     | Locks the slider. The value is not submitted.                                        |
| readonly   | bool                            | false     | Locks the slider. The value is still submitted.                                      |

`tooltip` and an array `value` both require `dual` and throw otherwise.

In dual mode the component also throws when `min` is not below `max`, when `step` is zero, negative or wider than the distance between the boundaries, or when the bound value is not a numeric pair inside those boundaries with the first value below the second.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('range')
    ->block('input.base', 'your-tailwind-classes');
```

### Available Blocks

The `dual.*` blocks are namespaced because the same names mean opposite things across the modes: the input is the whole visible control when single, and a transparent layer over the track when dual.

| Block Name            | Purpose                                                         |
|-----------------------|-----------------------------------------------------------------|
| input.wrapper         | Outer wrapper around the range input                            |
| input.base            | Core range input styles (track color, cursor, appearance)       |
| input.sizes.sm        | Small size: track height and thumb dimensions                   |
| input.sizes.md        | Medium size: track height and thumb dimensions                  |
| input.sizes.lg        | Large size: track height and thumb dimensions                   |
| input.locked          | Styles applied when the input is disabled or readonly           |
| dual.wrapper.base     | Wrapper the track and both inputs are positioned against        |
| dual.wrapper.sizes.sm | Small size: wrapper height                                      |
| dual.wrapper.sizes.md | Medium size: wrapper height                                     |
| dual.wrapper.sizes.lg | Large size: wrapper height                                      |
| dual.wrapper.locked   | Styles applied when the slider is disabled or readonly          |
| dual.track.base       | Unfilled track behind both thumbs                               |
| dual.track.progress   | Filled segment between the thumbs                               |
| dual.track.sizes.sm   | Small size: track height                                        |
| dual.track.sizes.md   | Medium size: track height                                       |
| dual.track.sizes.lg   | Large size: track height                                        |
| dual.input.base       | Shared styles of both native inputs, whose own track is hidden  |
| dual.input.sizes.sm   | Small size: thumb dimensions                                    |
| dual.input.sizes.md   | Medium size: thumb dimensions                                   |
| dual.input.sizes.lg   | Large size: thumb dimensions                                    |
| dual.tooltip.anchor   | Invisible marker kept over the thumb, the balloon anchors on it |
