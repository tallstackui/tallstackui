# TallStackUI: Dropdown Items

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

An individual menu item within a Dropdown component, rendering as a button, link, or div depending on the provided attributes. Supports icons, separators, and Livewire navigation.

## Basic Usage

```blade
<x-dropdown text="Actions">
    <x-dropdown.items text="Edit" icon="pencil" />
    <x-dropdown.items text="Duplicate" icon="document-duplicate" />
    <x-dropdown.items text="Delete" icon="trash" separator />
</x-dropdown>
```

```blade
<x-dropdown text="Navigation">
    <x-dropdown.items text="Dashboard" href="/dashboard" navigate />
    <x-dropdown.items text="Settings" href="/settings" icon="cog-6-tooth" position="right" />
</x-dropdown>
```

Using the default slot for custom content:

```blade
<x-dropdown text="Menu">
    <x-dropdown.items>
        <div class="flex items-center gap-2">
            <img src="/avatar.jpg" class="h-6 w-6 rounded-full" />
            <span>John Doe</span>
        </div>
    </x-dropdown.items>
</x-dropdown>
```

## Attributes

| Attribute     | Type         | Default | Description                                                          |
|---------------|--------------|---------|----------------------------------------------------------------------|
| text          | string\|null | null    | Text label for the menu item                                         |
| icon          | string\|null | null    | Heroicon name displayed alongside the text                           |
| position      | string\|null | 'left'  | Icon position relative to text ('left' or 'right')                   |
| href          | string\|null | null    | URL to navigate to (renders as `<a>` tag instead of `<button>`)      |
| separator     | bool\|null   | false   | When true, adds a top border separator line                          |
| navigate      | bool\|null   | null    | When true, uses Livewire `wire:navigate` for SPA-like navigation     |
| navigateHover | bool\|null   | null    | When true, uses Livewire `wire:navigate.hover` for prefetch on hover |

## Slots

| Slot      | Description                                       |
|-----------|---------------------------------------------------|
| (default) | Custom content (used when `text` is not provided) |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->dropdown('items')
    ->block('item.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name    | Purpose                                                                                                      |
|---------------|--------------------------------------------------------------------------------------------------------------|
| item.base     | Menu item base styles (text color, hover, focus, cursor) — padding/text/icon sizes live in the size variants |
| item.sizes.xs | Padding and text size applied when the parent dropdown is `xs`                                               |
| item.sizes.sm | Padding and text size applied when the parent dropdown is `sm`                                               |
| item.sizes.md | Padding and text size applied when the parent dropdown is `md` (default)                                     |
| item.sizes.lg | Padding and text size applied when the parent dropdown is `lg`                                               |
| border        | Separator border styles                                                                                      |
| icon.base     | Icon color styles                                                                                            |
| icon.sizes.xs | Icon dimensions when the parent dropdown is `xs`                                                             |
| icon.sizes.sm | Icon dimensions when the parent dropdown is `sm`                                                             |
| icon.sizes.md | Icon dimensions when the parent dropdown is `md` (default)                                                   |
| icon.sizes.lg | Icon dimensions when the parent dropdown is `lg`                                                             |

The `item.sizes.*` and `icon.sizes.*` blocks use Tailwind v4 arbitrary variants that read the `data-tsui-dropdown-size` attribute set by the parent floating panel — all four are emitted on every item, but only the one that matches the parent's size actually applies. There is no per-item size prop; cascade is automatic.
