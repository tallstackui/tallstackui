# TallStackUI: Signature

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 40+ Blade components for building modern web interfaces.

> **Requires Livewire:** This component must be used within a Livewire component.

A canvas-based signature pad component for capturing handwritten signatures. Supports undo/redo, clearing, exporting as an image file, and customizable pen color, background, and line width. Outputs a base64-encoded image string synced to a Livewire property.

## Basic Usage

```blade
<x-signature wire:model="signature" label="Sign here" />
```

```blade
<x-signature wire:model="signature" clearable exportable color="#1a1a1a" :height="200" />
```

```blade
<x-signature wire:model="signature" label="Signature" hint="Draw your signature above" jpeg :line="3" />
```

## Attributes

| Attribute  | Type             | Default       | Description                                                      |
|------------|------------------|---------------|------------------------------------------------------------------|
| label      | string\|null     | null          | Label text displayed above the signature pad                     |
| hint       | string\|null     | null          | Hint text displayed below the signature pad                      |
| invalidate | bool             | null          | Displays validation error state                                  |
| color      | string\|null     | '#000000'     | Pen stroke color (hex format)                                    |
| background | string\|null     | 'transparent' | Canvas background color                                          |
| line       | int\|float\|null | 2             | Pen stroke width in pixels                                       |
| height     | int\|null        | 150           | Canvas height in pixels                                          |
| jpeg       | bool             | null          | Exports the signature as JPEG instead of the default PNG         |
| clearable  | bool             | null          | Shows a clear (trash) button to erase the entire canvas          |
| exportable | bool             | null          | Shows a download button to export the signature as an image file |

## Alpine.js Events

| Event       | Description                                      |
|-------------|--------------------------------------------------|
| x-on:export | Fires when the export/download button is clicked |

## Validation Constraints

- The `line` attribute must be a number (cannot be null).
- The `height` attribute must be at least 10.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Personalization

```php
TallStackUi::personalize()
    ->signature()
    ->block('wrapper.first', 'your-tailwind-classes');
```

### Available Blocks

| Block Name     | Purpose                                                      |
|----------------|--------------------------------------------------------------|
| wrapper.first  | Outer container styles (background, border, rounded corners) |
| wrapper.second | Toolbar row layout (flex, border-bottom, padding)            |
| wrapper.button | Toolbar button group layout                                  |
| canvas.base    | Canvas element styles (border, dashed, rounded)              |
| canvas.wrapper | Canvas container padding                                     |
| icons          | Toolbar icon styles (dimensions, color)                      |
