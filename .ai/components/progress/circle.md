# TallStackUI: Progress Circle

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A circular progress indicator rendered as an SVG donut chart with a centered percentage label. Supports solid and light color styles, four sizes, and customizable stroke widths.

## Basic Usage

```blade
<x-progress.circle percent="75" />
```

```blade
<x-progress.circle percent="50" color="green" lg />
```

```blade
<x-progress.circle percent="90" color="blue" light xs />
```

With custom stroke widths:

```blade
<x-progress.circle percent="60" stroke-circle="3" stroke-percent="4" />
```

With footer:

```blade
<x-progress.circle percent="100" color="green">
    <x-slot:footer>
        <p class="mt-1 text-center text-sm text-green-600">Done</p>
    </x-slot:footer>
</x-progress.circle>
```

## Attributes

| Attribute      | Type              | Default   | Description                                   |
|----------------|-------------------|-----------|-----------------------------------------------|
| percent        | string\|int\|null | null      | Current progress value (0-100)                |
| xs             | bool\|null        | null      | Extra-small circle size                       |
| sm             | bool\|null        | null      | Small circle size                             |
| md             | bool\|null        | null      | Medium circle size (default)                  |
| lg             | bool\|null        | null      | Large circle size                             |
| solid          | bool              | true      | Uses the solid color style                    |
| light          | bool              | false     | Uses the light color style                    |
| color          | string\|null      | 'primary' | Color theme (e.g., primary, red, green, blue) |
| stroke-circle  | int\|null         | 2         | Stroke width of the background circle track   |
| stroke-percent | int\|null         | 2         | Stroke width of the progress arc              |
| size-circle    | int\|null         | 36        | SVG viewBox size for the circle               |
| footer         | slot\|null        | null      | Content rendered below the circle             |

## Slots

| Slot   | Description                                            |
|--------|--------------------------------------------------------|
| footer | Content rendered below the circular progress indicator |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->progress('circle')
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name      | Purpose                                                     |
|-----------------|-------------------------------------------------------------|
| wrapper         | Absolute-positioned container centering the percentage text |
| text            | Percentage text font and color                              |
| background      | Background circle track color                               |
| sizes.text.xs   | Text size for extra-small variant                           |
| sizes.text.sm   | Text size for small variant                                 |
| sizes.text.md   | Text size for medium variant                                |
| sizes.text.lg   | Text size for large variant                                 |
| sizes.circle.xs | Circle dimensions for extra-small variant                   |
| sizes.circle.sm | Circle dimensions for small variant                         |
| sizes.circle.md | Circle dimensions for medium variant                        |
| sizes.circle.lg | Circle dimensions for large variant                         |
