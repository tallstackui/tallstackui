# TallStackUI: Button Group

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A purely visual container that groups one or more `<x-button>` siblings into a cohesive segment, collapsing adjacent borders, rounding only the outer corners, and isolating focus rings so they sit above neighbours. Mirrors the segmented "button group" pattern from Flowbite and TailwindUI.

The group does **not** propagate size or style to its children, and is **not** a segmented control (no selected state, no `wire:model` binding). Each grouped `<x-button>` keeps full control over its own `color`, `size`, `style`, `icon`, `tooltip`, `loading`, `href`, etc.

Only `<x-button>` is officially supported as a child (works for both regular buttons and the anchor variant via `href`). `<x-button.circle>` is **not** intended for grouping. Mixing styles or colors across children is allowed and rendered as declared.

## Basic Usage

```blade
<x-button.group>
    <x-button text="Years" outline color="secondary" sm />
    <x-button text="Months" outline color="secondary" sm />
    <x-button text="Days" outline color="secondary" sm />
</x-button.group>
```

Vertical orientation:

```blade
<x-button.group vertical label="View options">
    <x-button text="List" icon="bars-4" outline color="secondary" />
    <x-button text="Grid" icon="squares-2x2" outline color="secondary" />
    <x-button text="Map" icon="map" outline color="secondary" />
</x-button.group>
```

Mixed styles within the same group (form-footer pattern):

```blade
<x-button.group>
    <x-button text="Cancel" outline color="secondary" />
    <x-button text="Discard" outline color="secondary" />
    <x-button text="Save" color="primary" />
</x-button.group>
```

Pagination-style icon-only pair with ARIA label:

```blade
<x-button.group label="Pagination">
    <x-button icon="chevron-left" outline color="secondary" sm />
    <x-button icon="chevron-right" outline color="secondary" sm />
</x-button.group>
```

## Attributes

| Attribute | Type         | Default | Description                                                                             |
|-----------|--------------|---------|-----------------------------------------------------------------------------------------|
| vertical  | bool         | false   | When true, lays children top-to-bottom and rounds only top/bottom corners               |
| label     | string\|null | null    | Sets `aria-label` on the wrapper. The wrapper always carries `role="group"` regardless. |

## Slots

| Slot      | Description                                                                                 |
|-----------|---------------------------------------------------------------------------------------------|
| (default) | One or more `<x-button>` children. Rendering of each child is unchanged by the parent group |

## Caveats

- `<x-button flat>` removes the border from the button. Inside a group, the `-ml-px` (or `-mt-px`) overlap collapses into nothing and the dividers between flat siblings disappear. Use `solid`, `outline`, or `light` for the intended visual.
- `<x-button.circle>` is not visually compatible with the group layout and should not be used as a child.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->button('group')
    ->block('wrapper.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                | Purpose                                                                                          |
|---------------------------|--------------------------------------------------------------------------------------------------|
| wrapper.base              | Base group styles (`isolate inline-flex shadow-xs`, focus z-index lift, child position relative) |
| wrapper.layout.horizontal | Horizontal-only rounding/offset rules applied when `vertical` is false                           |
| wrapper.layout.vertical   | Vertical-only rounding/offset rules applied when `vertical` is true                              |
