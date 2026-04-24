# TallStackUI: Accordion Items

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A child component of `<x-accordion>` that defines a single collapsible panel. Each item has a trigger (clickable header) and a body (rich content). Supports per-item default-open state, custom Heroicon replacement, a full custom icon slot, and a rich trigger slot.

## Basic Usage

```blade
<x-accordion>
    <x-accordion.items title="Plain item" id="one">
        Plain content in the panel body.
    </x-accordion.items>
</x-accordion>
```

Item that starts expanded:

```blade
<x-accordion>
    <x-accordion.items title="Closed by default" id="closed">Content</x-accordion.items>
    <x-accordion.items title="Open by default" id="opened" open>
        This panel ships expanded.
    </x-accordion.items>
</x-accordion>
```

Custom Heroicon instead of the default chevron:

```blade
<x-accordion>
    <x-accordion.items title="Using plus-circle" id="icon-plus" icon="plus-circle">
        The icon still rotates 180° when the item opens.
    </x-accordion.items>
</x-accordion>
```

Fully custom icon via slot:

```blade
<x-accordion>
    <x-accordion.items title="Custom indicator" id="icon-slot-1">
        <x-slot:icon>
            <span class="rounded-full bg-primary-100 px-2 py-0.5 text-xs font-semibold text-primary-700">
                new
            </span>
        </x-slot:icon>
        Content.
    </x-accordion.items>
</x-accordion>
```

Rich trigger slot (replaces the plain `title`):

```blade
<x-accordion>
    <x-accordion.items id="user-1">
        <x-slot:trigger>
            <div class="flex items-center gap-3">
                <x-avatar sm color="primary" text="JD" />
                <div class="flex flex-col text-start">
                    <span class="text-sm font-semibold">John Doe</span>
                    <span class="text-xs text-gray-500">Product Manager</span>
                </div>
                <x-badge color="green" sm text="active" class="ms-auto" />
            </div>
        </x-slot:trigger>
        Profile details, contact info, recent activity.
    </x-accordion.items>
</x-accordion>
```

## Attributes

| Attribute | Type                        | Default | Description                                                                                                                                                                                                                                 |
|-----------|-----------------------------|---------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| title     | string\|null                | null    | Text label shown in the trigger when no `<x-slot:trigger>` is provided.                                                                                                                                                                     |
| open      | bool\|null                  | false   | When true, the item starts expanded.                                                                                                                                                                                                        |
| id        | string\|null                | null    | Stable identifier for this item. Used in event payloads (`$event.detail.id`) and test selectors. When omitted, a random identifier is generated (recommended: always pass an explicit `id` for stable behavior across Livewire re-renders). |
| icon      | ComponentSlot\|string\|null | null    | Replacement for the default chevron. Pass a Heroicon name (e.g. `"plus-circle"`) for a string value, or use `<x-slot:icon>` for full HTML control.                                                                                          |
| trigger   | ComponentSlot\|string\|null | null    | Full replacement for the trigger content. When set via `<x-slot:trigger>`, replaces the `title`.                                                                                                                                            |

## Slots

| Slot      | Description                                                                                                                            |
|-----------|----------------------------------------------------------------------------------------------------------------------------------------|
| (default) | Panel body content, shown when the item is open.                                                                                       |
| trigger   | Replaces the trigger area (normally the `title`). Useful for avatars, badges, multi-line headings.                                     |
| icon      | Replaces the default chevron with arbitrary HTML. Rotation-on-open styling is not applied to slot content — the slot is emitted as-is. |

## Icon Prop vs Icon Slot

The `icon` prop accepts two shapes:

- **String** (e.g. `icon="plus-circle"`): renders a Heroicon with the standard rotation transition when the item opens/closes.
- **Slot** (`<x-slot:icon>...`): renders the provided HTML exactly as-is. No rotation transform is applied — you control any open/closed state yourself, for example:

```blade
<x-accordion.items title="Toggle indicator" id="toggle-1">
    <x-slot:icon>
        <template x-if="isOpen(id)"><x-icon name="minus" class="h-4 w-4" /></template>
        <template x-if="!isOpen(id)"><x-icon name="plus" class="h-4 w-4" /></template>
    </x-slot:icon>
    Content.
</x-accordion.items>
```

## Test Selectors

Each item renders two stable `dusk` selectors derived from its `id`:

| Selector                              | Target                        |
|---------------------------------------|-------------------------------|
| `@tallstackui_accordion_trigger_{id}` | The clickable trigger button. |
| `@tallstackui_accordion_content_{id}` | The animated content panel.   |

Use them in Dusk tests:

```php
->click('@tallstackui_accordion_trigger_faq-1')
->waitForText('Expected body text')
```

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime.

### Customization

```php
TallStackUi::customize()
    ->accordion('items')
    ->block('item.trigger.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name          | Purpose                                                                 |
|---------------------|-------------------------------------------------------------------------|
| item.wrapper        | Per-item container (includes the inter-item divider).                   |
| item.trigger.base   | Base classes for the `<button>` trigger (layout, padding, transitions). |
| item.trigger.open   | Classes applied to the trigger when the item is open.                   |
| item.trigger.closed | Classes applied to the trigger when the item is closed.                 |
| item.content        | Padding and typography for the panel body.                              |
| item.icon.base      | Base sizing and transition for the chevron/icon.                        |
| item.icon.rotated   | Extra classes applied when the item is open (default: `rotate-180`).    |
