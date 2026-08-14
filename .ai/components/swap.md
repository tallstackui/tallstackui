# TallStackUI: Swap

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A compact value cycler shaped like an input: a chevron button on each side and the selected value in the middle. The value changes through the buttons, through a drag over the value (mouse and touch, via pointer events) or through the keyboard arrows while either button holds focus. Options loop infinitely by default.

## Basic Usage

```blade
<x-swap wire:model="fruit" :options="['Apple', 'Banana', 'Cherry']" />
```

```blade
<x-swap label="Size" hint="Drag or use the arrows" :options="$sizes" select="label:name|value:id" />
```

```blade
<x-swap wire:model.live="month" block preview :options="$months" />
```

```blade
<x-swap wire:model="day" vertical :options="$days" />
```

## Attributes

| Attribute  | Type               | Default     | Description                                                                             |
|------------|--------------------|-------------|-----------------------------------------------------------------------------------------|
| id         | string\|null       | null        | Id applied to the value viewport                                                        |
| label      | string\|slot\|null | null        | Label rendered above the control                                                        |
| hint       | string\|slot\|null | null        | Hint rendered below the control (hidden while an error is shown)                        |
| options    | array\|Collection  | []          | Flat list, Collection or dimensional array of options                                   |
| select     | string\|null       | from config | Key remap for dimensional options, e.g. `label:name\|value:id`. Inline overrides config |
| block      | bool               | false       | Stretches the control to the full width of the parent                                   |
| preview    | bool\|null         | config      | Reveals the previous and next options at the sides with a fade out                      |
| vertical   | bool\|null         | config      | Rolls the value top-to-bottom; chevrons and drag axis follow                            |
| loop       | bool\|null         | config      | Cycles past the edges infinitely; `false` locks and disables at the ends                |
| tooltip    | string\|null       | null        | Tooltip shown over the whole control                                                    |
| invalidate | bool\|null         | null        | Suppresses the validation error feedback                                                |
| disabled   | attribute          | —           | Dims the control and freezes buttons, drag and keyboard                                 |
| readonly   | attribute          | —           | Keeps the resting look but freezes buttons, drag and keyboard                           |

`preview` and `vertical` cannot be combined — the render throws.

The model always carries the option **value**, never the index. A null model shows the first option without writing back until the user navigates. Outside Livewire the component keeps a hidden input in sync through `name` and pairs with Alpine's `x-model` through `x-modelable`.

## Options

```blade
{{-- flat --}}
<x-swap :options="['Apple', 'Banana']" />

{{-- Collection --}}
<x-swap :options="collect(['Apple', 'Banana'])" />

{{-- dimensional, default keys --}}
<x-swap :options="[['label' => 'Small', 'value' => 1], ['label' => 'Large', 'value' => 2]]" />

{{-- dimensional, custom keys --}}
<x-swap :options="[['name' => 'Small', 'id' => 1]]" select="label:name|value:id" />
```

Dimensional options missing the resolved label or value key throw a `ViewException`.

## Alpine.js Events

| Event     | Description                                                                     |
|-----------|---------------------------------------------------------------------------------|
| x-on:swap | Fires on every navigation with `{ value, label, index, direction }` in `detail` |

`direction` is `next` or `prev`.

```blade
<x-swap :options="$options" x-on:swap="console.log($event.detail)" />
```

## Livewire Integration

```blade
<x-swap wire:model="fruit" :options="['Apple', 'Banana']" />
<x-swap wire:model.live="fruit" :options="['Apple', 'Banana']" />
<x-swap wire:model="fruit" wire:change="fruitChanged" :options="['Apple', 'Banana']" />
```

`wire:change` calls the Livewire method with the new value, like the other form components.

## Global Configuration

```php
// config/tallstackui.php
'swap' => [
    \TallStackUi\Components\Swap\Component::class,
    [
        'preview' => false,
        'vertical' => false,
        'loop' => true,
        'select' => null,
    ],
],
```

The inline prop always wins over the global default. `select` takes the same
string syntax as the attribute, so a project whose options always come as
`name`/`id` can set the remap once instead of repeating it at every call site.

## Behavior Notes

- The track slides on `transform` with a 300ms ease-out transition, suspended while dragging so the value follows the pointer 1:1; releasing snaps to the nearest option, and a long gesture can cross several options at once.
- Looping is implemented with edge clones: crossing an edge animates into a clone of the opposite end and silently teleports to the real option. With `:loop="false"` the matching button disables at either end and the drag gains rubber band resistance.
- `preview` splits the viewport in thirds — the previous and next options stay visible whole at reduced opacity, fading toward the edges through a CSS mask — and widens the default width.
- The middle value is intentionally not focusable: Tab stops only on the buttons, and the arrow keys work while either button holds focus (up/down when `vertical`).
- `globals()->flash()` removes the track and fade transitions, turning every navigation into an instant jump.

## Soft Customization

```php
TallStackUi::customize()
    ->swap()
    ->block('viewport.width.base', 'w-40');
```

### Available Blocks

| Block      | Purpose                                                              |
|------------|----------------------------------------------------------------------|
| wrapper    | Outer wrapper handed to the input wrapper                            |
| input.*    | The input-like shell (base, color, background, error, block, locked) |
| button.*   | The chevron buttons (base, icon)                                     |
| viewport.* | The value viewport (base, draggable, mask, touch.*, width.*)         |
| track.*    | The sliding track (base, transition, vertical)                       |
| item.*     | Each option (base, fade.*, sizes.*, transition)                      |
