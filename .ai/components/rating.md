# TallStackUI: Rating

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 40+ Blade components for building modern web interfaces.

A star rating component for displaying or collecting user ratings. Supports custom icons, interactive evaluation via Livewire methods, and a static read-only mode.

## Basic Usage

```blade
<x-rating wire:model="rating" />
```

```blade
<x-rating wire:model="score" :quantity="3" color="yellow" lg />
```

```blade
<x-rating :rate="4.5" static text="4.5 out of 5" />
```

```blade
<x-rating wire:model="rating" icon="heart" evaluate-method="setRating" color="red" />
```

## Attributes

| Attribute       | Type             | Default    | Description                                             |
|-----------------|------------------|------------|---------------------------------------------------------|
| icon            | string\|null     | null       | Custom Heroicon name (defaults to a built-in star SVG)  |
| quantity        | int\|null        | 5          | Number of stars/icons to display (maximum 5)            |
| rate            | float\|int\|null | null       | Current rating value                                    |
| text            | string\|null     | null       | Text displayed next to the stars                        |
| evaluate-method | string           | 'evaluate' | Livewire method name called when a star is clicked      |
| xs              | bool             | null       | Extra-small size                                        |
| sm              | bool             | null       | Small size                                              |
| md              | bool             | null       | Medium size (default)                                   |
| lg              | bool             | null       | Large size                                              |
| static          | bool             | false      | Disables click interaction, making the rating read-only |
| color           | string\|null     | 'primary'  | Color theme for active/selected stars                   |

## Slots

| Slot      | Description                                                                   |
|-----------|-------------------------------------------------------------------------------|
| (default) | Custom content displayed next to the stars (used when `text` is not provided) |

## Alpine.js Events

| Event         | Description                                      |
|---------------|--------------------------------------------------|
| x-on:evaluate | Fires when a star is clicked in interactive mode |

## Validation Constraints

- The `evaluate-method` attribute must not be blank.
- The `quantity` must be 5 or less.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Personalization

```php
TallStackUi::customize()
    ->rating()
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name | Purpose                                                         |
|------------|-----------------------------------------------------------------|
| wrapper    | Outer container layout (flex, gap, alignment)                   |
| button     | Star button interaction styles (cursor, hover scale transition) |
| text       | Text label styles next to the stars                             |
| star       | Inactive/unselected star color                                  |
| sizes.xs   | Extra-small icon dimensions                                     |
| sizes.sm   | Small icon dimensions                                           |
| sizes.md   | Medium icon dimensions                                          |
| sizes.lg   | Large icon dimensions                                           |
