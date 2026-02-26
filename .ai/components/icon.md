# TallStackUI: Icon

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

An icon component that renders SVG icons from Heroicons (built-in) or BladeUI icon packages. Supports left/right text or content alongside the icon and an error state that applies red coloring.

## Basic Usage

```blade
<x-icon icon="home" class="h-5 w-5" />
```

```blade
<x-icon icon="check-circle" class="h-6 w-6 text-green-500" />
```

```blade
<x-icon icon="exclamation-circle" class="h-5 w-5" error />
```

```blade
<x-icon icon="arrow-right" class="h-4 w-4">
    <x-slot:left>Next</x-slot:left>
</x-icon>
```

## Attributes

| Attribute | Type          | Default      | Description                                                                  |                                                         |
|-----------|---------------|--------------|------------------------------------------------------------------------------|---------------------------------------------------------|
| icon      | string\|null  | null         | The icon name (e.g., `home`, `check-circle`, `arrow-right`)                  |                                                         |
| name      | string\|null  | null         | Alternative to `icon` for specifying the icon name                           |                                                         |
| error     | bool          | false        | Applies red error color styling to the icon                                  |                                                         |
| type      | string\|null  | null         | Override the icon type (e.g., `heroicons`). Defaults to the configured type. |                                                         |
| left      | ComponentSlot | string\|null | null                                                                         | Text or HTML content displayed to the left of the icon  |
| right     | ComponentSlot | string\|null | null                                                                         | Text or HTML content displayed to the right of the icon |

## Slots

| Slot  | Description                                                               |
|-------|---------------------------------------------------------------------------|
| left  | Content rendered to the left of the icon, wrapped in an inline-flex span  |
| right | Content rendered to the right of the icon, wrapped in an inline-flex span |

## Configuration

Configuration via `config/tallstackui.php` under `components.icon`:

| Key          | Default     | Description                                                                                                                             |
|--------------|-------------|-----------------------------------------------------------------------------------------------------------------------------------------|
| type         | 'heroicons' | Default icon type. Allowed: `heroicons` or a BladeUI package name.                                                                      |
| style        | 'solid'     | Default icon style. Allowed: `solid`, `outline` (Heroicons only).                                                                       |
| custom.guide | array       | Map of internal icon names to custom icon filenames. Set a value to override the default icon. When null, uses the key as the filename. |

Environment variables:
- `TALLSTACKUI_ICON_TYPE` - Override icon type
- `TALLSTACKUI_ICON_STYLE` - Override icon style
