# TallStackUI: Dropdown

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A dropdown menu component that displays a floating panel of actions triggered by a text label, icon, or custom action slot. Supports configurable positioning via the Floating component and Alpine.js anchor.

## Basic Usage

```blade
<x-dropdown text="Options">
    <x-dropdown.items text="Edit" icon="pencil" />
    <x-dropdown.items text="Delete" icon="trash" separator />
</x-dropdown>
```

```blade
<x-dropdown icon="ellipsis-vertical" position="bottom-start">
    <x-dropdown.items text="View Details" href="/items/1" />
    <x-dropdown.items text="Settings" icon="cog-6-tooth" />
</x-dropdown>
```

Using a custom action trigger:

```blade
<x-dropdown position="bottom-end">
    <x-slot:action>
        <button x-on:click="show = !show" class="btn btn-primary">
            Custom Trigger
        </button>
    </x-slot:action>

    <x-dropdown.items text="Option A" />
    <x-dropdown.items text="Option B" />
</x-dropdown>
```

With a header slot:

```blade
<x-dropdown text="Account">
    <x-slot:header>
        <div class="px-2 py-1 text-sm font-semibold text-gray-700">
            Signed in as <strong>user@example.com</strong>
        </div>
    </x-slot:header>

    <x-dropdown.items text="Profile" icon="user" />
    <x-dropdown.items text="Sign Out" icon="arrow-right-on-rectangle" separator />
</x-dropdown>
```

## Attributes

| Attribute | Type         | Default      | Description                                                                                                                                                                                                                                                                                   |
|-----------|--------------|--------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| text      | string\|null | null         | Text label displayed as the dropdown trigger                                                                                                                                                                                                                                                  |
| icon      | string\|null | null         | Icon name displayed as the dropdown trigger (used when text is not set)                                                                                                                                                                                                                       |
| position  | string\|null | 'bottom-end' | Floating panel position relative to the trigger                                                                                                                                                                                                                                               |
| static    | bool\|null   | false        | When true, disables the click-outside auto-close animation                                                                                                                                                                                                                                    |
| xs        | bool\|null   | null         | Compact size: smaller paddings, font and icons on every item                                                                                                                                                                                                                                  |
| sm        | bool\|null   | null         | Small size: items shrink one step from the default                                                                                                                                                                                                                                            |
| md        | bool\|null   | null         | Medium size (the default content density)                                                                                                                                                                                                                                                     |
| lg        | bool\|null   | null         | Large size: roomier paddings, font and icons on every item                                                                                                                                                                                                                                    |
| width     | string\|null | null         | Floating panel width. Accepts `xxs`, `xs`, `sm`, `md`, `lg`, `xl`, `2xl`. When omitted, falls back to whichever size flag is active (`xs`, `sm`, `md`, or `lg`); when none of those is set, defaults to `md` (`w-56`). Pass `width` explicitly to decouple panel footprint from item density. |

## Slots

| Slot      | Description                                                     |
|-----------|-----------------------------------------------------------------|
| (default) | Dropdown menu items (typically `<x-dropdown.items>` components) |
| header    | Content rendered above the menu items                           |
| action    | Custom trigger element (replaces default text/icon trigger)     |

## Validation Constraints

- The `position` must be one of: auto, auto-start, auto-end, bottom, bottom-start, bottom-end, left, left-start, left-end, right, right-start, right-end, top, top-start, top-end.
- The `width` must be one of: `xxs`, `xs`, `sm`, `md`, `lg`, `xl`, `2xl`.

## Size and Width

The dropdown exposes two orthogonal axes:

- **Size** (boolean flags `xs`, `sm`, `md`, `lg`) controls the **content density** of every item — padding, font size, icon size. Cascades automatically to nested `<x-dropdown.items>` and `<x-dropdown.submenu>`.
- **Width** (string prop `width`) controls only the **floating panel width**. Accepts `xxs` (`w-32`), `xs` (`w-40`), `sm` (`w-48`), `md` (`w-56`), `lg` (`w-64`), `xl` (`w-72`), `2xl` (`w-80`). Defaults to the active size when not set.

```blade
<x-dropdown text="Account" xs>
    <x-dropdown.items text="Profile" icon="user" />
</x-dropdown>

<x-dropdown text="Account" lg width="2xl">
    <x-dropdown.items text="Profile" icon="user" />
</x-dropdown>

<x-dropdown text="Account" xs width="xxs">
    <x-dropdown.items text="Profile" />
</x-dropdown>
```

A nested `<x-dropdown.submenu>` inherits both axes from its parent dropdown via DOM ancestor lookup at Alpine `init` (no extra props required) — even though the submenu's floating panel is teleported to the document body.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->dropdown()
    ->block('wrapper.first', 'your-tailwind-classes');
```

### Available Blocks

| Block Name          | Purpose                                                  |
|---------------------|----------------------------------------------------------|
| wrapper.first       | Outer flex container                                     |
| wrapper.second      | Relative inline-block positioning container              |
| header.wrapper      | Header content container with margin                     |
| slot.wrapper        | Menu items container with overflow and rounding          |
| floating.default    | Floating panel base styles (background, border, shadow)  |
| floating.widths.xxs | Floating panel width when `width="xxs"` (`w-32`)         |
| floating.widths.xs  | Floating panel width when `width="xs"` (`w-40`)          |
| floating.widths.sm  | Floating panel width when `width="sm"` (`w-48`)          |
| floating.widths.md  | Floating panel width when `width="md"` (`w-56`, default) |
| floating.widths.lg  | Floating panel width when `width="lg"` (`w-64`)          |
| floating.widths.xl  | Floating panel width when `width="xl"` (`w-72`)          |
| floating.widths.2xl | Floating panel width when `width="2xl"` (`w-80`)         |
| action.wrapper      | Trigger button flex container                            |
| action.text         | Trigger text styles                                      |
| action.icon         | Trigger icon and chevron styles                          |
