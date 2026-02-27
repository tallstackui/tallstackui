# TallStackUI: Color

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A color picker component with two modes: a full Tailwind CSS palette picker with shade range slider, or a custom colors-only picker. Supports selectable-only mode, clearable values, and the ability to exclude specific colors or shade steps from the palette.

## Basic Usage

```blade
<x-color wire:model="color" label="Pick a Color" picker />
```

```blade
<x-color wire:model="color" label="Color" picker selectable />
```

```blade
<x-color wire:model="color"
         label="Brand Color"
         :colors="['#FF5733', '#33FF57', '#3357FF']" />
```

```blade
<x-color wire:model="color"
         label="Color"
         picker
         clearable
         :excluded-color="['slate', 'gray']"
         :excluded-step="['50', '950']" />
```

## Attributes

| Attribute      | Type                        | Default | Description                                                                                      |
|----------------|-----------------------------|---------|--------------------------------------------------------------------------------------------------|
| label          | string\|ComponentSlot\|null | null    | Label text displayed above the input                                                             |
| hint           | string\|ComponentSlot\|null | null    | Hint text displayed below the input                                                              |
| picker         | bool\|null                  | false   | Enables the full Tailwind CSS color palette with shade range slider                              |
| colors         | Collection\|array\|null     | null    | Array of custom hex color strings (must start with #)                                            |
| invalidate     | bool\|null                  | null    | Prevents displaying validation error messages                                                    |
| selectable     | bool\|null                  | null    | Makes the input read-only so colors can only be picked from the palette                          |
| clearable      | bool\|null                  | null    | Shows a clear button to reset the selected color                                                 |
| excluded-color | string\|array\|null         | null    | Tailwind color name(s) to exclude from the palette (e.g., 'slate', 'gray')                       |
| excluded-step  | string\|array\|null         | null    | Tailwind shade step(s) to exclude from the palette (e.g., '50', '950'). Only works with `picker` |

## Alpine.js Events

| Event    | Description                                         |
|----------|-----------------------------------------------------|
| x-on:set | Triggered when a color is selected from the palette |

## Validation Constraints

- All `colors` array entries must start with `#`.
- The `excluded-step` attribute can only be used with the `picker` attribute.
- The `excluded-color` values must be valid Tailwind CSS colors: slate, gray, zinc, neutral, stone, red, orange, amber, yellow, lime, green, emerald, teal, cyan, sky, blue, indigo, violet, purple, fuchsia, pink, rose, mauve, olive, mist, taupe.
- The `excluded-step` values must be valid Tailwind CSS shade steps: 50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950.

## Configuration

Custom color palettes can be configured in `config/tallstackui.php`:

```php
'color' => [
    'custom' => [],
],
```

## Event Payload Details

The `x-on:set` event detail contains the selected color string:

```blade
<x-color x-on:set="alert(`Selected Color: ${$event.detail.color}`)" />
```

`$event.detail.color` is a string like `"red-500"` or `"#ff0000"` depending on the selection.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('color')
    ->block('selected.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name         | Purpose                                               |
|--------------------|-------------------------------------------------------|
| selected.wrapper   | Color preview swatch container                        |
| selected.base      | Color preview swatch border and dimensions            |
| icon.class         | Palette toggle icon dimensions                        |
| icon.wrapper       | Icon and clearable button flex container              |
| floating.default   | Base floating panel positioning                       |
| floating.class     | Floating panel width and overflow                     |
| box.base           | Color palette box background, rounding, and scrollbar |
| box.range.wrapper  | Range slider container padding                        |
| box.range.base     | Range slider track styles                             |
| box.range.thumb    | Range slider thumb styles                             |
| box.button.wrapper | Color swatch grid container                           |
| box.button.color   | Individual color swatch button size and rounding      |
| box.button.icon    | Check icon size for selected color                    |
| clearable.button   | Clearable button cursor and hover color               |
| clearable.size     | Clearable icon dimensions                             |
