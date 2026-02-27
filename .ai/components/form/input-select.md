# TallStackUI: Input Select

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A combined text input with a native select dropdown side-by-side. Supports icons, prefix/suffix addons, clearable values, and can be used as a compound input with a select on the left or right side. Typically used with `<x-select.native>` as a left or right addon.

## Basic Usage

```blade
<x-input.select wire:model="phone" label="Phone Number" icon="phone">
    <x-slot:left>
        <x-select.native wire:model="countryCode" :options="['+1', '+44', '+55']" side="left" />
    </x-slot:left>
</x-input.select>
```

```blade
<x-input.select wire:model="amount" label="Amount" clearable>
    <x-slot:right>
        <x-select.native wire:model="currency" :options="['USD', 'EUR', 'BRL']" side="right" />
    </x-slot:right>
</x-input.select>
```

```blade
<x-input.select wire:model="search" label="Search" icon="magnifying-glass" position="left" />
```

## Attributes

| Attribute  | Type                        | Default | Description                                     |
|------------|-----------------------------|---------|-------------------------------------------------|
| label      | string\|ComponentSlot\|null | null    | Label text displayed above the input            |
| hint       | string\|ComponentSlot\|null | null    | Hint text displayed below the input             |
| icon       | string\|null                | null    | Icon name displayed inside the input            |
| clearable  | bool\|null                  | null    | Shows a clear button when the input has a value |
| invalidate | bool\|null                  | null    | Prevents displaying validation error messages   |
| position   | string\|null                | 'left'  | Icon position: 'left' or 'right'                |

## Slots

| Slot   | Description                                                              |
|--------|--------------------------------------------------------------------------|
| left   | Left addon content, typically a `<x-select.native>` with `side="left"`   |
| right  | Right addon content, typically a `<x-select.native>` with `side="right"` |
| prefix | Text or component slot rendered inside the input on the left             |
| suffix | Text or component slot rendered inside the input on the right            |

## Validation Constraints

- The `icon` cannot be used with `prefix` or `suffix` at the same side (icon on left conflicts with prefix; icon on right conflicts with suffix).
- The `clearable` cannot be used with `suffix`.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('input.select')
    ->block('input.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                | Purpose                                                               |
|---------------------------|-----------------------------------------------------------------------|
| input.wrapper.first       | Outer wrapper with ring and focus styles for addon mode               |
| input.wrapper.second      | Inner input wrapper with focus ring styles                            |
| input.wrapper.round.left  | Removes left border radius when left addon is present                 |
| input.wrapper.round.right | Removes right border radius when right addon is present               |
| input.wrapper.error       | Error state ring styles for the wrapper                               |
| input.base                | Core input element styles (border, background, padding)               |
| input.slot                | Prefix/suffix text slot styles                                        |
| input.color.base          | Default ring and text colors                                          |
| input.color.background    | Background color for normal state                                     |
| input.color.disabled      | Background color for disabled/readonly state                          |
| input.paddings.prefix     | Padding when prefix text is present                                   |
| input.paddings.suffix     | Padding when suffix text is present                                   |
| input.paddings.left       | Padding when icon is on the left                                      |
| input.paddings.right      | Padding when icon is on the right                                     |
| input.paddings.clearable  | Padding adjustment when both icon and clearable are used on the right |
| icon.wrapper              | Icon container positioning                                            |
| icon.paddings.left        | Icon left-side padding                                                |
| icon.paddings.right       | Icon right-side padding                                               |
| icon.size                 | Icon dimensions                                                       |
| icon.color                | Icon color                                                            |
| clearable.wrapper         | Clearable button container positioning                                |
| clearable.padding         | Clearable button padding                                              |
| clearable.size            | Clearable icon dimensions                                             |
| clearable.color           | Clearable icon hover color                                            |
| error                     | Error state ring and text styles                                      |
