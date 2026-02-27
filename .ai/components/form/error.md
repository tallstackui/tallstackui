# TallStackUI: Error

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A form error message component that displays the first validation error for a given property. Renders only when a validation error exists for the specified property name.

## Basic Usage

```blade
<x-error property="email" />
```

```blade
<x-input wire:model="name" label="Name" />
<x-error property="name" />
```

## Attributes

| Attribute | Type         | Default | Description                                              |
|-----------|--------------|---------|----------------------------------------------------------|
| property  | string\|null | null    | The name of the validated property to display errors for |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('error')
    ->block('text', 'your-tailwind-classes');
```

### Available Blocks

| Block Name | Purpose                                              |
|------------|------------------------------------------------------|
| text       | Error message text styles (font size, weight, color) |
