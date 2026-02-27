# TallStackUI: Theme Switch

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A dark mode toggle component with two display variations: a segmented control (with light/dark/system options) and a simple toggle switch. Persists the user's theme preference and supports multiple sizes.

## Basic Usage

```blade
<x-theme-switch />
```

```blade
<x-theme-switch simple />
```

```blade
<x-theme-switch simple only-icons lg />
```

```blade
<x-theme-switch xs />
```

```blade
{{-- Full-width segmented with centered icons (useful inside dropdowns header slot) --}}
<x-theme-switch block />
```

## Attributes

| Attribute  | Type | Default | Description                                                                                              |
|------------|------|---------|----------------------------------------------------------------------------------------------------------|
| simple     | bool | false   | Uses a toggle switch instead of the segmented control                                                    |
| only-icons | bool | false   | Hides text labels in simple mode, showing only sun/moon icons (requires `simple`)                        |
| block      | bool | false   | Expands segmented control to full width with centered icons and no focus ring (segmented variation only) |
| xs         | bool | null    | Extra-small size                                                                                         |
| sm         | bool | null    | Small size                                                                                               |
| md         | bool | null    | Medium size (default)                                                                                    |
| lg         | bool | null    | Large size                                                                                               |
| xl         | bool | null    | Extra-large size                                                                                         |

## Validation Constraints

- The `only-icons` attribute requires `simple` to be enabled.
- The `block` attribute is not supported with `simple` variation.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->themeSwitch()
    ->block('segmented.wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name               | Purpose                                                        |
|--------------------------|----------------------------------------------------------------|
| wrapper                  | Transition wrapper for icon visibility                         |
| button                   | Base flex/cursor styles for the toggle button                  |
| colors.moon              | Moon icon color                                                |
| colors.sun               | Sun icon color                                                 |
| simple.wrapper           | Simple variation outer wrapper                                 |
| simple.icons.sizes.xs    | Simple icon extra-small dimensions                             |
| simple.icons.sizes.sm    | Simple icon small dimensions                                   |
| simple.icons.sizes.md    | Simple icon medium dimensions                                  |
| simple.icons.sizes.lg    | Simple icon large dimensions                                   |
| simple.icons.sizes.xl    | Simple icon extra-large dimensions                             |
| segmented.wrapper        | Segmented control container (inline-flex, rounded, background) |
| segmented.button         | Segmented button styles (cursor, rounded, padding)             |
| segmented.active         | Active segmented button state                                  |
| segmented.inactive       | Inactive segmented button state                                |
| segmented.colors.moon    | Segmented moon icon color                                      |
| segmented.colors.sun     | Segmented sun icon color                                       |
| segmented.colors.system  | Segmented system icon color                                    |
| segmented.icons.sizes.xs | Segmented icon extra-small dimensions                          |
| segmented.icons.sizes.sm | Segmented icon small dimensions                                |
| segmented.icons.sizes.md | Segmented icon medium dimensions                               |
| segmented.icons.sizes.lg | Segmented icon large dimensions                                |
| segmented.icons.sizes.xl | Segmented icon extra-large dimensions                          |
| switch.button            | Toggle switch button styles (focus ring, border, cursor)       |
| switch.wrapper           | Toggle switch knob/circle styles                               |
| switch.on                | Toggle switch "on" state background color                      |
| switch.off               | Toggle switch "off" state background color                     |
| switch.icons.sizes.xs    | Switch icon extra-small dimensions                             |
| switch.icons.sizes.sm    | Switch icon small dimensions                                   |
| switch.icons.sizes.md    | Switch icon medium dimensions                                  |
| switch.icons.sizes.lg    | Switch icon large dimensions                                   |
| switch.icons.sizes.xl    | Switch icon extra-large dimensions                             |
| switch.sizes.xs          | Switch track extra-small dimensions                            |
| switch.sizes.sm          | Switch track small dimensions                                  |
| switch.sizes.md          | Switch track medium dimensions                                 |
| switch.sizes.lg          | Switch track large dimensions                                  |
| switch.sizes.xl          | Switch track extra-large dimensions                            |
| switch.translate.xs      | Switch knob extra-small translate offset                       |
| switch.translate.sm      | Switch knob small translate offset                             |
| switch.translate.md      | Switch knob medium translate offset                            |
| switch.translate.lg      | Switch knob large translate offset                             |
| switch.translate.xl      | Switch knob extra-large translate offset                       |
