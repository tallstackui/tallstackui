# TallStackUI: Button

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A versatile button component supporting solid, light, outline, and flat styles with optional icons, loading states, tooltips, and link behavior.

## Basic Usage

```blade
<x-button text="Save" />
```

```blade
<x-button text="Delete" color="red" outline icon="trash" />
```

```blade
<x-button text="Processing..." loading="save" color="green" />
```

```blade
<x-button text="Visit Site" href="https://example.com" flat />
```

## Attributes

| Attribute | Type         | Default   | Description                                                      |
|-----------|--------------|-----------|------------------------------------------------------------------|
| text      | string\|null | null      | Button label text                                                |
| icon      | string\|null | null      | Heroicon name displayed alongside the text                       |
| position  | string\|null | 'left'    | Icon position relative to text: 'left' or 'right'                |
| xs        | bool         | null      | Extra-small size                                                 |
| sm        | bool         | null      | Small size                                                       |
| md        | bool         | null      | Medium size (default)                                            |
| lg        | bool         | null      | Large size                                                       |
| color     | string\|null | 'primary' | Color theme (e.g., primary, red, green, yellow)                  |
| square    | string\|null | null      | Removes border radius for square corners                         |
| round     | string\|null | null      | Uses fully rounded (pill) border radius                          |
| block     | bool         | false     | Expands button to full width (`w-full`)                          |
| href      | string\|null | null      | When set, renders as an anchor tag instead of a button           |
| loading   | string\|null | null      | Livewire action name to show a loading spinner during execution  |
| delay     | string\|null | null      | Delay duration for the loading indicator (e.g., 'longest')       |
| solid     | bool         | true      | Uses the solid color style variant (default)                     |
| outline   | bool         | false     | Uses the outline color style variant                             |
| light     | bool         | false     | Uses the light color style variant                               |
| flat      | bool         | false     | Uses the flat color style variant (no border)                    |
| submit    | bool         | false     | Sets button type to 'submit' for form submission                 |
| unfocus   | bool         | false     | Hides the focus ring when activated by mouse (kept for keyboard) |
| tooltip   | string\|null | null      | Tooltip text shown on hover                                      |

## Slots

| Slot      | Description                                                           |
|-----------|-----------------------------------------------------------------------|
| (default) | Custom content, used when `text` is not provided                      |
| left      | Custom HTML rendered before the text (overrides left-positioned icon) |
| right     | Custom HTML rendered after the text (overrides right-positioned icon) |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->button()
    ->block('wrapper.class', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                    | Purpose                                                             |
|-------------------------------|---------------------------------------------------------------------|
| wrapper.class                 | Base button styles (inline-flex, border, transition, cursor states) |
| wrapper.sizes.xs              | Extra-small text and padding                                        |
| wrapper.sizes.sm              | Small text and padding                                              |
| wrapper.sizes.md              | Medium text and padding                                             |
| wrapper.sizes.lg              | Large text and padding                                              |
| wrapper.border.radius.rounded | Default rounded border radius                                       |
| wrapper.border.radius.circle  | Fully rounded (pill) border radius                                  |
| icon.sizes.xs                 | Extra-small icon dimensions                                         |
| icon.sizes.sm                 | Small icon dimensions                                               |
| icon.sizes.md                 | Medium icon dimensions                                              |
| icon.sizes.lg                 | Large icon dimensions                                               |
