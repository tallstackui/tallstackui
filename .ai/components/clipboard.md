# TallStackUI: Clipboard

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

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

## Attributes

| Attribute | Type         | Default                            | Description                                                  |
|-----------|--------------|------------------------------------|--------------------------------------------------------------|
| label     | string\|null | null                               | Label displayed above the input (input mode only)            |
| hint      | string\|null | null                               | Hint text displayed below the input (input mode only)        |
| text      | string\|null | null                               | The text content to be copied to clipboard                   |
| icon      | bool         | null                               | Switches to icon-only mode instead of the default input mode |
| left      | bool         | false                              | Places the copy button on the left side of the input         |
| secret    | bool         | false                              | Masks the input as a password field                          |
| icons     | array\|null  | ['copy' => null, 'copied' => null] | Custom icon names for the copy and copied states             |

## Slots

| Slot      | Description                                                 |
|-----------|-------------------------------------------------------------|
| (default) | Fallback text content when `text` attribute is not provided |

## Validation Constraints

- The `text` content cannot be empty (checked at runtime via the ClipboardRuntime class). The text can be provided via the `text` attribute or the default slot.
- The translation keys `button.copy` and `button.copied` in `ts-ui::messages.clipboard` must not be blank.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->clipboard()
    ->block('input.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name               | Purpose                                         |
|--------------------------|-------------------------------------------------|
| input.wrapper            | Input mode flex container                       |
| input.buttons.base       | Copy button base styles (both sides)            |
| input.buttons.left       | Left-side button border radius                  |
| input.buttons.right      | Right-side button border radius                 |
| input.buttons.icon.class | Button icon color and dimensions                |
| input.base               | Input field base styles                         |
| input.color.base         | Input text and ring color                       |
| input.color.background   | Input background color                          |
| input.color.disabled     | Disabled state color styles for the input       |
| input.sides.left         | Input border radius when button is on the left  |
| input.sides.right        | Input border radius when button is on the right |
| icon.wrapper             | Icon-only mode wrapper (inline-flex, cursor)    |
| icon.icons.copy.name     | Default icon name for the copy state            |
| icon.icons.copy.class    | Copy icon color and dimensions                  |
| icon.icons.copied.name   | Default icon name for the copied state          |
| icon.icons.copied.class  | Copied icon color and dimensions                |
