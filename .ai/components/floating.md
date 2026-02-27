# TallStackUI: Floating

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

> **Internal Component:** This component is used internally by other TallStackUI components (such as Dropdown and Submenu) and is not typically used directly.

A floating panel utility component powered by Alpine.js `x-anchor` for positioning relative to a reference element. Provides automatic show/hide behavior with click-outside and Escape key dismissal, plus configurable transitions and positioning.

## Basic Usage

```blade
<div x-data="{ show: false }">
    <button x-ref="anchor" x-on:click="show = !show">Toggle</button>

    <x-floating x-show="show" x-anchor="$refs.anchor" position="bottom-start" offset="8">
        <div class="p-4">Floating content</div>
    </x-floating>
</div>
```

## Attributes

| Attribute  | Type                | Default      | Description                                                       |
|------------|---------------------|--------------|-------------------------------------------------------------------|
| offset     | string\|null        | '10'         | Distance in pixels between the anchor and the floating panel      |
| position   | string\|null        | 'bottom-end' | Anchor position relative to the reference element                 |
| transition | ComponentSlot\|null | null         | Custom transition slot to override default enter/leave animations |
| footer     | ComponentSlot\|null | null         | Footer content slot                                               |

## Slots

| Slot       | Description                                 |
|------------|---------------------------------------------|
| (default)  | Main content of the floating panel          |
| transition | Custom Alpine.js transition directives      |
| footer     | Footer content rendered after the main slot |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->floating()
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name | Purpose                                                                        |
|------------|--------------------------------------------------------------------------------|
| wrapper    | Floating panel container with background, border, rounded corners, and z-index |
