# TallStackUI: Label

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A form label component that renders a `<label>` element with optional required-field asterisk support and error state styling. Appending ` *` to the label text automatically renders a styled red asterisk indicator.

## Basic Usage

```blade
<x-label label="Email" />
```

```blade
<x-label label="Password *" />
```

```blade
<x-label label="Username" id="username-input" :error="$errors->has('username')" />
```

## Attributes

| Attribute  | Type         | Default | Description                                                           |
|------------|--------------|---------|-----------------------------------------------------------------------|
| id         | string\|null | null    | The `for` attribute value, linking the label to a form element by ID  |
| label      | string\|null | null    | Label text to display. Append ` *` to show a red asterisk indicator.  |
| error      | bool\|null   | false   | Applies error state styling (red text color) to the label             |
| invalidate | bool\|null   | null    | Prevents the error style from being applied even when `error` is true |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('label')
    ->block('text', 'your-tailwind-classes');
```

### Available Blocks

| Block Name | Purpose                                                 |
|------------|---------------------------------------------------------|
| text       | Label text styles (font size, weight, color, margin)    |
| asterisk   | Required asterisk indicator styles (color, font weight) |
| error      | Error state text color override                         |
