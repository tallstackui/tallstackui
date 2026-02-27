# TallStackUI: Range

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A range slider input component with configurable sizes, color themes, and support for labels and hints. Renders as a native HTML range input with customized track and thumb styling.

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

## Attributes

| Attribute  | Type                        | Default   | Description                                     |
|------------|-----------------------------|-----------|-------------------------------------------------|
| label      | string\|ComponentSlot\|null | null      | Label text displayed above the range input      |
| hint       | string\|ComponentSlot\|null | null      | Hint text displayed below the range input       |
| sm         | bool\|null                  | null      | Sets small size for the range slider            |
| md         | bool\|null                  | null      | Sets medium size for the range slider (default) |
| lg         | bool\|null                  | null      | Sets large size for the range slider            |
| color      | string\|null                | 'primary' | Color theme for the slider thumb                |
| invalidate | bool\|null                  | null      | Prevents displaying validation error messages   |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('range')
    ->block('input.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name     | Purpose                                                   |
|----------------|-----------------------------------------------------------|
| input.wrapper  | Outer wrapper around the range input                      |
| input.base     | Core range input styles (track color, cursor, appearance) |
| input.sizes.sm | Small size: track height and thumb dimensions             |
| input.sizes.md | Medium size: track height and thumb dimensions            |
| input.sizes.lg | Large size: track height and thumb dimensions             |
| input.disabled | Styles applied when the input is disabled or readonly     |
