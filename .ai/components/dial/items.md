# TallStackUI: Dial Items

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

An individual action item within a Dial speed dial component. Displays an icon button with an optional tooltip label, and renders as a link or button depending on whether an href is provided.

## Basic Usage

```blade
<x-dial>
    <x-dial.items icon="pencil" label="Edit" />
    <x-dial.items icon="trash" label="Delete" />
    <x-dial.items icon="share" label="Share" href="/share" />
</x-dial>
```

```blade
<x-dial position="bottom-right">
    <x-dial.items icon="document-plus" label="New File" />
    <x-dial.items icon="folder-plus" label="New Folder" square />
    <x-dial.items icon="cloud-arrow-up" label="Upload" href="/upload" navigate />
</x-dial>
```

## Attributes

| Attribute     | Type         | Default | Description                                                             |
|---------------|--------------|---------|-------------------------------------------------------------------------|
| icon          | string\|null | null    | Heroicon name for the item button (required)                            |
| label         | string\|null | null    | Tooltip text displayed alongside the item                               |
| square        | bool\|null   | false   | When true, uses rounded-lg instead of rounded-full for the button shape |
| href          | string\|null | null    | URL to navigate to (renders as `<a>` tag instead of `<button>`)         |
| navigate      | bool\|null   | null    | When true, uses Livewire `wire:navigate` for SPA-like navigation        |
| navigateHover | bool\|null   | null    | When true, uses Livewire `wire:navigate.hover` for prefetch on hover    |

## Validation Constraints

- The `icon` property is required.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->dial('items')
    ->block('item', 'your-tailwind-classes');
```

### Available Blocks

| Block Name         | Purpose                                                                             |
|--------------------|-------------------------------------------------------------------------------------|
| wrapper.base       | Item wrapper layout (relative flex, items-center)                                   |
| wrapper.horizontal | Extra wrapper classes applied when the parent dial is horizontal and a label is set |
| item               | Action button styles (dimensions, background, shadow, hover, focus)                 |
| label.tooltip      | Tooltip-style label rendered absolutely beside the item (used in vertical mode)     |
| label.inline       | Inline label rendered below the item (used when the parent dial is horizontal)      |
| icon               | Icon dimensions                                                                     |
