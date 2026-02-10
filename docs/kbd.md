# Kbd

A component for displaying keyboard key hints, useful for showing shortcuts and key combinations to users.

## Basic Usage

```blade
<x-kbd text="Ctrl" />
```

Or using the slot:

```blade
<x-kbd>K</x-kbd>
```

## Key Combinations

Compose multiple `<x-kbd>` elements to display key combinations:

```blade
<x-kbd>Ctrl</x-kbd> + <x-kbd>K</x-kbd>
```

```blade
<x-kbd>⌘</x-kbd> + <x-kbd>Shift</x-kbd> + <x-kbd>P</x-kbd>
```

## Sizes

Four sizes are available. The default is `md`.

```blade
<x-kbd xs>Ctrl</x-kbd>
<x-kbd sm>Ctrl</x-kbd>
<x-kbd md>Ctrl</x-kbd>
<x-kbd lg>Ctrl</x-kbd>
```

| Prop | Text Size | Padding           |
|------|-----------|-------------------|
| `xs` | `text-xs` | `px-1.5 py-0.5`  |
| `sm` | `text-sm` | `px-2 py-0.5`    |
| `md` | `text-md` | `px-2.5 py-1`    |
| `lg` | `text-lg` | `px-3 py-1`      |

## Borderless

Remove the border and shadow while keeping the background:

```blade
<x-kbd borderless>Ctrl</x-kbd>
```

## Tooltip

Display a tooltip on hover using `x-tooltip` (Tippy.js):

```blade
<x-kbd tooltip="Open search">⌘</x-kbd> + <x-kbd tooltip="Open search">K</x-kbd>
```

## Clickable

### Link (href)

When `href` is provided, the component renders as an `<a>` tag:

```blade
<x-kbd href="https://example.com/shortcuts">?</x-kbd>
```

### Wire Click

The component detects `wire:click` and applies a clickable cursor:

```blade
<x-kbd wire:click="openShortcuts">?</x-kbd>
```

### Alpine Click

Same for Alpine `x-on:click`:

```blade
<x-kbd x-on:click="open = true">?</x-kbd>
```

## Properties

| Prop         | Type     | Default | Description                       |
|--------------|----------|---------|-----------------------------------|
| `text`       | `string` | `null`  | Key text (alternative to slot)    |
| `xs`         | `bool`   | `false` | Extra small size                  |
| `sm`         | `bool`   | `false` | Small size                        |
| `md`         | `bool`   | `true`  | Medium size (default)             |
| `lg`         | `bool`   | `false` | Large size                        |
| `borderless` | `bool`   | `false` | Remove border and shadow          |
| `href`       | `string` | `null`  | Makes the component an `<a>` link |
| `tooltip`    | `string` | `null`  | Tooltip text on hover             |

## Soft Customization

All visual blocks can be customized via the soft personalization API:

```php
TallStackUi::personalize()
    ->kbd()
    ->block('wrapper.class', '...')
    ->block('wrapper.sizes.xs', '...')
    ->block('wrapper.sizes.sm', '...')
    ->block('wrapper.sizes.md', '...')
    ->block('wrapper.sizes.lg', '...')
    ->block('borderless', '...')
    ->block('clickable', '...');
```

### Available Blocks

| Block              | Default Classes                                                                                                                                                                                |
|--------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `wrapper.class`    | `inline-flex items-center justify-center rounded-md border font-sans font-medium shadow bg-gray-100 border-gray-300 text-gray-600 dark:bg-dark-600 dark:border-dark-500 dark:text-dark-300` |
| `wrapper.sizes.xs` | `text-xs px-1.5 py-0.5 min-w-5`                                                                                                                                                             |
| `wrapper.sizes.sm` | `text-sm px-2 py-0.5 min-w-6`                                                                                                                                                               |
| `wrapper.sizes.md` | `text-md px-2.5 py-1 min-w-7`                                                                                                                                                               |
| `wrapper.sizes.lg` | `text-lg px-3 py-1 min-w-8`                                                                                                                                                                 |
| `borderless`       | `border-transparent shadow-none`                                                                                                                                                               |
| `clickable`        | `cursor-pointer hover:opacity-80 transition-opacity`                                                                                                                                           |

### Scoped Customization

```php
TallStackUi::personalize('kbd', scope: 'shortcut-hint')
    ->block('wrapper.class', 'inline-flex items-center justify-center rounded-full border font-mono font-bold shadow bg-gray-100 border-gray-300 text-gray-600 dark:bg-dark-600 dark:border-dark-500 dark:text-dark-300');
```

```blade
<x-kbd scope="shortcut-hint">⌘</x-kbd>
```
