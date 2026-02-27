# TallStackUI: Tooltip

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A tooltip icon component that displays informational text on hover using Tippy.js. Renders an icon (default: question mark circle) that shows a positioned tooltip with custom text content.

## Basic Usage

```blade
<x-tooltip text="This field is required." />
```

```blade
<x-tooltip text="Click to save changes" icon="information-circle" color="blue" position="right" />
```

```blade
<x-tooltip text="Warning: this action is irreversible" color="red" lg position="bottom" />
```

## Attributes

| Attribute | Type         | Default                | Description                                               |
|-----------|--------------|------------------------|-----------------------------------------------------------|
| text      | string\|null | null                   | Tooltip text content displayed on hover                   |
| icon      | string\|null | 'question-mark-circle' | Heroicon name used as the tooltip trigger                 |
| color     | string       | 'primary'              | Color theme for the icon (e.g., primary, red, blue, gray) |
| xs        | bool\|null   | null                   | Extra-small icon size                                     |
| sm        | bool\|null   | null                   | Small icon size (default)                                 |
| md        | bool\|null   | null                   | Medium icon size                                          |
| lg        | bool\|null   | null                   | Large icon size                                           |
| position  | string\|null | 'top'                  | Tooltip position relative to the icon                     |

## Allowed Positions

`auto`, `auto-start`, `auto-end`, `top`, `top-start`, `top-end`, `bottom`, `bottom-start`, `bottom-end`, `left`, `left-start`, `left-end`, `right`, `right-start`, `right-end`

## Validation Constraints

- The `position` must be one of the allowed positions listed above.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->tooltip()
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name | Purpose                     |
|------------|-----------------------------|
| wrapper    | Outer inline-flex container |
| sizes.xs   | Extra-small icon dimensions |
| sizes.sm   | Small icon dimensions       |
| sizes.md   | Medium icon dimensions      |
| sizes.lg   | Large icon dimensions       |
