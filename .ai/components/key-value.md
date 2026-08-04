# TallStackUI: Key-Value

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

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

## Attributes

| Attribute     | Type                        | Default | Description                                                                                       |
|---------------|-----------------------------|---------|---------------------------------------------------------------------------------------------------|
| label         | string\|null                | null    | Custom header label for the key column (defaults to translation)                                  |
| value         | string\|null                | null    | Custom header label for the value column (defaults to translation)                                |
| color         | string\|null                | null    | Accent for the header and the add button. Any TallStackUI color, or `black`. Without it the header is neutral gray and the button follows `primary`. |
| limit         | int\|null                   | null    | Maximum number of rows allowed                                                                    |
| static        | bool                        | null    | Makes all inputs read-only and hides the add button                                               |
| deletable     | bool                        | null    | Shows a delete button on each row                                                                 |
| delete-method | string\|null                | null    | Livewire method name to call when a row is deleted                                                |
| placeholders  | bool                        | true    | Shows placeholder text in the key and value inputs                                                |
| icon          | ComponentSlot\|string\|null | null    | Custom icon for the delete button (defaults to 'trash'), or a slot for fully custom delete markup |

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

| Block Name       | Purpose                                                      |
|------------------|--------------------------------------------------------------|
| wrapper          | Outer container styles (background, border, rounded corners) |
| header.wrapper   | Header row layout (grid, border, padding)                    |
| header.neutral   | Header text color, applied only when no `color` is given     |
| header.key       | Header key column text style                                 |
| header.value     | Header value column text style                               |
| empty.wrapper    | Empty state container (centered flex)                        |
| empty.text       | Empty state message text style                               |
| list.wrapper     | Row layout for each key-value pair                           |
| list.input.key   | Input styles for the key field                               |
| list.input.value | Input styles for the value field                             |
| button.add       | Add row button layout (full-width, border, padding)          |
| button.neutral   | Add row button color, applied only when no `color` is given  |
| button.delete    | Delete icon button styles (positioning, color)               |

## Color Personalization

```bash
php artisan tallstackui:setup-color
```

Publishes a `KeyValueColors` class with two palettes: `headerColors()` for the header text
and `buttonColors()` for the add button. Returning `null` for an entry falls back to the
bundled value, so only the colors you actually change need filling in.

Both palettes are consulted only when `color` is given. Without it the component takes
`header.neutral` and `button.neutral` from the customization blocks instead, which is why
neither has to out-specify the other.
