# TallStackUI: Layout Sidebar

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A responsive sidebar navigation component with mobile slide-out drawer and desktop fixed panel. Supports collapsible mode, custom branding, scroll styling, Livewire navigation, and a footer slot.

## Basic Usage

```blade
<x-layout.sidebar>
    <x-slot:brand>
        <div class="flex items-center px-4 py-6">
            <img src="/logo.svg" alt="Logo" class="h-8" />
            <span class="ml-2 font-bold">My App</span>
        </div>
    </x-slot:brand>
    <x-layout.sidebar.item text="Dashboard" route="/dashboard" icon="home" />
    <x-layout.sidebar.item text="Settings" route="/settings" icon="cog-6-tooth" />
</x-layout.sidebar>
```

```blade
<x-layout.sidebar collapsible navigate thin-scroll>
    <x-slot:brand>
        <div class="px-4 py-6"><img src="/logo.svg" class="h-8" /></div>
    </x-slot:brand>
    <x-slot:brand-collapsed>
        <div class="px-2 py-6"><img src="/icon.svg" class="h-8" /></div>
    </x-slot:brand-collapsed>
    <x-layout.sidebar.separator text="Main" />
    <x-layout.sidebar.item text="Dashboard" route="/dashboard" icon="home" />
    <x-slot:footer>
        <p class="text-sm text-gray-400">v3.0</p>
    </x-slot:footer>
</x-layout.sidebar>
```

## Attributes

| Attribute       | Type               | Default | Description                                                        |
|-----------------|--------------------|---------|--------------------------------------------------------------------|
| brand           | slot\|string\|null | null    | Branding content displayed at the top of the sidebar               |
| brand-collapsed | slot\|string\|null | null    | Alternate branding shown when the collapsible sidebar is collapsed |
| footer          | slot\|string\|null | null    | Content rendered at the bottom of the sidebar                      |
| smart           | bool\|null         | null    | Enables smart route matching for all child items                   |
| navigate        | bool\|null         | null    | Adds `wire:navigate` to all child item links                       |
| navigate-hover  | bool\|null         | null    | Adds `wire:navigate.hover` to all child item links                 |
| thin-scroll     | bool\|null         | null    | Applies thin soft scrollbar styling to the sidebar items area      |
| thick-scroll    | bool\|null         | null    | Applies thick custom scrollbar styling to the sidebar items area   |
| collapsible     | bool\|null         | null    | Enables sidebar collapse/expand toggling from the header           |

## Slots

| Slot            | Description                                                                             |
|-----------------|-----------------------------------------------------------------------------------------|
| (default)       | Sidebar navigation items (`<x-layout.sidebar.item>` and `<x-layout.sidebar.separator>`) |
| brand           | Logo or branding content at the top                                                     |
| brand-collapsed | Compact branding shown when sidebar is collapsed (requires `collapsible`)               |
| footer          | Footer content pinned to the bottom of the sidebar                                      |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->sideBar()
    ->block('mobile.wrapper.first', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                   | Purpose                                                 |
|------------------------------|---------------------------------------------------------|
| mobile.wrapper.first         | Mobile sidebar root container                           |
| mobile.wrapper.second        | Mobile fixed inset flex container                       |
| mobile.wrapper.third         | Mobile sidebar inner panel wrapper                      |
| mobile.wrapper.fourth        | Mobile sidebar background and flex column               |
| mobile.wrapper.fifth         | Mobile sidebar content height and flex layout           |
| mobile.wrapper.sixth         | Mobile sidebar items list flex layout                   |
| mobile.wrapper.brand.margin  | Top margin applied when no brand is provided (mobile)   |
| mobile.wrapper.items         | Mobile scrollable items container                       |
| mobile.backdrop              | Semi-transparent overlay behind the mobile sidebar      |
| mobile.button.wrapper        | Mobile close button positioning                         |
| mobile.button.size           | Mobile close icon dimensions and color                  |
| mobile.button.icon           | Icon name for the mobile close button (default: x-mark) |
| mobile.footer                | Mobile footer border and padding                        |
| desktop.wrapper.first.base   | Desktop sidebar fixed positioning and flex layout       |
| desktop.wrapper.first.size   | Desktop sidebar width                                   |
| desktop.wrapper.second       | Desktop sidebar inner panel with border and background  |
| desktop.wrapper.third        | Desktop brand area height and alignment                 |
| desktop.wrapper.brand.margin | Top margin applied when no brand is provided (desktop)  |
| desktop.wrapper.fourth       | Desktop content height and flex layout                  |
| desktop.wrapper.fifth        | Desktop items list flex layout                          |
| desktop.wrapper.items        | Desktop scrollable items container                      |
| desktop.sizes.expanded       | Width when the collapsible sidebar is expanded          |
| desktop.sizes.collapsed      | Width when the collapsible sidebar is collapsed         |
| desktop.footer               | Desktop footer border and padding                       |
