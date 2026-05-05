# TallStackUI: Badge

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

An inline badge component for labels, tags, and status indicators. Supports solid, light, and outline styles with optional icons and multiple sizes.

## Basic Usage

```blade
<x-badge text="New" />
```

```blade
<x-badge text="Pending" color="yellow" light />
```

```blade
<x-badge text="Settings" icon="cog-6-tooth" color="gray" outline round />
```

## Attributes

| Attribute | Type         | Default   | Description                                                                                                                                                                                                                                       |
|-----------|--------------|-----------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| text      | string\|null | null      | Text content of the badge                                                                                                                                                                                                                         |
| icon      | string\|null | null      | Heroicon name displayed alongside the text                                                                                                                                                                                                        |
| position  | string\|null | 'right'   | Icon position relative to text: 'left' or 'right'                                                                                                                                                                                                 |
| xs        | bool         | null      | Extra-small size (default)                                                                                                                                                                                                                        |
| sm        | bool         | null      | Small size                                                                                                                                                                                                                                        |
| md        | bool         | null      | Medium size                                                                                                                                                                                                                                       |
| lg        | bool         | null      | Large size                                                                                                                                                                                                                                        |
| color     | string\|null | 'primary' | Color theme (e.g., primary, red, green, yellow)                                                                                                                                                                                                   |
| square    | bool         | false     | Removes border radius for square corners                                                                                                                                                                                                          |
| round     | bool\|string | false     | When `true`, applies the historical fully-rounded (pill) shape. When set to `xs`, `sm`, `md`, `lg`, or `xl`, applies the matching `rounded-{size}` Tailwind utility. Without it, falls back to the default `rounded-md`. `square` overrides this. |
| solid     | bool         | true      | Uses the solid color style variant                                                                                                                                                                                                                |
| outline   | bool         | null      | Uses the outline color style variant                                                                                                                                                                                                              |
| light     | bool         | null      | Uses the light color style variant                                                                                                                                                                                                                |

## Slots

| Slot      | Description                                                              |
|-----------|--------------------------------------------------------------------------|
| (default) | Custom content, used when `text` is not provided                         |
| left      | Custom content rendered before the text (overrides left-positioned icon) |
| right     | Custom content rendered after the text (overrides right-positioned icon) |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->badge()
    ->block('wrapper.class', 'your-tailwind-classes');
```

### Available Blocks

| Block Name         | Purpose                                                       |
|--------------------|---------------------------------------------------------------|
| wrapper.class      | Base badge styles (inline-flex, border, padding, font)        |
| wrapper.sizes.xs   | Extra-small text size                                         |
| wrapper.sizes.sm   | Small text size                                               |
| wrapper.sizes.md   | Medium text size                                              |
| wrapper.sizes.lg   | Large text size                                               |
| clickable          | Cursor style applied when wire:click or x-on:click is present |
| icon               | Icon dimensions                                               |
| border.radius.xs   | Border radius applied when `round="xs"`                       |
| border.radius.sm   | Border radius applied when `round="sm"`                       |
| border.radius.md   | Border radius applied when `round="md"` (default fallback)    |
| border.radius.lg   | Border radius applied when `round="lg"`                       |
| border.radius.xl   | Border radius applied when `round="xl"`                       |
| border.radius.full | Fully rounded (pill) shape applied when `round` is `true`     |
