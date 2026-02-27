# TallStackUI: Layout

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A full-page application layout component that provides a structured shell with optional top content, sidebar menu, header, main content area, and footer. Integrates with the sidebar component for responsive navigation with collapsible state management.

## Basic Usage

```blade
<x-layout>
    <x-slot:menu>
        <x-layout.sidebar>
            <x-layout.sidebar.item text="Dashboard" route="/dashboard" icon="home" />
        </x-layout.sidebar>
    </x-slot:menu>
    <x-slot:header>
        <x-layout.header>
            <x-slot:left>My App</x-slot:left>
        </x-layout.header>
    </x-slot:header>

    <p>Main page content goes here.</p>

    <x-slot:footer>
        <p class="text-center text-sm text-gray-500">Footer content</p>
    </x-slot:footer>
</x-layout>
```

```blade
<x-layout>
    <x-slot:top>
        <div class="bg-primary-500 text-white text-center py-2">Announcement bar</div>
    </x-slot:top>
    <x-slot:menu>
        <x-layout.sidebar navigate>
            <x-layout.sidebar.item text="Home" route="/home" icon="home" />
        </x-layout.sidebar>
    </x-slot:menu>
    <x-slot:header>
        <x-layout.header />
    </x-slot:header>

    <h1>Welcome</h1>
</x-layout>
```

## Attributes

| Attribute | Type               | Default | Description                                                |
|-----------|--------------------|---------|------------------------------------------------------------|
| top       | slot\|string\|null | null    | Content rendered above everything (e.g., announcement bar) |
| header    | slot\|string\|null | null    | Header content rendered above the main area                |
| menu      | slot\|string\|null | null    | Sidebar menu content (typically `<x-layout.sidebar>`)      |
| footer    | slot\|string\|null | null    | Content rendered below the main area                       |

## Slots

| Slot      | Description                                            |
|-----------|--------------------------------------------------------|
| (default) | Main page content rendered inside the `<main>` element |
| top       | Content rendered at the very top of the layout         |
| menu      | Sidebar navigation component                           |
| header    | Header component rendered above the main content       |
| footer    | Footer content rendered below the main content         |

## Configuration

In `config/tallstackui.php` under `components.layout`:

| Option | Type | Default                                                | Description                                                                                                                             |
|--------|------|--------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------|
| ignore | bool | `env('TALLSTACKUI_IGNORE_LAYOUT_REGISTRATION', false)` | When true, prevents registration of the layout component and all its children, useful to avoid conflicts with your own layout component |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->layout()
    ->block('wrapper.first', 'your-tailwind-classes');
```

### Available Blocks

| Block Name               | Purpose                                   |
|--------------------------|-------------------------------------------|
| wrapper.first            | Outer wrapper with minimum height         |
| wrapper.second.expanded  | Padding applied when sidebar is expanded  |
| wrapper.second.collapsed | Padding applied when sidebar is collapsed |
| main                     | Main content area padding and max-width   |
