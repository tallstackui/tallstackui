# TallStackUI: Pin

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A PIN/OTP input component that renders individual character boxes with automatic focus navigation, paste support, optional numeric or letter-only masks, a static prefix, and a clear button.

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

## Attributes

| Attribute  | Type                        | Default | Description                                                                         |
|------------|-----------------------------|---------|-------------------------------------------------------------------------------------|
| label      | string\|ComponentSlot\|null | null    | Label text displayed above the pin input                                            |
| hint       | string\|ComponentSlot\|null | null    | Hint text displayed below the pin input                                             |
| length     | int\|null                   | null    | Number of pin input boxes (required)                                                |
| prefix     | string\|null                | null    | Static text prefix displayed before the pin boxes (max 3 characters)                |
| clear      | bool\|null                  | null    | Shows a clear button to reset all pin inputs                                        |
| invalidate | bool\|null                  | null    | Prevents displaying validation error messages                                       |
| numbers    | bool\|null                  | null    | Restricts input to numbers only (applies Alpine.js mask)                            |
| letters    | bool\|null                  | null    | Restricts input to letters only (applies Alpine.js mask)                            |
| smart      | bool\|null                  | null    | Enables smart paste: automatically distributes a pasted string across all pin boxes |

## Alpine.js Events

| Event       | Description                                |
|-------------|--------------------------------------------|
| x-on:filled | Triggered when all pin boxes are filled    |
| x-on:clear  | Triggered when the clear button is clicked |

## Validation Constraints

- The `length` is mandatory and must be set.
- The `prefix` must be 3 characters or less.

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

| Block Name             | Purpose                                                |
|------------------------|--------------------------------------------------------|
| wrapper                | Outer flex container for pin boxes                     |
| input.size.prefix      | Width of the prefix input box                          |
| input.size.base        | Width of each pin input box                            |
| input.base             | Core pin input styles (rounding, text alignment, font) |
| input.color.base       | Default border, ring, and text colors                  |
| input.color.background | Background color                                       |
| input.color.error      | Error state border, ring, and text colors              |
| button                 | Clear button icon size and color                       |
