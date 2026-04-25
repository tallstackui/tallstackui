# TallStackUI: Accordion

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A collapsible panel group for progressively disclosed content such as FAQs, configuration sections, or grouped detail views. By default the accordion opens one item at a time (opening another closes the current one), matches the Radix/shadcn single-open UX, and animates expansion via the `@alpinejs/collapse` plugin. Supports multi-open mode, flat visual style, chevron positioning, default-open items, rich triggers, and custom icons.

## Basic Usage

```blade
<x-accordion>
    <x-accordion.items title="What is TallStackUI?" id="faq-1">
        A suite of Blade components for the TALL stack.
    </x-accordion.items>
    <x-accordion.items title="Which Laravel versions are supported?" id="faq-2">
        Laravel 10, 11, 12, and 13.
    </x-accordion.items>
</x-accordion>
```

Allow multiple panels to stay open:

```blade
<x-accordion multiple>
    <x-accordion.items title="Feature A" id="a">Content A</x-accordion.items>
    <x-accordion.items title="Feature B" id="b">Content B</x-accordion.items>
</x-accordion>
```

Flat visual style (no outer border / shadow):

```blade
<x-accordion flat>
    <x-accordion.items title="First" id="first">No outer border.</x-accordion.items>
    <x-accordion.items title="Second" id="second">Item separators only.</x-accordion.items>
</x-accordion>
```

Chevron on the left:

```blade
<x-accordion chevron="left">
    <x-accordion.items title="Read more" id="left-1">
        The chevron sits before the title.
    </x-accordion.items>
</x-accordion>
```

Listening for events:

```blade
<x-accordion
    x-on:open="console.log('opened', $event.detail.id)"
    x-on:close="console.log('closed', $event.detail.id)"
>
    <x-accordion.items title="Watch me" id="evt-1">Content</x-accordion.items>
</x-accordion>
```

Binding events to a Livewire method:

```blade
<x-accordion x-on:open="$wire.onOpen($event.detail.id)">
    <x-accordion.items title="Question" id="q1">Answer.</x-accordion.items>
</x-accordion>
```

## Attributes

| Attribute | Type         | Default   | Description                                                                       |
|-----------|--------------|-----------|-----------------------------------------------------------------------------------|
| multiple  | bool\|null   | false     | When set, multiple items can be open at the same time. Default is single-open.    |
| flat      | bool\|null   | false     | Removes the outer border, rounding, and shadow. Item separators remain.           |
| chevron   | string\|null | `'right'` | Position of the trigger's trailing icon. Accepts `'right'` (default) or `'left'`. |

## Slots

| Slot      | Description                                         |
|-----------|-----------------------------------------------------|
| (default) | `<x-accordion.items>` children defining each panel. |

## Events

Events are dispatched as `CustomEvent`s on the accordion's root `<div>` and bubble up. Listen with `x-on:open` / `x-on:close` on the `<x-accordion>` itself or on any ancestor.

| Event      | Detail         | Description                                      |
|------------|----------------|--------------------------------------------------|
| x-on:open  | `{id: string}` | Fired when an item opens. Payload identifies it. |
| x-on:close | `{id: string}` | Fired when an item closes.                       |

## Single-Open vs Multiple

Single-open mode (default): opening an item automatically closes any other currently-open sibling. Use the `multiple` attribute to opt out:

```blade
<x-accordion>
    <x-accordion.items title="A" id="a">Opening B will close this.</x-accordion.items>
    <x-accordion.items title="B" id="b">Opening A will close this.</x-accordion.items>
</x-accordion>

<x-accordion multiple>
    <x-accordion.items title="A" id="a">Stays open independently.</x-accordion.items>
    <x-accordion.items title="B" id="b">Stays open independently.</x-accordion.items>
</x-accordion>
```

## Validation

- `chevron` must be `'left'` or `'right'`. Any other value raises `InvalidArgumentException`.

## Soft Customization

Soft customization allows you to override the default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->accordion()
    ->block('wrapper.base', 'your-tailwind-classes');
```

### Scoped Customization

```php
TallStackUi::customize('accordion', scope: 'muted')
    ->block('wrapper.bordered', 'border border-dashed border-gray-400 rounded-xl');
```

Then in Blade:

```blade
<x-accordion scope="muted">
    <x-accordion.items title="Muted" id="m-1">Content</x-accordion.items>
</x-accordion>
```

### Available Blocks

| Block Name                   | Purpose                                                                           |
|------------------------------|-----------------------------------------------------------------------------------|
| wrapper.base                 | Base container styles applied to every accordion (background, width).             |
| wrapper.bordered             | Border, radius, shadow, and overflow clipping applied when `flat` is not set.     |
| wrapper.chevron-left-cascade | Tailwind arbitrary-variant class that flips trigger layout when `chevron="left"`. |
