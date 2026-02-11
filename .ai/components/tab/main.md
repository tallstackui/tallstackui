# TallStackUI: Tab

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 40+ Blade components for building modern web interfaces.

A tabbed interface component with underline-styled tab navigation and content panels. On mobile, tabs collapse to a select dropdown by default (unless scroll-on-mobile is enabled). Supports Livewire property binding and tab-change events.

## Basic Usage

```blade
<x-tab selected="profile">
    <x-tab.items tab="profile">
        <p>Profile content here.</p>
    </x-tab.items>
    <x-tab.items tab="settings">
        <p>Settings content here.</p>
    </x-tab.items>
</x-tab>
```

With Livewire binding:

```blade
<x-tab wire:model="activeTab">
    <x-tab.items tab="overview" title="Overview">
        <p>Overview panel.</p>
    </x-tab.items>
    <x-tab.items tab="details" title="Details">
        <p>Details panel.</p>
    </x-tab.items>
</x-tab>
```

With scroll on mobile and centered tabs:

```blade
<x-tab selected="first" scroll-on-mobile centered>
    <x-tab.items tab="first" title="First Tab">
        <p>First tab content.</p>
    </x-tab.items>
    <x-tab.items tab="second" title="Second Tab">
        <p>Second tab content.</p>
    </x-tab.items>
</x-tab>
```

Listening for tab navigation events:

```blade
<x-tab selected="home" x-on:navigate="console.log($event.detail.select)">
    <x-tab.items tab="home">Home content</x-tab.items>
    <x-tab.items tab="about">About content</x-tab.items>
</x-tab>
```

## Attributes

| Attribute        | Type         | Default | Description                                                                  |
|------------------|--------------|---------|------------------------------------------------------------------------------|
| selected         | string\|null | null    | Initially selected tab identifier (or use `wire:model` for Livewire binding) |
| scroll-on-mobile | bool\|null   | null    | Shows horizontal scrollable tabs on mobile instead of a select dropdown      |
| centered         | bool\|null   | null    | Centers the tab navigation items                                             |

## Slots

| Slot      | Description                                                |
|-----------|------------------------------------------------------------|
| (default) | `<x-tab.items>` children defining each tab and its content |

## Events

| Event         | Detail             | Description                                           |
|---------------|--------------------|-------------------------------------------------------|
| x-on:navigate | `{select: string}` | Fired when a tab is selected, with the tab identifier |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Personalization

```php
TallStackUi::personalize()
    ->tab()
    ->block('base.wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name    | Purpose                                             |
|---------------|-----------------------------------------------------|
| base.wrapper  | Outer card container with background and shadow     |
| base.padding  | Padding wrapper for the mobile select dropdown      |
| base.body     | Flex container for the tab navigation list          |
| base.content  | Content area padding and text color                 |
| base.divider  | Horizontal divider between tabs and content         |
| base.select   | Mobile select dropdown styling                      |
| item.wrapper  | Individual tab item flex layout and padding         |
| item.select   | Active/selected tab underline border and text color |
| item.unselect | Inactive tab border and text color                  |
