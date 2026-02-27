# TallStackUI: Layout Header

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

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

| Attribute             | Type               | Default | Description                                      |
|-----------------------|--------------------|---------|--------------------------------------------------|
| left                  | slot\|string\|null | null    | Content rendered on the left side of the header  |
| middle                | slot\|string\|null | null    | Content rendered in the center of the header     |
| right                 | slot\|string\|null | null    | Content rendered on the right side of the header |
| without-mobile-button | bool\|null         | null    | Hides the mobile hamburger menu toggle button    |

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
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name         | Purpose                                                   |
|--------------------|-----------------------------------------------------------|
| wrapper            | Outer sticky header container with flex layout and shadow |
| button.class       | Mobile hamburger menu button visibility and cursor        |
| button.icon.size   | Mobile hamburger icon dimensions and color                |
| collapse.class     | Desktop sidebar collapse toggle button visibility         |
| collapse.icon      | Icon name used for the collapse toggle (default: bars-4)  |
| collapse.icon.size | Collapse toggle icon dimensions and color                 |
| slots.wrapper      | Flex container wrapping all three slot areas              |
| slots.left         | Flex container for the left slot                          |
| slots.middle       | Flex container for the middle slot                        |
| slots.right        | Flex container for the right slot                         |
