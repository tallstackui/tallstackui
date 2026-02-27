# TallStackUI: Dial

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A floating action button (FAB) / speed dial component that reveals a set of action items on click or hover. Supports fixed corner positioning, multiple sizes, color styles, and horizontal or vertical item layouts.

## Basic Usage

```blade
<x-dial>
    <x-dial.items icon="pencil" label="Edit" />
    <x-dial.items icon="trash" label="Delete" />
    <x-dial.items icon="share" label="Share" />
</x-dial>
```

```blade
<x-dial icon="bars-3" position="bottom-left" color="green" horizontal>
    <x-dial.items icon="home" label="Home" href="/dashboard" />
    <x-dial.items icon="cog-6-tooth" label="Settings" href="/settings" />
</x-dial>
```

```blade
<x-dial icon="plus" position="top-right" style="outline" color="secondary" lg hover square>
    <x-dial.items icon="document-plus" label="New Document" square />
    <x-dial.items icon="folder-plus" label="New Folder" square />
</x-dial>
```

## Attributes

| Attribute      | Type         | Default        | Description                                                             |
|----------------|--------------|----------------|-------------------------------------------------------------------------|
| icon           | string\|null | 'plus'         | Heroicon name for the main dial button (rotates 45deg when open)        |
| square         | bool\|null   | false          | When true, uses rounded-lg instead of rounded-full for the button shape |
| position       | string\|null | 'bottom-right' | Fixed screen position (top-left, top-right, bottom-left, bottom-right)  |
| color          | string\|null | 'primary'      | Color theme for the dial button                                         |
| style          | string\|null | 'solid'        | Color style variant (solid, light, outline)                             |
| horizontal     | bool\|null   | false          | When true, arranges items horizontally instead of vertically            |
| hover          | bool\|null   | false          | When true, opens the dial on hover instead of only on click             |
| withoutTooltip | bool\|null   | null           | When true, hides tooltip labels on child dial items                     |
| xs             | bool\|null   | null           | Extra-small size                                                        |
| sm             | bool\|null   | null           | Small size                                                              |
| md             | bool\|null   | null           | Medium size (default)                                                   |
| lg             | bool\|null   | null           | Large size                                                              |

## Slots

| Slot      | Description                                               |
|-----------|-----------------------------------------------------------|
| (default) | Dial action items (typically `<x-dial.items>` components) |

## Validation Constraints

- The `position` must be one of: top-left, top-right, bottom-left, bottom-right.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->dial()
    ->block('button.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name            | Purpose                                                         |
|-----------------------|-----------------------------------------------------------------|
| icon.base             | Icon transition styles                                          |
| icon.sizes.xs         | Extra-small icon dimensions                                     |
| icon.sizes.sm         | Small icon dimensions                                           |
| icon.sizes.md         | Medium icon dimensions                                          |
| icon.sizes.lg         | Large icon dimensions                                           |
| button.base           | Main dial button base styles (flex, shadow, focus ring, cursor) |
| button.sizes.xs       | Extra-small button dimensions                                   |
| button.sizes.sm       | Small button dimensions                                         |
| button.sizes.md       | Medium button dimensions                                        |
| button.sizes.lg       | Large button dimensions                                         |
| position.top-left     | Fixed top-left positioning                                      |
| position.top-right    | Fixed top-right positioning                                     |
| position.bottom-left  | Fixed bottom-left positioning                                   |
| position.bottom-right | Fixed bottom-right positioning                                  |
| items                 | Flex container for action items                                 |
