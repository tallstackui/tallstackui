# TallStackUI: Back to Top

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A floating button that appears when the user scrolls down, allowing them to smoothly scroll back to the top of the page or to a specific anchor element.

## Basic Usage

```blade
<x-back-to-top />
```

```blade
<x-back-to-top color="red" position="bottom-left" icon="arrow-up" />
```

```blade
<x-back-to-top anchor="#section-top" immediate square lg />
```

## Attributes

| Attribute | Type         | Default        | Description                                                                |
|-----------|--------------|----------------|----------------------------------------------------------------------------|
| icon      | string\|null | 'chevron-up'   | Heroicon name displayed inside the button                                  |
| color     | string\|null | 'primary'      | Color theme (e.g., primary, red, green, blue)                              |
| position  | string\|null | 'bottom-right' | Screen position: 'bottom-right' or 'bottom-left'                           |
| anchor    | string\|null | null           | CSS selector of the element to scroll to instead of the page top           |
| immediate | bool\|null   | false          | Uses instant scroll instead of smooth animation when scrolling back to top |
| square    | bool\|null   | false          | Uses rounded-lg corners instead of fully rounded                           |
| xs        | bool\|null   | null           | Extra-small size                                                           |
| sm        | bool\|null   | null           | Small size                                                                 |
| md        | bool\|null   | null           | Medium size (default)                                                      |
| lg        | bool\|null   | null           | Large size                                                                 |

Every default above except `anchor` comes from the global configuration, so the
`Default` column describes the shipped configuration rather than a value hardcoded
in the component.

## Global Configuration

```php
// config/tallstackui.php
'back-to-top' => [
    \TallStackUi\Components\BackToTop\Component::class,
    [
        'immediate' => false,
        'square' => false,
        'color' => 'primary',
        'icon' => 'chevron-up',
        'position' => 'bottom-right',
        'size' => 'md',
    ],
],
```

The inline prop always wins, including the negative form: with `'square' => true`
configured, `:square="false"` brings a single button back to the circle. The size
is chosen through the `xs`, `sm`, `md` and `lg` flags, so `size` in the
configuration only applies when none of them is present.

## Validation Constraints

- The `position` attribute must be one of: `bottom-left`, `bottom-right`.
- The `size` resolved from the flags or from the configuration must be one of: `xs`, `sm`, `md`, `lg`.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->backToTop()
    ->block('button.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name            | Purpose                                               |
|-----------------------|-------------------------------------------------------|
| button.base           | Base button styles (flex, shadow, focus ring, cursor) |
| button.sizes.xs       | Extra-small button dimensions                         |
| button.sizes.sm       | Small button dimensions                               |
| button.sizes.md       | Medium button dimensions                              |
| button.sizes.lg       | Large button dimensions                               |
| icon.sizes.xs         | Extra-small icon dimensions                           |
| icon.sizes.sm         | Small icon dimensions                                 |
| icon.sizes.md         | Medium icon dimensions                                |
| icon.sizes.lg         | Large icon dimensions                                 |
| position.bottom-left  | Fixed positioning for bottom-left placement           |
| position.bottom-right | Fixed positioning for bottom-right placement          |
