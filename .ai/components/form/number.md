# TallStackUI: Number

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A numeric input component with increment/decrement buttons, configurable min/max bounds, decimal step support, long-press acceleration, and optional centralized or chevron layouts.

## Basic Usage

```blade
<x-number wire:model="quantity" label="Quantity" />
```

```blade
<x-number wire:model="quantity" label="Quantity" :min="0" :max="100" :step="1" />
```

```blade
<x-number wire:model="price" label="Price" :min="0" :step="0.01" centralized />
```

```blade
<x-number wire:model="count" label="Count" :min="0" :max="10" chevron selectable />
```

## Attributes

| Attribute   | Type                        | Default | Description                                                            |
|-------------|-----------------------------|---------|------------------------------------------------------------------------|
| label       | string\|ComponentSlot\|null | null    | Label text displayed above the input                                   |
| hint        | string\|ComponentSlot\|null | null    | Hint text displayed below the input                                    |
| min         | int\|null                   | null    | Minimum allowed value                                                  |
| max         | int\|null                   | null    | Maximum allowed value                                                  |
| delay       | int\|null                   | 2       | Speed multiplier for long-press increment/decrement (lower is faster)  |
| selectable  | bool\|null                  | null    | Prevents keyboard input, allowing only button-based changes            |
| chevron     | bool\|null                  | false   | Uses chevron (up/down) icons instead of plus/minus icons               |
| invalidate  | bool\|null                  | null    | Prevents displaying validation error messages for this input           |
| centralized | bool\|null                  | null    | Centers the input text and places buttons on opposite sides            |
| step        | int\|float                  | 1       | Increment/decrement step value. Values less than 1 enable decimal mode |

## Additional Options

```blade
<!-- Centralized layout (buttons on both sides) -->
<x-number centralized />

<!-- Chevron style (up/down arrows instead of +/-) -->
<x-number chevron />

<!-- Selectable only (no manual typing) -->
<x-number selectable />

<!-- Delay in seconds for long-press acceleration -->
<x-number delay="1" min="1" max="10" />
```

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('number')
    ->block('input.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name             | Purpose                                      |
|------------------------|----------------------------------------------|
| input.wrapper          | Outer wrapper with ring and focus styles     |
| input.base             | Core input element styles                    |
| input.slot             | Slot text styles                             |
| input.color.base       | Default ring and text colors                 |
| input.color.background | Background color for normal state            |
| input.color.disabled   | Background color for disabled/readonly state |
| buttons.wrapper        | Buttons flex container                       |
| buttons.left.base      | Left (decrement) button layout and cursor    |
| buttons.left.size      | Left button icon dimensions                  |
| buttons.left.color     | Left button icon color                       |
| buttons.left.error     | Left button icon error color                 |
| buttons.right.base     | Right (increment) button layout and cursor   |
| buttons.right.size     | Right button icon dimensions                 |
| buttons.right.color    | Right button icon color                      |
| buttons.right.error    | Right button icon error color                |
| error                  | Error state ring and text styles             |
