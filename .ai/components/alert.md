# TallStackUI: Alert

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A dismissible alert component for displaying contextual messages with optional title, icon, and footer. Supports solid, light, and outline color styles.

## Basic Usage

```blade
<x-alert text="Your changes have been saved." />
```

```blade
<x-alert title="Success" text="Your account has been created." color="green" icon="check-circle" close />
```

```blade
<x-alert color="yellow" light icon="exclamation-triangle">
    <x-slot:footer>
        <div class="mt-2 flex gap-2">
            <x-button text="Undo" xs />
        </div>
    </x-slot:footer>
    Please review your submission before continuing.
</x-alert>
```

## Attributes

| Attribute | Type         | Default   | Description                                                                                                                                               |
|-----------|--------------|-----------|-----------------------------------------------------------------------------------------------------------------------------------------------------------|
| title     | string\|null | null      | Bold heading displayed above the text                                                                                                                     |
| text      | string\|null | null      | Main alert message body                                                                                                                                   |
| icon      | string\|null | null      | Heroicon name displayed to the left of the content                                                                                                        |
| color     | string\|null | 'primary' | Color theme (e.g., primary, red, green, yellow)                                                                                                           |
| close     | bool         | false     | Shows a dismiss button to hide the alert                                                                                                                  |
| dismiss   | int\|null    | null      | Auto-dismiss the alert after N seconds                                                                                                                    |
| light     | bool         | false     | Uses the light color style variant                                                                                                                        |
| outline   | bool         | false     | Uses the outline color style variant                                                                                                                      |
| rounded   | string\|null | 'lg'      | Corner radius size: `xs`, `sm`, `md`, `lg`, `xl`                                                                                                          |
| square    | bool         | false     | Forces square corners (`rounded-none`); overrides `rounded` when both set                                                                                 |
| bordered  | string\|null | null      | Adds a thick side border. Format: `"left"`, `"right"`, or `"<side>:<color>"` (e.g., `"left:red"`). When the color is omitted, the alert's `color` is used |

## Auto-Dismiss

```blade
{{-- Disappears after 5 seconds --}}
<x-alert :dismiss="5" text="This alert will auto-dismiss." />

{{-- Auto-dismiss with manual close option --}}
<x-alert :dismiss="10" close text="Closes automatically or click X." />
```

## Rounded Corners

The `rounded` attribute controls the corner radius. Default is `lg`.

```blade
<x-alert rounded="xs" text="Extra small radius." />
<x-alert rounded="sm" text="Small radius." />
<x-alert rounded="md" text="Medium radius." />
<x-alert rounded="lg" text="Large radius (default)." />
<x-alert rounded="xl" text="Extra large radius." />
```

## Square Corners

Use `square` to remove the rounded corners entirely. When set, `rounded` is ignored.

```blade
<x-alert square text="No rounded corners." />
```

## Side Border (`bordered`)

Adds a thick (`4px`) side border on the left or right edge of the alert. Useful for callouts.

```blade
{{-- Side only — color inherited from the alert's `color` --}}
<x-alert color="red" light bordered="left" text="Inherits the red color." />
<x-alert color="green" light bordered="right" text="Inherits the green color." />

{{-- Explicit color via `<side>:<color>` syntax --}}
<x-alert color="primary" light bordered="left:blue" text="Explicit blue border." />
<x-alert color="primary" light bordered="right:rose" text="Explicit rose border." />

{{-- Combinable with `outline` and `square` --}}
<x-alert color="primary" outline bordered="left:amber" text="Outline + side border." />
<x-alert color="primary" light square bordered="left:violet" text="Square + side border." />
```

Accepts any of the 28 supported color names. Side must be `left` or `right`.

## Slots

| Slot      | Description                                         |
|-----------|-----------------------------------------------------|
| (default) | Main content body, used when `text` is not provided |
| footer    | Content rendered below the alert body               |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->alert()
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name       | Purpose                                                               |
|------------------|-----------------------------------------------------------------------|
| wrapper          | Outer container of the alert                                          |
| rounded.xs       | Corner radius classes for `rounded="xs"`                              |
| rounded.sm       | Corner radius classes for `rounded="sm"`                              |
| rounded.md       | Corner radius classes for `rounded="md"`                              |
| rounded.lg       | Corner radius classes for `rounded="lg"`                              |
| rounded.xl       | Corner radius classes for `rounded="xl"`                              |
| square           | Classes applied when the `square` attribute is set                    |
| bordered.left    | Border classes applied when `bordered="left"` (or `"left:<color>"`)   |
| bordered.right   | Border classes applied when `bordered="right"` (or `"right:<color>"`) |
| content.wrapper  | Flex container for icon, text, and close button                       |
| content.base     | Inner flex wrapper for icon and text                                  |
| text.title       | Title heading styles                                                  |
| text.description | Description paragraph styles                                          |
| close.wrapper    | Close button container                                                |
| close.size       | Close icon dimensions                                                 |
| icon.wrapper     | Icon container                                                        |
| icon.size        | Icon dimensions                                                       |
