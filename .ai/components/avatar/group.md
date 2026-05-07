# TallStackUI: Avatar Group

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A wrapper component that stacks multiple avatar components together with overlapping negative spacing, creating a grouped avatar display commonly used for showing team members or participants.

## Basic Usage

```blade
<x-avatar.group>
    <x-avatar image="https://example.com/user1.jpg" sm />
    <x-avatar image="https://example.com/user2.jpg" sm />
    <x-avatar image="https://example.com/user3.jpg" sm />
</x-avatar.group>
```

```blade
<x-avatar.group>
    <x-avatar text="AB" color="blue" sm />
    <x-avatar text="CD" color="red" sm />
    <x-avatar text="+3" color="gray" sm />
</x-avatar.group>
```

## Attributes

| Attribute | Type | Default | Description                                                                                                                                             |
|-----------|------|---------|---------------------------------------------------------------------------------------------------------------------------------------------------------|
| reverse   | bool | false   | Inverts the overlap layering so the first avatar in markup paints on top. Visual left-to-right order matches the DOM order in both default and reverse. |

## Slots

| Slot      | Description                                    |
|-----------|------------------------------------------------|
| (default) | Avatar components to be displayed in the group |

## Reversed Stacking

```blade
<x-avatar.group reverse>
    <x-avatar text="A" color="indigo" sm />
    <x-avatar text="B" color="emerald" sm />
    <x-avatar text="C" color="amber" sm />
</x-avatar.group>
```

Without `reverse`, the last avatar in markup paints on top (the natural browser paint order). With `reverse`, the first avatar in markup paints on top and each subsequent avatar slides under the previous one. The visual left-to-right order matches the DOM order in both modes — only the overlap layering changes.

The z-stacking is explicit for the first six children (`z-50` → `z-10`); from the seventh onward they fall back to `z-0` and rely on natural paint order, which is rarely visible because of the heavy overlap.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->avatar('group')
    ->block('wrapper.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name      | Purpose                                                                                                                                                                                |
|-----------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| wrapper.base    | Inline-flex container with negative horizontal spacing for overlapping avatars; shrink-to-fit so the group stays anchored to the parent's flow start in both default and reverse modes |
| wrapper.reverse | When `reverse` is enabled: makes children `position: relative` and applies decreasing z-index to the first six (`z-50` → `z-10`) so the first avatar paints on top of the others       |
