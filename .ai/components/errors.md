# TallStackUI: Errors

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A validation error summary component that displays all (or filtered) Laravel validation errors in a styled list with a title, icon, optional close button, and footer slot. Automatically hides when no errors are present.

## Basic Usage

```blade
<x-errors />
```

```blade
<x-errors title="Please fix the following:" color="red" close />
```

```blade
<x-errors :only="['email', 'password']" icon="exclamation-triangle" color="yellow">
    <x-slot:footer>
        <p class="text-sm mt-2">Need help? <a href="/support">Contact support</a></p>
    </x-slot:footer>
</x-errors>
```

## Attributes

| Attribute | Type                | Default            | Description                                                                                         |
|-----------|---------------------|--------------------|-----------------------------------------------------------------------------------------------------|
| title     | string\|null        | Translated default | Title displayed at the top of the error box; supports `:count` placeholder for the number of errors |
| only      | string\|array\|null | null               | Filter to show errors for specific field names only                                                 |
| icon      | string\|null        | 'x-circle'         | Heroicon name displayed next to the title                                                           |
| color     | string\|null        | 'red'              | Color theme for the error box                                                                       |
| close     | bool                | false              | Shows a dismiss button to hide the error box                                                        |

## Slots

| Slot   | Description                                                                  |
|--------|------------------------------------------------------------------------------|
| footer | Content rendered below the error list; accepts plain string or ComponentSlot |

## Validation Constraints

- The `title` attribute cannot be empty.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->errors()
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name    | Purpose                                                   |
|---------------|-----------------------------------------------------------|
| wrapper       | Outer container with rounded corners, padding, and shadow |
| title.wrapper | Title bar flex layout with bottom border                  |
| title.text    | Title text font and inline-flex alignment                 |
| title.icon    | Title icon dimensions                                     |
| body.wrapper  | Error list container with left margin and padding         |
| body.list     | Unordered list styles (disc, spacing)                     |
| close         | Close button icon dimensions                              |
| slots.footer  | Footer slot top margin                                    |
