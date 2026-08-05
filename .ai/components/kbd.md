# TallStackUI: Kbd

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A keyboard key indicator component for displaying keyboard shortcuts or key references. Supports multiple sizes, borderless and shadowless modes, and optional tooltips.

## Basic Usage

```blade
<x-kbd text="Ctrl" />
```

```blade
<x-kbd text="Shift" /> + <x-kbd text="Enter" />
```

```blade
<x-kbd text="Esc" borderless tooltip="Go back" />
```

```blade
<x-kbd text="Esc" borderless shadowless />
```

## Attributes

| Attribute  | Type         | Default | Description                            |
|------------|--------------|---------|----------------------------------------|
| text       | string\|null | null    | Key label text                         |
| xs         | bool         | null    | Extra-small size                       |
| sm         | bool         | null    | Small size (default)                   |
| md         | bool         | null    | Medium size                            |
| lg         | bool         | null    | Large size                             |
| borderless | bool         | false   | Removes the border, keeping the shadow |
| shadowless | bool         | false   | Removes the shadow, keeping the border |
| tooltip    | string\|null | null    | Tooltip text shown on hover            |

Combine `borderless` and `shadowless` to strip both.

The balloon accepts the same attributes as anywhere else — `data-position`,
`data-tooltip-delay`, `data-tooltip-color` and `data-tooltip-disabled`. See
[Tooltip](tooltip.md#the-x-tooltip-directive).

```blade
<x-kbd text="⌘" tooltip="Command" data-tooltip-color="amber" data-position="bottom" />
```

## Slots

| Slot      | Description                                      |
|-----------|--------------------------------------------------|
| (default) | Custom content, used when `text` is not provided |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->kbd()
    ->block('wrapper.class', 'your-tailwind-classes');
```

### Available Blocks

| Block Name       | Purpose                                                        |
|------------------|----------------------------------------------------------------|
| wrapper.class    | Base key styles (inline-flex, rounded, border, shadow, colors) |
| wrapper.sizes.xs | Extra-small text and padding                                   |
| wrapper.sizes.sm | Small text and padding                                         |
| wrapper.sizes.md | Medium text and padding                                        |
| wrapper.sizes.lg | Large text and padding                                         |
| borderless       | Applied when borderless is active (transparent border)         |
| shadowless       | Applied when shadowless is active (no shadow)                  |
