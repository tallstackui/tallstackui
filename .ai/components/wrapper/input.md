# TallStackUI: Wrapper Input

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

> **Internal Component:** This component is used internally by other TallStackUI components and is not typically used directly.

A utility wrapper that provides consistent label, hint, and error display around form input components. Handles label rendering (as string or custom slot), hint text below the input, and Livewire validation error messages.

## Basic Usage

```blade
<x-wrapper.input label="Email" property="email" error>
    <input type="email" wire:model="email" class="w-full rounded-md border px-3 py-2" />
</x-wrapper.input>
```

```blade
<x-wrapper.input label="Username" hint="Choose a unique username" property="username" error>
    <input type="text" wire:model="username" class="w-full rounded-md border px-3 py-2" />
</x-wrapper.input>
```

## Attributes

| Attribute  | Type               | Default | Description                                                             |
|------------|--------------------|---------|-------------------------------------------------------------------------|
| property   | string\|null       | null    | Livewire property name used for error message display                   |
| label      | slot\|string\|null | null    | Label text or custom label slot rendered above the input                |
| id         | string\|null       | null    | HTML id linked to the label's `for` attribute                           |
| hint       | string\|null       | null    | Help text displayed below the input (hidden when error is shown)        |
| invalidate | bool\|null         | null    | Passes invalidate state through to label and error sub-components       |
| error      | bool               | false   | Enables Livewire validation error display below the input               |
| clearable  | bool\|null         | null    | Indicates clearable state (passed through for use by parent components) |

## Slots

| Slot      | Description                                                  |
|-----------|--------------------------------------------------------------|
| (default) | The form input element to wrap                               |
| label     | Custom label content rendered instead of a simple text label |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->wrapper('input')
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name | Purpose                                                        |
|------------|----------------------------------------------------------------|
| wrapper    | Container div with rounded corners and shadow around the input |
