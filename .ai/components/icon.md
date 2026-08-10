# TallStackUI: Icon

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

An icon component that renders SVG icons from Heroicons (built-in) or BladeUI icon packages. Size and color come from bare shorthand attributes, with left/right slots for text or content alongside the icon and an error state that applies red coloring.

## Basic Usage

```blade
<x-icon name="home" />
```

```blade
<x-icon name="check-circle" lg green />
```

```blade
<x-icon name="exclamation-circle" 2xl error />
```

```blade
<x-icon name="arrow-right" sm>
    <x-slot:left>Next</x-slot:left>
</x-icon>
```

## Size

Eleven shorthands, one bare attribute each. `md` is the default and comes from the configuration.

| Shorthand | Classes     | Size |
|-----------|-------------|------|
| `xs`      | `h-3 w-3`   | 12px |
| `sm`      | `h-4 w-4`   | 16px |
| `md`      | `h-5 w-5`   | 20px |
| `lg`      | `h-6 w-6`   | 24px |
| `xl`      | `h-7 w-7`   | 28px |
| `2xl`     | `h-8 w-8`   | 32px |
| `3xl`     | `h-10 w-10` | 40px |
| `4xl`     | `h-12 w-12` | 48px |
| `5xl`     | `h-14 w-14` | 56px |
| `6xl`     | `h-16 w-16` | 64px |
| `7xl`     | `h-20 w-20` | 80px |

Two sizes at once throws:

```blade
<x-icon name="users" xs 2xl />   {{-- throws --}}
```

## Color

The 29 palette keys are bare attributes too, resolving to a single `text-*` class that paints the SVG through `currentColor`:

`black`, `primary`, `secondary`, `slate`, `gray`, `zinc`, `neutral`, `stone`, `red`, `orange`, `amber`, `yellow`, `lime`, `green`, `emerald`, `teal`, `cyan`, `sky`, `blue`, `indigo`, `violet`, `purple`, `fuchsia`, `pink`, `rose`, `mauve`, `olive`, `mist`, `taupe`

```blade
<x-icon name="star" 2xl amber />
```

No color means no class, so the icon inherits the surrounding `currentColor`. Two colors at once throws, and `error` takes precedence over any color:

```blade
<x-icon name="users" red blue />                  {{-- throws --}}
<x-icon name="exclamation-circle" error blue />   {{-- stays red --}}
```

## Class Overrides Both

Declaring `class` — including an empty `class=""` — turns off the size and the color shorthands, leaving the given classes untouched:

```blade
<x-icon name="bell" 4xl red class="size-6" />   {{-- size-6, nothing else --}}
<x-icon name="bell" class="size-10" />          {{-- size-10 --}}
```

This is how every internal usage inside the package keeps its own sizing.

Shorthands are consumed by the component and never reach the `<svg>`. They are not constructor properties, so IDE completion does not offer them: names like `2xl` are not valid PHP variables, which rules out declaring the scale as props.

## Attributes

| Attribute | Type                        | Default | Description                                                                  |
|-----------|-----------------------------|---------|------------------------------------------------------------------------------|
| icon      | string\|null                | null    | The icon name (e.g., `home`, `check-circle`, `arrow-right`)                  |
| name      | string\|null                | null    | Alternative to `icon` for specifying the icon name                           |
| error     | bool                        | false   | Applies red error color styling, taking precedence over any color shorthand  |
| type      | string\|null                | null    | Override the icon type (e.g., `heroicons`). Defaults to the configured type. |
| left      | ComponentSlot\|string\|null | null    | Text or HTML content displayed to the left of the icon                       |
| right     | ComponentSlot\|string\|null | null    | Text or HTML content displayed to the right of the icon                      |

Size and color are shorthand attributes rather than props; see the sections above.

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
| size         | 'md'        | Default size when no shorthand and no `class` are given. Allowed: any size shorthand. An invalid value throws.                          |
| custom.guide | array       | Map of internal icon names to custom icon filenames. Set a value to override the default icon. When null, uses the key as the filename. |

Environment variables:
- `TALLSTACKUI_ICON_TYPE` - Override icon type
- `TALLSTACKUI_ICON_STYLE` - Override icon style

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->icon()
    ->block('sizes.md', 'h-9 w-9');
```

```php
TallStackUi::customize('icon', scope: 'hero')
    ->block('sizes.md', 'h-12 w-12');
```

### Available Blocks

| Block Name                                     | Purpose                      |
|------------------------------------------------|------------------------------|
| sizes.{xs,sm,md,lg,xl,2xl,3xl,4xl,5xl,6xl,7xl} | Dimensions of each shorthand |

## Color Personalization

```bash
php artisan tallstackui:setup-color
```

Publishes an `IconColors` class with a single `textColors()` palette.
