# TallStackUI: Dropdown Submenu

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A nested submenu within a Dropdown component that opens a secondary floating panel on click. Displays a chevron indicator for the submenu direction and supports icons and separators.

## Basic Usage

```blade
<x-dropdown text="Menu">
    <x-dropdown.items text="Home" />
    <x-dropdown.submenu text="More Options">
        <x-dropdown.items text="Option A" />
        <x-dropdown.items text="Option B" />
    </x-dropdown.submenu>
    <x-dropdown.items text="Settings" separator />
</x-dropdown>
```

```blade
<x-dropdown text="Actions">
    <x-dropdown.submenu text="Export" icon="arrow-down-tray" position="left">
        <x-dropdown.items text="Export as CSV" />
        <x-dropdown.items text="Export as PDF" />
    </x-dropdown.submenu>
</x-dropdown>
```

## Attributes

| Attribute | Type         | Default | Description                                                                            |
|-----------|--------------|---------|----------------------------------------------------------------------------------------|
| text      | string\|null | null    | Text label for the submenu trigger                                                     |
| icon      | string\|null | null    | Heroicon name displayed alongside the text                                             |
| position  | string\|null | 'right' | Submenu opening direction ('right' maps to 'right-start', 'left' maps to 'left-start') |
| separator | bool\|null   | false   | When true, adds a top border separator line                                            |

## Slots

| Slot      | Description                                               |
|-----------|-----------------------------------------------------------|
| (default) | Submenu items (typically `<x-dropdown.items>` components) |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->dropdown('submenu')
    ->block('item.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name          | Purpose                                                                                      |
|---------------------|----------------------------------------------------------------------------------------------|
| wrapper             | Flex container for icon and text                                                             |
| item.base           | Submenu trigger button base styles (text, hover, focus) — padding/text live in size variants |
| item.sizes.xs       | Padding and text size when the parent dropdown is `xs`                                       |
| item.sizes.sm       | Padding and text size when the parent dropdown is `sm`                                       |
| item.sizes.md       | Padding and text size when the parent dropdown is `md` (default)                             |
| item.sizes.lg       | Padding and text size when the parent dropdown is `lg`                                       |
| border              | Separator border styles                                                                      |
| icon.sizes.xs       | Trigger icon dimensions when the parent dropdown is `xs`                                     |
| icon.sizes.sm       | Trigger icon dimensions when the parent dropdown is `sm`                                     |
| icon.sizes.md       | Trigger icon dimensions when the parent dropdown is `md` (default)                           |
| icon.sizes.lg       | Trigger icon dimensions when the parent dropdown is `lg`                                     |
| submenu.left        | Left chevron indicator size                                                                  |
| submenu.right       | Right chevron indicator size                                                                 |
| floating.default    | Floating panel base styles (background, border, shadow)                                      |
| floating.widths.xxs | Floating panel width when the parent dropdown is `width="xxs"` (`w-32`)                      |
| floating.widths.xs  | Floating panel width when the parent dropdown is `width="xs"` (`w-40`)                       |
| floating.widths.sm  | Floating panel width when the parent dropdown is `width="sm"` (`w-48`)                       |
| floating.widths.md  | Floating panel width when the parent dropdown is `width="md"` (`w-56`, default)              |
| floating.widths.lg  | Floating panel width when the parent dropdown is `width="lg"` (`w-64`)                       |
| floating.widths.xl  | Floating panel width when the parent dropdown is `width="xl"` (`w-72`)                       |
| floating.widths.2xl | Floating panel width when the parent dropdown is `width="2xl"` (`w-80`)                      |
| slot                | Submenu items container with overflow and rounding                                           |

Size and width are inherited from the parent `<x-dropdown>` automatically (see `.ai/components/dropdown/main.md`). The submenu has no per-instance size or width props — its trigger button reads the parent's `data-tsui-dropdown-size` via Tailwind arbitrary variants, and its teleported floating panel reads `data-tsui-dropdown-size` / `data-tsui-dropdown-width` set on Alpine `init` from the closest ancestor.
