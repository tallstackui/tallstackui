# TallStackUI: Link

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A styled anchor link component with color theming, text formatting options (bold, underline, italic), optional icons, query string building, and fragment anchors. Supports Livewire's wire:navigate for SPA-style navigation.

## Basic Usage

```blade
<x-link text="Documentation" href="/docs" />
```

```blade
<x-link text="External Link" href="https://example.com" blank bold underline />
```

```blade
<x-link text="Settings" href="/settings" icon="cog-6-tooth" color="gray" />
```

```blade
<x-link href="/users" :query="['page' => 2, 'sort' => 'name']" fragment="results" />
```

## Attributes

| Attribute      | Type                    | Default   | Description                                                  |
|----------------|-------------------------|-----------|--------------------------------------------------------------|
| text           | string\|null            | null      | Link text (falls back to slot, then href)                    |
| href           | string\|null            | null      | Target URL (required unless `fragment` is provided)          |
| color          | string\|null            | 'primary' | Color theme (e.g., primary, red, green, gray)                |
| xs             | string\|null            | null      | Extra-small text size                                        |
| sm             | string\|null            | null      | Small text size                                              |
| md             | string\|null            | null      | Medium text size (default)                                   |
| lg             | string\|null            | null      | Large text size                                              |
| query          | array\|Collection\|null | null      | Query parameters appended to the URL                         |
| fragment       | string\|null            | null      | URL fragment/anchor appended with #                          |
| icon           | string\|null            | null      | Heroicon name displayed alongside the text                   |
| position       | string\|null            | 'left'    | Icon position relative to text: 'left' or 'right'            |
| blank          | bool                    | null      | Opens the link in a new tab (target="_blank")                |
| bold           | bool                    | null      | Applies bold font weight                                     |
| underline      | bool                    | null      | Applies underline text decoration                            |
| italic         | bool                    | null      | Applies italic text style                                    |
| colorless      | bool                    | null      | Removes color styling from the link text                     |
| navigate       | bool                    | null      | Enables Livewire's wire:navigate for SPA-style navigation    |
| navigate-hover | bool                    | null      | Enables Livewire's wire:navigate.hover for prefetch on hover |

## Slots

| Slot      | Description                                      |
|-----------|--------------------------------------------------|
| (default) | Custom content, used when `text` is not provided |

## Validation Constraints

- The `href` attribute is required when no `fragment` is provided.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->link()
    ->block('bold', 'your-tailwind-classes');
```

### Available Blocks

| Block Name | Purpose                                                 |
|------------|---------------------------------------------------------|
| bold       | Font weight class applied when bold is enabled          |
| underline  | Text decoration class applied when underline is enabled |
| italic     | Text style class applied when italic is enabled         |
| icon.base  | Flex layout for icon + text alignment                   |
| icon.size  | Icon dimensions                                         |
| sizes.xs   | Extra-small text size                                   |
| sizes.sm   | Small text size                                         |
| sizes.md   | Medium text size                                        |
| sizes.lg   | Large text size                                         |
