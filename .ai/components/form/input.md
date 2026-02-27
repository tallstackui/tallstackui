# TallStackUI: Input

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A versatile text input component with support for icons, prefix/suffix addons (text, slots, or buttons), clearable values, and automatic zero-stripping for numeric inputs.

## Basic Usage

```blade
<x-input wire:model="name" label="Name" hint="Enter your full name" />
```

```blade
<x-input wire:model="email" label="Email" icon="envelope" />
```

```blade
<x-input wire:model="search" label="Search" icon="magnifying-glass" clearable />
```

```blade
<x-input wire:model="website" label="Website" prefix="https://" suffix=".com" />
```

## Attributes

| Attribute   | Type                        | Default | Description                                                  |
|-------------|-----------------------------|---------|--------------------------------------------------------------|
| label       | string\|ComponentSlot\|null | null    | Label text displayed above the input                         |
| hint        | string\|ComponentSlot\|null | null    | Hint text displayed below the input                          |
| icon        | string\|null                | null    | Icon name displayed inside the input                         |
| clearable   | bool\|null                  | null    | Shows a clear button when the input has a value              |
| invalidate  | bool\|null                  | null    | Prevents displaying validation error messages for this input |
| strip-zeros | bool\|null                  | null    | Strips leading zeros from the input value                    |
| position    | string\|null                | 'left'  | Icon position: 'left' or 'right'                             |
| prefix      | string\|ComponentSlot\|null | null    | Prefix content (text string or slot with button/component)   |
| suffix      | string\|ComponentSlot\|null | null    | Suffix content (text string or slot with button/component)   |

## Slots

| Slot   | Description                                                                                                                                                               |
|--------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| prefix | Prefix slot for rendering custom content (e.g., buttons) at the left side of the input. When using a ComponentSlot, the input renders in addon mode with button styling.  |
| suffix | Suffix slot for rendering custom content (e.g., buttons) at the right side of the input. When using a ComponentSlot, the input renders in addon mode with button styling. |

## Validation Constraints

- The `icon` cannot be used with `prefix` or `suffix` at the same side (icon on left conflicts with prefix; icon on right conflicts with suffix).
- The `clearable` cannot be used with `suffix`.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('input')
    ->block('input.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name               | Purpose                                                  |
|--------------------------|----------------------------------------------------------|
| input.wrapper            | Outer wrapper with ring and focus styles                 |
| input.base               | Core input element styles                                |
| input.slot               | Prefix/suffix text slot styles                           |
| input.color.base         | Default ring and text colors                             |
| input.color.background   | Background color for normal state                        |
| input.color.disabled     | Background color for disabled/readonly state             |
| input.paddings.prefix    | Padding when prefix text is present                      |
| input.paddings.suffix    | Padding when suffix text is present                      |
| input.paddings.left      | Padding when icon is on the left                         |
| input.paddings.right     | Padding when icon is on the right                        |
| input.paddings.clearable | Padding adjustment when both icon and clearable are used |
| input.addon.wrapper      | Wrapper for addon (button) mode                          |
| input.addon.round.left   | Left rounding removal for addon mode                     |
| input.addon.round.right  | Right rounding removal for addon mode                    |
| input.addon.error        | Error ring styles for addon wrapper                      |
| input.addon.button.base  | Base styles for addon button containers                  |
| input.addon.button.left  | Left addon button rounding                               |
| input.addon.button.right | Right addon button rounding                              |
| icon.wrapper             | Icon container positioning                               |
| icon.paddings.left       | Icon left-side padding                                   |
| icon.paddings.right      | Icon right-side padding                                  |
| icon.size                | Icon dimensions                                          |
| icon.color               | Icon color                                               |
| clearable.wrapper        | Clearable button container positioning                   |
| clearable.padding        | Clearable button padding                                 |
| clearable.size           | Clearable icon dimensions                                |
| clearable.color          | Clearable icon hover color                               |
| error                    | Error state ring and text styles                         |
