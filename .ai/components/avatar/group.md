# TallStackUI: Avatar Group

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 40+ Blade components for building modern web interfaces.

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

This component has no custom attributes. Standard HTML attributes are passed through to the wrapper element.

## Slots

| Slot      | Description                                    |
|-----------|------------------------------------------------|
| (default) | Avatar components to be displayed in the group |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Personalization

```php
TallStackUi::customize()
    ->avatar()
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name | Purpose                                                                 |
|------------|-------------------------------------------------------------------------|
| wrapper    | Flex container with negative horizontal spacing for overlapping avatars |
