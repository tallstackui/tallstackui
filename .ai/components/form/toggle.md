# TallStackUI: Toggle

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A toggle switch component for boolean inputs with multiple sizes, label positioning, and color customization. Uses a checkbox input under the hood with a stylized toggle appearance.

## Basic Usage

```blade
<x-toggle wire:model="active" label="Active" />
```

```blade
<x-toggle wire:model="notifications" label="Enable notifications" color="green" position="left" />
```

```blade
<x-toggle wire:model="darkMode" label="Dark mode" lg />
```

## Attributes

| Attribute  | Type                        | Default   | Description                                                           |
|------------|-----------------------------|-----------|-----------------------------------------------------------------------|
| label      | string\|ComponentSlot\|null | null      | Label text displayed next to the toggle                               |
| xs         | string\|null                | null      | Sets extra-small size when present (any truthy value)                 |
| sm         | string\|null                | null      | Sets small size when present (any truthy value)                       |
| md         | string\|null                | null      | Sets medium size when present (any truthy value, this is the default) |
| lg         | string\|null                | null      | Sets large size when present (any truthy value)                       |
| position   | string\|null                | 'right'   | Label position relative to toggle: 'left' or 'right'                  |
| color      | string\|null                | 'primary' | Color theme for the checked state                                     |
| invalidate | bool\|null                  | null      | Prevents displaying validation error messages                         |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('toggle')
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name          | Purpose                                                               |
|---------------------|-----------------------------------------------------------------------|
| wrapper             | Outer container with flex alignment for toggle and label              |
| input.class         | Base styles for the hidden checkbox input (shape, cursor, appearance) |
| input.sizes.xs      | Input dimensions for extra-small size                                 |
| input.sizes.sm      | Input dimensions for small size                                       |
| input.sizes.md      | Input dimensions for medium size                                      |
| input.sizes.lg      | Input dimensions for large size                                       |
| background.class    | Background track styles (color, shape, cursor)                        |
| background.sizes.xs | Track dimensions for extra-small size                                 |
| background.sizes.sm | Track dimensions for small size                                       |
| background.sizes.md | Track dimensions for medium size                                      |
| background.sizes.lg | Track dimensions for large size                                       |
| error               | Error state styles applied when validation fails                      |
