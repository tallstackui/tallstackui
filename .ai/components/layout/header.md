# TallStackUI: Layout Header

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A sticky top header component for the application layout. Provides left, middle, and right content slots with automatic justification, a mobile hamburger button for toggling the sidebar, and a collapse toggle button for the collapsible sidebar.

## Basic Usage

```blade
<x-layout.header>
    <x-slot:left>
        <span class="font-bold">My Application</span>
    </x-slot:left>
    <x-slot:right>
        <img src="/avatar.jpg" class="h-8 w-8 rounded-full" alt="User" />
    </x-slot:right>
</x-layout.header>
```

```blade
<x-layout.header without-mobile-button>
    <x-slot:left>Logo</x-slot:left>
    <x-slot:middle>
        <input type="search" placeholder="Search..." class="rounded-lg border px-4 py-2" />
    </x-slot:middle>
    <x-slot:right>
        <x-button text="Sign Out" xs />
    </x-slot:right>
</x-layout.header>
```

## Attributes

| Attribute             | Type               | Default | Description                                                                                                                 |
|-----------------------|--------------------|---------|-----------------------------------------------------------------------------------------------------------------------------|
| left                  | slot\|string\|null | null    | Content rendered on the left side of the header                                                                             |
| middle                | slot\|string\|null | null    | Content rendered in the center of the header                                                                                |
| right                 | slot\|string\|null | null    | Content rendered on the right side of the header                                                                            |
| without-mobile-button | bool\|null         | null    | Hides the mobile hamburger menu toggle button                                                                               |
| collapse-icon         | string\|null       | null    | Icon of the sidebar collapse toggle. Falls back to the `collapse-icon` config, then to the `collapse.icon` block (`bars-4`) |
| size                  | string\|null       | md      | Header height (`sm`, `md`, `lg`, `xl`)                                                                                      |
| sm                    | bool\|null         | null    | Shortcut for `size="sm"` (`h-14`)                                                                                           |
| md                    | bool\|null         | null    | Shortcut for `size="md"` (`h-16`)                                                                                           |
| lg                    | bool\|null         | null    | Shortcut for `size="lg"` (`h-20`)                                                                                           |
| xl                    | bool\|null         | null    | Shortcut for `size="xl"` (`h-24`)                                                                                           |

The `size` default comes from the global configuration, so it describes the shipped
configuration rather than a value hardcoded in the component.

```blade
<x-layout.header lg />
<x-layout.header size="lg" />
```

## Global Configuration

```php
// config/tallstackui.php
'layout.header' => [
    \TallStackUi\Components\Layout\Header\Component::class,
    [
        'size' => 'md',
        'collapse-icon' => null, // any icon name, e.g. chevron-double-left
    ],
],
```

A shortcut flag wins over `size`, `size` wins over the configuration. An unknown
size raises a validation exception, wherever it came from. The `collapse-icon`
attribute wins over its configuration, and both win over the `collapse.icon`
customization block, which stays the last fallback.

## Validation Constraints

- The resolved `size` must be one of: `sm`, `md`, `lg`, `xl`.

## Slots

| Slot      | Description                                                       |
|-----------|-------------------------------------------------------------------|
| (default) | Additional content appended to the header                         |
| left      | Left-aligned content, rendered next to the sidebar toggle buttons |
| middle    | Center-aligned content                                            |
| right     | Right-aligned content                                             |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->layout('header')
    ->block('wrapper.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name         | Purpose                                                                                                                                                                                           |
|--------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| wrapper.base       | Outer sticky header container with flex layout and shadow, minus its height. Carries `tsui-scrollbar-bleed`, which lets the header reach the viewport edge while an overlay holds the scroll lock |
| wrapper.sizes.sm   | Height applied by `sm`                                                                                                                                                                            |
| wrapper.sizes.md   | Height applied by `md`                                                                                                                                                                            |
| wrapper.sizes.lg   | Height applied by `lg`                                                                                                                                                                            |
| wrapper.sizes.xl   | Height applied by `xl`                                                                                                                                                                            |
| button.class       | Mobile hamburger menu button visibility and cursor                                                                                                                                                |
| button.icon.size   | Mobile hamburger icon dimensions and color                                                                                                                                                        |
| collapse.class     | Desktop sidebar collapse toggle button visibility                                                                                                                                                 |
| collapse.icon      | Icon name used for the collapse toggle (default: bars-4)                                                                                                                                          |
| collapse.icon.size | Collapse toggle icon dimensions and color                                                                                                                                                         |
| slots.wrapper      | Flex container wrapping all three slot areas                                                                                                                                                      |
| slots.left         | Flex container for the left slot                                                                                                                                                                  |
| slots.middle       | Flex container for the middle slot                                                                                                                                                                |
| slots.right        | Flex container for the right slot                                                                                                                                                                 |
