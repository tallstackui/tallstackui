# TallStackUI: Button Circle

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A circular button component for icon-only or single-character actions. Supports solid, light, outline, and flat styles with optional loading states.

## Basic Usage

```blade
<x-button.circle icon="plus" />
```

```blade
<x-button.circle text="A" color="red" outline />
```

```blade
<x-button.circle icon="trash" color="red" light loading="delete" />
```

```blade
<x-button.circle icon="arrow-right" href="/next" lg />
```

## Attributes

| Attribute | Type         | Default   | Description                                                            |
|-----------|--------------|-----------|------------------------------------------------------------------------|
| text      | string\|null | null      | Single character or short text displayed inside the circle             |
| icon      | string\|null | null      | Heroicon name displayed inside the circle (takes precedence over text) |
| color     | string\|null | 'primary' | Color theme (e.g., primary, red, green, yellow)                        |
| href      | string\|null | null      | When set, renders as an anchor tag instead of a button                 |
| loading   | string\|null | null      | Livewire action name to show a loading spinner during execution        |
| delay     | string\|null | null      | Delay duration for the loading indicator (e.g., 'longest')             |
| xs        | string\|null | null      | Extra-small size                                                       |
| sm        | string\|null | null      | Small size                                                             |
| md        | string\|null | null      | Medium size (default)                                                  |
| lg        | string\|null | null      | Large size                                                             |
| solid     | bool         | null      | Uses the solid color style variant (default when no style set)         |
| outline   | bool         | null      | Uses the outline color style variant                                   |
| light     | bool         | false     | Uses the light color style variant                                     |
| flat      | bool         | false     | Uses the flat color style variant (no border)                          |
| submit    | bool         | false     | Sets button type to 'submit' for form submission                       |
| unfocus   | bool         | false     | Hides the focus ring when activated by mouse (kept for keyboard)       |

## Slots

| Slot      | Description                                                     |
|-----------|-----------------------------------------------------------------|
| (default) | Custom content, used when neither `icon` nor `text` is provided |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->button('circle')
    ->block('wrapper.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name       | Purpose                                                                   |
|------------------|---------------------------------------------------------------------------|
| wrapper.base     | Base circle button styles (inline-flex, rounded-full, border, transition) |
| wrapper.sizes.xs | Extra-small circle dimensions                                             |
| wrapper.sizes.sm | Small circle dimensions                                                   |
| wrapper.sizes.md | Medium circle dimensions                                                  |
| wrapper.sizes.lg | Large circle dimensions                                                   |
| icon.sizes.xs    | Extra-small icon dimensions                                               |
| icon.sizes.sm    | Small icon dimensions                                                     |
| icon.sizes.md    | Medium icon dimensions                                                    |
| icon.sizes.lg    | Large icon dimensions                                                     |
| text.sizes.xs    | Extra-small text size                                                     |
| text.sizes.sm    | Small text size                                                           |
| text.sizes.md    | Medium text size                                                          |
| text.sizes.lg    | Large text size                                                           |
