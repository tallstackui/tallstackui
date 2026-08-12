# TallStackUI: Clipboard

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A copy-to-clipboard component with two display modes: an input field with a copy button, or a standalone icon-only trigger. Supports label, hint, secret masking, button placement, and custom copy/copied icons.

## Basic Usage

```blade
<x-clipboard text="npm install tallstackui" />
```

```blade
<x-clipboard text="sk_live_abc123" label="API Key" hint="Click to copy" secret left />
```

```blade
<x-clipboard text="Copy this text" icon />
```

```blade
<x-clipboard text="Copy this text" :icon="['copy' => 'pencil', 'copied' => 'check']" />
```

## Attributes

| Attribute | Type              | Default | Description                                                  |
|-----------|-------------------|---------|--------------------------------------------------------------|
| label     | string\|null      | null    | Label displayed above the input (input mode only)            |
| hint      | string\|null      | null    | Hint text displayed below the input (input mode only)        |
| text      | string\|null      | null    | The text content to be copied to clipboard                   |
| icon      | bool\|array\|null | null    | Switches to icon-only mode instead of the default input mode |
| left      | bool\|null        | false   | Places the copy button on the left side of the input         |
| secret    | bool\|null        | false   | Masks the input as a password field                          |

## The `icon` attribute

`icon` both switches the display mode and carries the icon names, so there is a
single attribute to reason about:

```blade
{{-- icon mode, with the default clipboard/document-check pair --}}
<x-clipboard text="TallStackUI" icon />

{{-- icon mode, with custom icons --}}
<x-clipboard text="TallStackUI" :icon="['copy' => 'pencil', 'copied' => 'check']" />

{{-- either key can be omitted, falling back to the default of that state --}}
<x-clipboard text="TallStackUI" :icon="['copy' => 'pencil']" />

{{-- input mode --}}
<x-clipboard text="TallStackUI" />
<x-clipboard text="TallStackUI" :icon="false" />
```

An array turns icon mode on by itself: passing `icon` alongside it is allowed but
redundant. Only `false` and `null` fall back to the input mode.

## Slots

| Slot      | Description                                                 |
|-----------|-------------------------------------------------------------|
| (default) | Fallback text content when `text` attribute is not provided |

## Validation Constraints

- The `text` content cannot be empty (checked at runtime via the ClipboardRuntime class). The text can be provided via the `text` attribute or the default slot.
- The `icon` array only accepts the keys `copy` and `copied`. Any other key raises `InvalidArgumentException`.
- The translation keys `button.copy` and `button.copied` in `ts-ui::messages.clipboard` must not be blank.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->clipboard()
    ->block('input.base', 'your-tailwind-classes');
```

### Where the outline lives

In input mode a single element draws the border of the whole control: the outer
wrapper, through `wrapper.base`. The input and the button draw none, and the line
between them is a border on one side of the button, carried by `input.buttons.left`
or `input.buttons.right` depending on where the button sits.

Restyling the outline therefore means targeting `wrapper.base`, not the input:

```php
TallStackUi::customize()
    ->clipboard()
    ->block('wrapper.base')
    ->replace('ring-gray-200', 'ring-gray-300');
```

The focus ring lives there too, as `focus-within:ring-2`, so it answers to the
input and to the copy button alike.

### Available Blocks

| Block Name               | Purpose                                                            |
|--------------------------|--------------------------------------------------------------------|
| wrapper.spacing-top      | Spacing between the label and the control                          |
| wrapper.base             | The control outline: radius, ring and focus ring (input mode only) |
| input.wrapper            | Input mode flex container                                          |
| input.buttons.base       | Copy button base styles (both sides)                               |
| input.buttons.left       | Left-side button radius and the divider facing the input           |
| input.buttons.right      | Right-side button radius and the divider facing the input          |
| input.buttons.icon.class | Button icon color and dimensions                                   |
| input.base               | Input field base styles                                            |
| input.color.base         | Input text color                                                   |
| input.color.background   | Input background color                                             |
| input.color.disabled     | Disabled state color styles for the input                          |
| input.sides.left         | Input border radius when button is on the left                     |
| input.sides.right        | Input border radius when button is on the right                    |
| icon.wrapper             | Icon-only mode wrapper (inline-flex, cursor)                       |
| icon.icons.copy.name     | Default icon name for the copy state                               |
| icon.icons.copy.class    | Copy icon color and dimensions                                     |
| icon.icons.copied.name   | Default icon name for the copied state                             |
| icon.icons.copied.class  | Copied icon color and dimensions                                   |
