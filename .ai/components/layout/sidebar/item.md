# TallStackUI: Sidebar Item

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A sidebar navigation item component that renders as either a single link or a collapsible group containing nested items. Supports active state detection via route matching, icons, badges, tooltips in collapsed mode, and visibility control.

## Basic Usage

Single navigation item:

```blade
<x-layout.sidebar.item text="Dashboard" route="/dashboard" icon="home" />
```

Item with badge:

```blade
<x-layout.sidebar.item text="Notifications" route="/notifications" icon="bell">
    <x-slot:badge>5</x-slot:badge>
</x-layout.sidebar.item>
```

Collapsible group with nested items:

```blade
<x-layout.sidebar.item text="Settings" icon="cog-6-tooth" opened>
    <x-layout.sidebar.item text="General" route="/settings/general" />
    <x-layout.sidebar.item text="Security" route="/settings/security" />
</x-layout.sidebar.item>
```

Marking an item as current manually:

```blade
<x-layout.sidebar.item text="Profile" href="/profile" icon="user" current />
```

Using route pattern matching:

```blade
<x-layout.sidebar.item text="Orders" route="/orders" icon="shopping-cart" match="orders.*" />
```

## Attributes

| Attribute   | Type               | Default | Description                                                             |
|-------------|--------------------|---------|-------------------------------------------------------------------------|
| text        | string\|null       | null    | Display label for the navigation item                                   |
| route       | string\|null       | null    | URL or named route. Used for `href` and for smart active-state matching |
| href        | string\|null       | null    | Raw URL link (skips route matching, bypasses `wire:navigate`)           |
| match       | string\|null       | null    | Route name pattern for `routeIs()` active-state matching                |
| icon        | slot\|string\|null | null    | Heroicon name or a custom slot for the item icon                        |
| badge       | slot\|string\|null | null    | Badge content displayed next to the item text                           |
| badge-color | string\|null       | 'red'   | Color of the badge component                                            |
| current     | bool\|null         | null    | Forces the item to display as active                                    |
| opened      | bool\|null         | null    | When used as a group, starts in the expanded state                      |
| visible     | Closure\|bool      | true    | Controls whether the item is rendered                                   |

## Slots

| Slot      | Description                                                                     |
|-----------|---------------------------------------------------------------------------------|
| (default) | Nested `<x-layout.sidebar.item>` children, making this item a collapsible group |
| icon      | Custom icon content instead of a Heroicon name                                  |
| badge     | Badge content displayed alongside the item text                                 |

## Inherited Attributes

These attributes are inherited from the parent `<x-layout.sidebar>` via `@aware`:

| Attribute      | Source                                                                            |
|----------------|-----------------------------------------------------------------------------------|
| smart          | `<x-layout.sidebar smart>` enables automatic route-based active state detection   |
| navigate       | `<x-layout.sidebar navigate>` adds `wire:navigate` to links                       |
| navigate-hover | `<x-layout.sidebar navigate-hover>` adds `wire:navigate.hover` to links           |
| collapsible    | `<x-layout.sidebar collapsible>` enables collapsed sidebar behavior with tooltips |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->sideBar('item')
    ->block('item.state.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                 | Purpose                                                |
|----------------------------|--------------------------------------------------------|
| group.button               | Collapsible group toggle button styles                 |
| group.icon.base            | Group icon dimensions and color                        |
| group.icon.collapse.base   | Chevron icon for group expand/collapse                 |
| group.icon.collapse.rotate | Chevron rotation when group is expanded                |
| group.group                | Nested items list padding                              |
| group.text                 | Group text with overflow and transition handling       |
| group.text.visible         | Group text visible state (sidebar expanded)            |
| group.text.hidden          | Group text hidden state (sidebar collapsed)            |
| group.badge                | Group badge overflow and transition                    |
| group.badge.visible        | Group badge visible state                              |
| group.badge.hidden         | Group badge hidden state                               |
| item.wrapper.base          | Single item list-item wrapper padding                  |
| item.wrapper.border        | Left border for nested items within a group            |
| item.state.base            | Base styles for the item link (flex, font, transition) |
| item.state.current         | Active/current state background and text color         |
| item.state.normal          | Default/hover state text color                         |
| item.state.collapsed       | Centered alignment when sidebar is collapsed           |
| item.icon                  | Item icon dimensions and color                         |
| item.text                  | Item text overflow and transition                      |
| item.text.visible          | Item text visible state                                |
| item.text.hidden           | Item text hidden state                                 |
| item.badge                 | Item badge margin and overflow transition              |
| item.badge.visible         | Item badge visible state                               |
| item.badge.hidden          | Item badge hidden state                                |
