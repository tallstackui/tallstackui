# TallStackUI: Sidebar Item

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A sidebar navigation item component that renders as either a single link or a collapsible group containing nested items. Supports active state detection via route matching, icons, badges, tooltips in collapsed mode, and visibility control.

On a collapsed sidebar the nested items are unreachable inline, so a group opens them in a floating panel anchored to its icon, on hover or click. Single items keep the tooltip. A badge has no room on the collapsed rail either, and degrades to a dot on the corner of the icon, keeping its color.

## Basic Usage

Single navigation item:

```blade
<x-side-bar.item text="Dashboard" route="/dashboard" icon="home" />
```

Item with badge:

```blade
<x-side-bar.item text="Notifications" route="/notifications" icon="bell">
    <x-slot:badge>5</x-slot:badge>
</x-side-bar.item>
```

Collapsible group with nested items:

```blade
<x-side-bar.item text="Settings" icon="cog-6-tooth" opened>
    <x-side-bar.item text="General" route="/settings/general" />
    <x-side-bar.item text="Security" route="/settings/security" />
</x-side-bar.item>
```

Marking an item as current manually:

```blade
<x-side-bar.item text="Profile" href="/profile" icon="user" current />
```

Using route pattern matching:

```blade
<x-side-bar.item text="Orders" route="/orders" icon="shopping-cart" match="orders.*" />
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

| Slot      | Description                                                               |
|-----------|---------------------------------------------------------------------------|
| (default) | Nested `<x-side-bar.item>` children, making this item a collapsible group |
| icon      | Custom icon content instead of a Heroicon name                            |
| badge     | Badge content displayed alongside the item text                           |

## Inherited Attributes

These attributes are inherited from the parent `<x-side-bar>` via `@aware`:

| Attribute      | Source                                                                                                                                                                                                                                            |
|----------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| smart          | `<x-side-bar smart>` enables automatic route-based active state detection. An item never matches where there is no current route to compare against, which is the case on error views, or where the current route was declared without `->name()` |
| navigate       | `<x-side-bar navigate>` adds `wire:navigate` to links                                                                                                                                                                                             |
| navigate-hover | `<x-side-bar navigate-hover>` adds `wire:navigate.hover` to links                                                                                                                                                                                 |
| collapsible    | `<x-side-bar collapsible>` enables collapsed sidebar behavior with tooltips                                                                                                                                                                       |

## Tooltips While Collapsed

With `collapsible`, each item carries `x-tooltip` with its `text`, positioned to the
`right`, and binds `data-tooltip-disabled` to the sidebar store. Expanding the menu — or
switching to mobile, where the labels are visible — suppresses the balloon immediately,
even with the pointer already resting on the item. See
[Tooltip](../../tooltip.md#the-x-tooltip-directive).

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->sideBar('item')
    ->block('item.state.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                   | Purpose                                                      |
|------------------------------|--------------------------------------------------------------|
| group.button                 | Collapsible group toggle button styles                       |
| group.button.gap             | Gap between the group icon, text and badge                   |
| group.button.collapsed       | Centered alignment when sidebar is collapsed                 |
| group.icon.base              | Group icon dimensions and color                              |
| group.icon.collapse.base     | Chevron icon for group expand/collapse                       |
| group.icon.collapse.rotate   | Chevron rotation when group is expanded                      |
| group.group                  | Nested items list padding                                    |
| group.text                   | Group text with overflow and transition handling             |
| group.text.visible           | Group text visible state (sidebar expanded)                  |
| group.text.hidden            | Group text hidden state (sidebar collapsed)                  |
| group.badge                  | Group badge overflow and transition                          |
| group.badge.visible          | Group badge visible state                                    |
| group.badge.hidden           | Group badge hidden state                                     |
| group.flyout.wrapper         | Floating panel frame shown when a collapsed group is hovered |
| group.flyout.scroll          | Scroll container inside the panel frame, with its height cap |
| group.flyout.scrollbar.thin  | Thin scrollbar, when the sidebar asks for one                |
| group.flyout.scrollbar.thick | Thick scrollbar, when the sidebar asks for one               |
| group.dot                    | Dot replacing the group badge on the collapsed rail          |
| group.flyout.header          | Sticky group label at the top of the floating panel          |
| group.flyout.items           | List wrapper for the items inside the floating panel         |
| item.wrapper.base            | Single item list-item wrapper padding                        |
| item.wrapper.border          | Left border for nested items within a group                  |
| item.state.base              | Base styles for the item link (flex, font, transition)       |
| item.state.gap               | Gap between the item icon, text and badge                    |
| item.state.current           | Active/current state background and text color               |
| item.state.normal            | Default/hover state text color                               |
| item.state.collapsed         | Centered alignment when sidebar is collapsed                 |
| item.icon                    | Item icon dimensions and color                               |
| item.text                    | Item text overflow and transition                            |
| item.text.visible            | Item text visible state                                      |
| item.text.hidden             | Item text hidden state                                       |
| item.badge                   | Item badge wrapper overflow and transition                   |
| item.badge.visible           | Item badge visible state                                     |
| item.badge.hidden            | Item badge hidden state                                      |
| item.dot                     | Dot replacing the item badge on the collapsed rail           |
