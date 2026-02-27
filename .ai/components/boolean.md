# TallStackUI: Boolean

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A simple boolean display component that renders a colored icon based on a true/false value. Useful for displaying status columns in tables or read-only boolean fields.

## Basic Usage

```blade
<x-boolean :boolean="$user->is_active" />
```

```blade
<x-boolean :boolean="$order->paid"
           icon-when-true="check-circle"
           icon-when-false="x-circle"
           color-when-true="green"
           color-when-false="red" />
```

```blade
<x-boolean :boolean="fn () => $item->isVerified()" />
```

## Attributes

| Attribute      | Type          | Default        | Description                                                       |
|----------------|---------------|----------------|-------------------------------------------------------------------|
| boolean        | bool\|Closure | false          | The boolean value to display; closures are resolved automatically |
| iconWhenTrue   | string\|null  | 'check-circle' | Heroicon name displayed when boolean is true                      |
| iconWhenFalse  | string\|null  | 'check-circle' | Heroicon name displayed when boolean is false                     |
| colorWhenTrue  | string\|null  | 'primary'      | Color applied to the icon when boolean is true                    |
| colorWhenFalse | string\|null  | 'gray'         | Color applied to the icon when boolean is false                   |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->boolean()
    ->block('icon', 'your-tailwind-classes');
```

### Available Blocks

| Block Name | Purpose                            |
|------------|------------------------------------|
| icon       | Icon dimensions (width and height) |
