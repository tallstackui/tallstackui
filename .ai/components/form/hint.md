# TallStackUI: Hint

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A form hint component that displays helper text below a form field. Supports both an attribute-based hint string and a default slot for custom HTML content.

## Basic Usage

```blade
<x-hint hint="Enter your full legal name" />
```

```blade
<x-hint>
    Must be at least <strong>8 characters</strong> long.
</x-hint>
```

## Attributes

| Attribute | Type         | Default | Description                                                                          |
|-----------|--------------|---------|--------------------------------------------------------------------------------------|
| hint      | string\|null | null    | Hint text to display. If not provided, the default slot content is rendered instead. |

## Slots

| Slot      | Description                                                                                                |
|-----------|------------------------------------------------------------------------------------------------------------|
| (default) | Custom HTML content for the hint. Used when the `hint` attribute is not set. Rendered with unescaped HTML. |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('hint')
    ->block('text', 'your-tailwind-classes');
```

### Available Blocks

| Block Name | Purpose                                     |
|------------|---------------------------------------------|
| text       | Hint text styles (font size, color, margin) |
