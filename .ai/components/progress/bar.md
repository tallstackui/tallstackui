# TallStackUI: Progress Bar

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A horizontal progress bar component with three display variations: simple inline bar, floating label above the bar, and a title variation with label and percentage side by side. Supports solid and light color styles, four sizes, and an optional footer slot.

## Basic Usage

Simple progress bar:

```blade
<x-progress percent="65" />
```

Progress bar with title:

```blade
<x-progress percent="80" title="Upload Progress" color="green" />
```

Floating percentage label:

```blade
<x-progress percent="45" floating color="blue" lg />
```

Without percentage text:

```blade
<x-progress percent="30" without-text xs />
```

With footer content:

```blade
<x-progress percent="100" title="Complete" color="green">
    <x-slot:footer>
        <p class="mt-1 text-sm text-green-600">All files uploaded.</p>
    </x-slot:footer>
</x-progress>
```

## Attributes

| Attribute    | Type              | Default   | Description                                                    |
|--------------|-------------------|-----------|----------------------------------------------------------------|
| percent      | string\|int\|null | null      | Current progress value (0-100)                                 |
| title        | string\|null      | null      | Label text displayed above the bar (activates title variation) |
| xs           | bool\|null        | null      | Extra-small bar height                                         |
| sm           | bool\|null        | null      | Small bar height                                               |
| md           | bool\|null        | null      | Medium bar height (default)                                    |
| lg           | bool\|null        | null      | Large bar height                                               |
| simple       | bool              | false     | Forces simple variation (default when no title or floating)    |
| floating     | bool              | false     | Shows the percentage in a floating label above the bar         |
| solid        | bool              | true      | Uses the solid color style                                     |
| light        | bool              | false     | Uses the light color style                                     |
| color        | string\|null      | 'primary' | Color theme (e.g., primary, red, green, blue, yellow)          |
| without-text | bool              | false     | Hides the percentage text inside the simple bar                |
| footer       | slot\|null        | null      | Content rendered below the progress bar                        |

## Slots

| Slot   | Description                             |
|--------|-----------------------------------------|
| footer | Content rendered below the progress bar |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->progress()
    ->block('simple.wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name        | Purpose                                                      |
|-------------------|--------------------------------------------------------------|
| simple.wrapper    | Simple variation outer bar container with rounded background |
| simple.progress   | Simple variation inner progress fill bar                     |
| floating.wrapper  | Floating label bubble container                              |
| floating.progress | Floating variation outer bar container                       |
| floating.float    | Floating variation inner progress fill bar                   |
| title.wrapper     | Title variation header flex container                        |
| title.title       | Title text styling                                           |
| title.bar         | Title variation inner progress fill bar                      |
| title.progress    | Title variation outer bar container                          |
| title.percent     | Percentage text styling next to the title                    |
| sizes.xs          | Extra-small height class                                     |
| sizes.sm          | Small height class                                           |
| sizes.md          | Medium height class                                          |
| sizes.lg          | Large height class                                           |
