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

| Attribute | Type | Default | Description                                                                                                                                                |
|-----------|------|---------|------------------------------------------------------------------------------------------------------------------------------------------------------------|
| reverse   | bool | false   | Mirrors the visual stacking direction. The DOM order is preserved — only the painted overlap is reversed, so the first avatar in markup ends on the right. |

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

Useful for activity feeds where the most-recent contributor is the first array entry but should appear on the right of the stack.

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
| wrapper.reverse | Extra classes applied only when `reverse` is enabled to flip the overlap                                                                                                               |
