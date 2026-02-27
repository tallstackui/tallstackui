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
    ->block('item', 'your-tailwind-classes');
```

### Available Blocks

| Block Name       | Purpose                                                     |
|------------------|-------------------------------------------------------------|
| wrapper          | Flex container for icon and text                            |
| item             | Submenu trigger button styles (text, padding, hover, focus) |
| border           | Separator border styles                                     |
| icon             | Custom icon size styles                                     |
| submenu.left     | Left chevron indicator size                                 |
| submenu.right    | Right chevron indicator size                                |
| floating.default | Floating panel base styles (background, border, shadow)     |
| slot             | Submenu items container with overflow and rounding          |
