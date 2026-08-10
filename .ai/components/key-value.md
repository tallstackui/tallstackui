# TallStackUI: Key-Value

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

> **Requires Livewire:** This component must be used within a Livewire component.

A dynamic key-value pair editor that allows users to add, edit, and remove entries. Syncs data with a Livewire property via wire:model. Supports static (read-only) mode, row limits, deletable rows, and an accent color on the header and the add button.

## Basic Usage

```blade
<x-key-value wire:model="metadata" />
```

```blade
<x-key-value wire:model="settings" label="Setting" value="Value" :limit="5" deletable />
```

```blade
<x-key-value wire:model="data" static />
```

```blade
<x-key-value wire:model="metadata" color="green" />
```

```blade
<x-key-value wire:model="metadata" colorless />
```

## Attributes

| Attribute     | Type                        | Default | Description                                                                                                                                        |
|---------------|-----------------------------|---------|----------------------------------------------------------------------------------------------------------------------------------------------------|
| label         | string\|null                | null    | Custom header label for the key column (defaults to translation)                                                                                   |
| value         | string\|null                | null    | Custom header label for the value column (defaults to translation)                                                                                 |
| color         | string\|null                | null    | Accent for the header and the add button. Any TallStackUI color, or `black`. Without it the header stays neutral and the button follows `primary`. |
| colorless     | bool\|null                  | null    | Drops the accent entirely, in light and dark. Wins over `color`.                                                                                   |
| compact       | bool                        | false   | Tightens the vertical padding of the header, the rows, the empty message and the add button                                                        |
| limit         | int\|null                   | null    | Maximum number of rows allowed                                                                                                                     |
| static        | bool                        | null    | Makes all inputs read-only and hides the add button                                                                                                |
| deletable     | bool                        | null    | Shows a delete button on each row                                                                                                                  |
| delete-method | string\|null                | null    | Livewire method name to call when a row is deleted                                                                                                 |
| placeholders  | bool                        | true    | Shows placeholder text in the key and value inputs                                                                                                 |
| icon          | ComponentSlot\|string\|null | null    | Custom icon for the delete button (defaults to 'trash'), or a slot for fully custom delete markup                                                  |

## Compact

`compact` tightens the vertical padding so more pairs fit on a screen. It reaches the
header, the rows, the empty message and the add button; the horizontal padding, the type
scale and the colors are untouched. A compact row carries the same `py-2.5` as a compact
`<x-table>` data cell, so the two share a rhythm on a page holding both.

```blade
<x-key-value wire:model="metadata" compact />
```

It works with everything else — `color`, `colorless`, `limit`, `static`, `deletable`.

`deletable` rows are the one exception: they already carry no vertical padding, because
the delete button is absolutely positioned against the row and the padding was dropped to
make room for it. `compact` still tightens the header, the empty message and the add
button on such a component.

Each affected block has a `-compact` twin, and `compact` swaps the whole string rather
than layering on top of it. Customizing `header.wrapper` therefore leaves a compact
component alone; customize `header.wrapper-compact` as well when both modes are in use.

## Slots

| Slot   | Description                                                           |
|--------|-----------------------------------------------------------------------|
| header | Custom content appended to the header row                             |
| icon   | Custom markup for the delete button (replaces the default trash icon) |

## Alpine.js Events

| Event       | Description                   |
|-------------|-------------------------------|
| x-on:add    | Fires when a new row is added |
| x-on:remove | Fires when a row is removed   |

## Validation Constraints

- The `static` and `limit` attributes cannot be used at the same time.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->keyValue()
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                           | Purpose                                                                    |
|--------------------------------------|----------------------------------------------------------------------------|
| wrapper                              | Outer container styles (background, border, rounded corners)               |
| header.wrapper                       | Header row layout (grid, border, padding)                                  |
| header.wrapper-compact               | Header used instead of `header.wrapper` under `compact`                    |
| header.neutral                       | Header text color, applied when no accent resolves                         |
| header.key                           | Header key column text style                                               |
| header.value                         | Header value column text style                                             |
| empty.wrapper                        | Empty state container (centered flex)                                      |
| empty.wrapper-compact                | Empty state used instead of `empty.wrapper` under `compact`                |
| empty.text                           | Empty state message text style                                             |
| list.wrapper                         | Row layout for each key-value pair                                         |
| list.wrapper-default-padding         | Row vertical padding, applied when `deletable` is off                      |
| list.wrapper-default-padding-compact | Row padding used instead of `list.wrapper-default-padding` under `compact` |
| list.input.key                       | Input styles for the key field                                             |
| list.input.value                     | Input styles for the value field                                           |
| button.add                           | Add row button layout (full-width, border, padding)                        |
| button.add-compact                   | Add row button used instead of `button.add` under `compact`                |
| button.neutral                       | Add row button color under `colorless`                                     |
| button.delete                        | Delete icon button styles (positioning, color)                             |

## Color Personalization

```bash
php artisan tallstackui:setup-color
```

Publishes a `KeyValueColors` class with two palettes: `headerColors()` for the header text
and `buttonColors()` for the add button. Returning `null` for an entry falls back to the
bundled value, so only the colors you actually change need filling in.

The button reads its palette whether or not `color` is given, falling back to `primary`.
The header only reads its own when a color is asked for, since it is a caption rather than
an action.

`colorless` short-circuits both, handing the header and the button back to
`header.neutral` and `button.neutral`. A color and a neutral never land on the same
element, which is why neither has to out-specify the other.
