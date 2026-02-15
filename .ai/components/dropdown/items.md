# TallStackUI: Dropdown Items

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 40+ Blade components for building modern web interfaces.

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

### Personalization

```php
TallStackUi::customize()
    ->dropdown('items')
    ->block('item', 'your-tailwind-classes');
```

### Available Blocks

| Block Name | Purpose                                                           |
|------------|-------------------------------------------------------------------|
| item       | Menu item base styles (text color, padding, hover, focus, cursor) |
| border     | Separator border styles                                           |
| icon       | Icon size and color styles                                        |
