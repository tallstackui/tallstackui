# TallStackUI: Environment

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A badge-like component that displays the current Laravel application environment (e.g., "Environment: Local") and optionally shows the current Git branch name. Useful for development toolbars and admin panels.

## Basic Usage

```blade
<x-environment />
```

```blade
<x-environment sm without-branch />
```

```blade
<x-environment lg round>
    <x-slot:left>
        <x-icon icon="server-stack" class="w-4 h-4 mr-1" />
    </x-slot:left>
</x-environment>
```

## Attributes

| Attribute     | Type         | Default | Description                                                                                                                                                                                                                                       |
|---------------|--------------|---------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| xs            | bool         | null    | Extra-small size (default)                                                                                                                                                                                                                        |
| sm            | bool         | null    | Small size                                                                                                                                                                                                                                        |
| md            | bool         | null    | Medium size                                                                                                                                                                                                                                       |
| lg            | bool         | null    | Large size                                                                                                                                                                                                                                        |
| square        | bool         | false   | Removes border radius for square corners                                                                                                                                                                                                          |
| round         | bool\|string | false   | When `true`, applies the historical fully-rounded (pill) shape. When set to `xs`, `sm`, `md`, `lg`, or `xl`, applies the matching `rounded-{size}` Tailwind utility. Without it, falls back to the default `rounded-md`. `square` overrides this. |
| withoutBranch | bool         | null    | Hides the Git branch name                                                                                                                                                                                                                         |

## Slots

| Slot  | Description                                                   |
|-------|---------------------------------------------------------------|
| left  | Custom content rendered before the environment text           |
| right | Custom content rendered after the environment and branch text |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->environment()
    ->block('wrapper.class', 'your-tailwind-classes');
```

### Available Blocks

| Block Name         | Purpose                                                    |
|--------------------|------------------------------------------------------------|
| wrapper.class      | Base wrapper styles (inline-flex, border, padding, font)   |
| wrapper.sizes.xs   | Extra-small text size                                      |
| wrapper.sizes.sm   | Small text size                                            |
| wrapper.sizes.md   | Medium text size                                           |
| wrapper.sizes.lg   | Large text size                                            |
| border.radius.xs   | Border radius applied when `round="xs"`                    |
| border.radius.sm   | Border radius applied when `round="sm"`                    |
| border.radius.md   | Border radius applied when `round="md"` (default fallback) |
| border.radius.lg   | Border radius applied when `round="lg"`                    |
| border.radius.xl   | Border radius applied when `round="xl"`                    |
| border.radius.full | Fully rounded (pill) shape applied when `round` is `true`  |
