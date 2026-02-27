# TallStackUI: Wrapper Radio

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

> **Internal Component:** This component is used internally by other TallStackUI components and is not typically used directly.

A utility wrapper that provides consistent label positioning and error display around radio, checkbox, and toggle form components. Supports left or right label placement and start or middle vertical alignment.

## Basic Usage

```blade
<x-wrapper.radio label="Accept terms" position="right" property="terms" error>
    <input type="checkbox" wire:model="terms" class="rounded border-gray-300" />
</x-wrapper.radio>
```

```blade
<x-wrapper.radio label="Option A" position="left" alignment="start" id="option-a">
    <input type="radio" name="option" value="a" id="option-a" />
</x-wrapper.radio>
```

## Attributes

| Attribute  | Type         | Default  | Description                                                        |
|------------|--------------|----------|--------------------------------------------------------------------|
| property   | string\|null | null     | Livewire property name used for error message display              |
| label      | string\|null | null     | Label text displayed alongside the input                           |
| id         | string\|null | null     | HTML id linked to the label's `for` attribute                      |
| position   | string\|null | 'left'   | Label position relative to the input: `'left'` or `'right'`        |
| alignment  | string\|null | 'middle' | Vertical alignment of the label and input: `'start'` or `'middle'` |
| invalidate | bool\|null   | null     | Passes invalidate state through to the error sub-component         |
| error      | bool         | false    | Enables Livewire validation error display below the input          |

## Slots

| Slot      | Description                                          |
|-----------|------------------------------------------------------|
| (default) | The radio, checkbox, or toggle input element to wrap |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->wrapper('radio')
    ->block('wrapper.first', 'your-tailwind-classes');
```

### Available Blocks

| Block Name            | Purpose                                      |
|-----------------------|----------------------------------------------|
| wrapper.first         | Outer flex container for the label and input |
| wrapper.second.start  | Alignment wrapper for top-aligned layout     |
| wrapper.second.middle | Alignment wrapper for center-aligned layout  |
| label.wrapper         | Label element flex and cursor styling        |
| label.text            | Label text font size, weight, and color      |
| label.error           | Error state text color applied to the label  |
