# TallStackUI: Alert

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 40+ Blade components for building modern web interfaces.

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

| Attribute | Type         | Default   | Description                                        |
|-----------|--------------|-----------|----------------------------------------------------|
| title     | string\|null | null      | Bold heading displayed above the text              |
| text      | string\|null | null      | Main alert message body                            |
| icon      | string\|null | null      | Heroicon name displayed to the left of the content |
| color     | string\|null | 'primary' | Color theme (e.g., primary, red, green, yellow)    |
| close     | bool         | false     | Shows a dismiss button to hide the alert           |
| light     | bool         | false     | Uses the light color style variant                 |
| outline   | bool         | false     | Uses the outline color style variant               |

## Slots

| Slot      | Description                                         |
|-----------|-----------------------------------------------------|
| (default) | Main content body, used when `text` is not provided |
| footer    | Content rendered below the alert body               |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Personalization

```php
TallStackUi::personalize()
    ->alert()
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name       | Purpose                                         |
|------------------|-------------------------------------------------|
| wrapper          | Outer container of the alert                    |
| content.wrapper  | Flex container for icon, text, and close button |
| content.base     | Inner flex wrapper for icon and text            |
| text.title       | Title heading styles                            |
| text.description | Description paragraph styles                    |
| close.wrapper    | Close button container                          |
| close.size       | Close icon dimensions                           |
| icon.wrapper     | Icon container                                  |
| icon.size        | Icon dimensions                                 |
