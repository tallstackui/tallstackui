# TallStackUI: Stats

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A statistics card component for displaying numeric metrics with titles, icons, and trend indicators. Supports solid, light, and outline styles, animated number counting, and optional link behavior with Livewire navigation.

## Basic Usage

```blade
<x-stats number="1,234" title="Total Users" icon="users" />
```

```blade
<x-stats number="89" title="Conversion Rate" icon="chart-bar" color="green" increase />
```

```blade
<x-stats number="42" title="Open Issues" color="red" outline decrease animated />
```

```blade
<x-stats number="567" title="Revenue" href="/reports" navigate>
    <x-slot:header>Monthly Report</x-slot:header>
    <x-slot:footer>Updated 5 min ago</x-slot:footer>
</x-stats>
```

## Attributes

| Attribute      | Type                        | Default   | Description                                                           |
|----------------|-----------------------------|-----------|-----------------------------------------------------------------------|
| number         | string\|int\|null           | null      | Numeric value to display                                              |
| title          | string\|null                | null      | Descriptive title above/beside the number                             |
| icon           | ComponentSlot\|string\|null | null      | Heroicon name or a slot for fully custom icon markup                  |
| color          | string\|null                | 'primary' | Color theme for the icon background (e.g., primary, red, green)       |
| href           | string\|null                | null      | When set, renders as an anchor tag for click-through behavior         |
| solid          | bool                        | true      | Uses the solid color style variant (default)                          |
| light          | bool                        | false     | Uses the light color style variant                                    |
| outline        | bool                        | false     | Uses the outline color style variant                                  |
| animated       | bool                        | false     | Enables animated count-up effect when the element enters the viewport |
| increase       | bool                        | false     | Shows an upward trend arrow icon on the right side                    |
| decrease       | bool                        | false     | Shows a downward trend arrow icon on the right side                   |
| navigate       | bool                        | null      | Enables Livewire's wire:navigate for SPA-style navigation             |
| navigate-hover | bool                        | null      | Enables Livewire's wire:navigate.hover for prefetch on hover          |

## Slots

| Slot      | Description                                                         |
|-----------|---------------------------------------------------------------------|
| (default) | Custom content replacing the number display area                    |
| header    | Content displayed above the stats body (string or ComponentSlot)    |
| footer    | Content displayed below the stats body (string or ComponentSlot)    |
| right     | Custom content on the right side (replaces increase/decrease arrow) |
| icon      | Fully custom icon markup (replaces the default icon rendering)      |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->stats()
    ->block('wrapper.first', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                 | Purpose                                              |
|----------------------------|------------------------------------------------------|
| wrapper.first              | Outer card container (flex column, rounded, shadow)  |
| wrapper.second             | Inner content layout (flex, centered, gap)           |
| wrapper.third              | Icon container (flex, centered, rounded, dimensions) |
| slots.header               | Header slot text styles                              |
| slots.footer               | Footer slot text styles                              |
| slots.right.increase.icon  | Increase trend arrow icon name                       |
| slots.right.increase.class | Increase trend arrow icon styles                     |
| slots.right.decrease.icon  | Decrease trend arrow icon name                       |
| slots.right.decrease.class | Decrease trend arrow icon styles                     |
| icon                       | Icon dimensions inside the icon container            |
| title                      | Title text styles                                    |
| number                     | Number text styles (font size, weight, color)        |
