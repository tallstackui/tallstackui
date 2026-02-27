# TallStackUI: Select Native

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A native HTML `<select>` component with support for simple arrays, key-value option arrays, grouped options (optgroups), and inline `<option>` elements via the default slot. Can also be used as a side addon within `<x-input.select>`.

## Basic Usage

```blade
<x-select.native wire:model="country" label="Country" :options="['Brazil', 'USA', 'Canada']" />
```

```blade
<x-select.native wire:model="status" label="Status"
    :options="[['label' => 'Active', 'value' => 1], ['label' => 'Inactive', 'value' => 0]]"
    select="label:label|value:value" />
```

```blade
<x-select.native wire:model="color" label="Color">
    <option value="">Select a color...</option>
    <option value="red">Red</option>
    <option value="blue">Blue</option>
</x-select.native>
```

## Attributes

| Attribute  | Type              | Default | Description                                                                                 |
|------------|-------------------|---------|---------------------------------------------------------------------------------------------|
| label      | string\|null      | null    | Label text displayed above the select                                                       |
| hint       | string\|null      | null    | Hint text displayed below the select                                                        |
| options    | Collection\|array | []      | Array of options. Supports simple values, key-value arrays, or grouped arrays.              |
| select     | string\|null      | null    | Mapping string for option keys in format `label:key\|value:key\|description:key\|image:key` |
| selectable | array\|null       | []      | Resolved selectable keys (label, value) used internally after parsing `select`              |
| invalidate | bool\|null        | null    | Prevents displaying validation error messages                                               |
| grouped    | bool\|null        | null    | Enables optgroup rendering when option values are arrays                                    |

## Slots

| Slot      | Description                                                 |
|-----------|-------------------------------------------------------------|
| (default) | Inline `<option>` elements rendered when `options` is empty |

## Field Mapping

When using multidimensional arrays with different key names, use the `select` attribute to map fields:

```blade
<x-select.native :options="[
    ['name' => 'TALL', 'id' => 1],
    ['name' => 'LIVT', 'id' => 2],
]" select="label:name|value:id" />
```

Format: `label:key|value:key|description:key|image:key`

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->select('native')
    ->block('input.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name             | Purpose                                                        |
|------------------------|----------------------------------------------------------------|
| wrapper                | Outer wrapper container                                        |
| input.wrapper          | Select element focus ring and rounded styles                   |
| input.base             | Core select element styles (border, background, padding, text) |
| input.color.base       | Default ring and text colors                                   |
| input.color.background | Background color for normal state                              |
| input.color.disabled   | Background color for disabled/readonly state                   |
| input.slot             | Slot text styles (prefix/suffix area)                          |
| input.round.left       | Removes left border radius when used as right-side addon       |
| input.round.right      | Removes right border radius when used as left-side addon       |
| input.borderless       | Removes ring/border when used as a side addon                  |
| error                  | Error state ring and text styles                               |
