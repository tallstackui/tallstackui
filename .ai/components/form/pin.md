# TallStackUI: Pin

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A PIN/OTP input component that renders individual character boxes with automatic focus navigation, paste support, optional numeric or letter-only restriction, a static prefix, a clear button, masked boxes, a visual separator and grouped boxes.

## Basic Usage

```blade
<x-pin wire:model="code" label="Verification Code" :length="6" />
```

```blade
<x-pin wire:model="otp" label="OTP" :length="4" numbers />
```

```blade
<x-pin wire:model="token" label="Token" :length="6" letters />
```

```blade
<x-pin wire:model="phone" label="Phone" :length="4" prefix="+1" clear />
```

```blade
<x-pin wire:model="code" label="Smart Code" :length="6" smart />
```

```blade
<x-pin wire:model="pin" label="PIN" :length="6" numbers password />
```

```blade
{{-- 123-456 --}}
<x-pin wire:model="code" label="Code" :length="6" numbers separator />
```

```blade
{{-- 12/3456 --}}
<x-pin wire:model="code" label="Code" :length="6" numbers separator="/" split="2" />
```

```blade
{{-- AB-CD-EF-GH --}}
<x-pin wire:model="code" label="Code" :length="8" letters separator split="2,4,6" />
```

```blade
<x-pin wire:model="code" label="Code" :length="6" numbers group />
```

```blade
{{-- [123]-[456] --}}
<x-pin wire:model="code" label="Code" :length="6" numbers group separator />
```

## Attributes

| Attribute  | Type                        | Default | Description                                                                                                                                              |
|------------|-----------------------------|---------|----------------------------------------------------------------------------------------------------------------------------------------------------------|
| label      | string\|ComponentSlot\|null | null    | Label text displayed above the pin input                                                                                                                 |
| hint       | string\|ComponentSlot\|null | null    | Hint text displayed below the pin input                                                                                                                  |
| length     | int\|null                   | null    | Number of pin input boxes (required)                                                                                                                     |
| prefix     | string\|null                | null    | Static text prefix displayed before the pin boxes (max 3 characters)                                                                                     |
| clear      | bool\|null                  | null    | Shows a clear button to reset all pin inputs                                                                                                             |
| invalidate | bool\|null                  | null    | Prevents displaying validation error messages                                                                                                            |
| numbers    | bool\|null                  | null    | Restricts input to numbers only (cannot be used with `letters`)                                                                                          |
| letters    | bool\|null                  | null    | Restricts input to letters only (cannot be used with `numbers`)                                                                                          |
| smart      | bool\|null                  | null    | Enables smart paste: automatically distributes a pasted string across all pin boxes                                                                      |
| disabled   | bool                        | false   | Locks every box and the clear button. The value is not submitted.                                                                                        |
| readonly   | bool                        | false   | Locks every box and the clear button. The value is still submitted.                                                                                      |
| password   | bool\|null                  | null    | Renders every box as `type="password"`, so the typed characters are masked                                                                               |
| separator  | bool\|string\|null          | null    | Renders a visual separator between the boxes. `true` uses `-`; a string (max 3 characters) replaces it. The separator never reaches the value            |
| split      | int\|string\|array\|null    | null    | Box indexes followed by a separator: `2` gives `12-3456`, `2,4` or `[2, 4]` gives `12-34-56`. Defaults to the middle of the length. Requires `separator` |
| group      | bool\|null                  | null    | Joins the boxes together with shared borders, rounding only the outer corners. With `separator`, each chunk becomes its own group                        |

## Alpine.js Events

| Event       | Description                                |
|-------------|--------------------------------------------|
| x-on:filled | Triggered when all pin boxes are filled    |
| x-on:clear  | Triggered when the clear button is clicked |

## Validation Constraints

- The `length` is mandatory and must be set.
- The `prefix` must be 3 characters or less.
- The `numbers` and `letters` cannot be used together.
- The `separator` must be 3 characters or less.
- The `split` requires the `separator` to be set.
- The `split` positions must be between 1 and the `length` minus one.

## Event Payload Details

```blade
<x-pin length="5" x-on:filled="alert(`Filled: ${$event.detail.model}`)" />
<x-pin length="5" clear x-on:clear="alert(`Cleared: ${$event.detail.model}`)" />
```

### Smart Auto-Submit

When `smart` is enabled, the form auto-submits when all pin fields are filled:

```blade
<form wire:submit="verify">
    <x-pin length="5" wire:model.live="pin" label="Enter your code" smart numbers />
</form>
```

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('pin')
    ->block('input.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name             | Purpose                                                               |
|------------------------|-----------------------------------------------------------------------|
| wrapper                | Outer flex container for pin boxes                                    |
| input.size.prefix      | Width of the prefix input box                                         |
| input.size.base        | Width of each pin input box                                           |
| input.base             | Core pin input styles (display, text alignment, font)                 |
| input.spacing          | Gap after each box (and after the last box of a group chunk)          |
| input.rounding         | Rounding of each box when not grouped                                 |
| input.group.base       | Stacking context so the focused grouped box paints over its neighbors |
| input.group.first      | Rounding of the first box of a group chunk                            |
| input.group.last       | Rounding of the last box of a group chunk                             |
| input.group.joined     | Negative margin that overlaps the shared border of grouped boxes      |
| input.color.base       | Default border, ring, and text colors                                 |
| input.color.background | Background color                                                      |
| input.color.error      | Error state border, ring, and text colors                             |
| separator              | Color, size and spacing of the separator                              |
| button                 | Clear button icon size and color                                      |
| input.locked           | Cursor and opacity applied while disabled or readonly                 |
