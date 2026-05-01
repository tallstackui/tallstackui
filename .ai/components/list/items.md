# TallStackUI: List Items

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A row inside a `<x-list>`. Renders a bold `name`, an optional inline `caption`/default-slot content, and an optional ellipsis-vertical menu trigger that opens a dropdown with the consumer's menu items. **Must be used inside `<x-list>`** — relies on the parent's Alpine scope for search filtering.

## Basic Usage

Simple row with caption:

```blade
<x-list>
    <x-list.items name="general" caption="1 server" />
    <x-list.items name="production" caption="2 servers" />
</x-list>
```

Row with rich inline content (default slot replaces caption-as-text):

```blade
<x-list>
    <x-list.items name="active-feature">
        <x-badge color="green" round xs>active</x-badge>
    </x-list.items>
    <x-list.items name="staged-feature" caption="(rolling out)">
        {{-- caption falls into the default slot when present --}}
    </x-list.items>
</x-list>
```

Row with per-item menu:

```blade
<x-list>
    <x-list.items name="general" caption="1 server">
        <x-slot:menu>
            <x-dropdown.items text="Edit" wire:click="edit('general')" />
            <x-dropdown.items text="Delete" wire:click="delete('general')" />
        </x-slot:menu>
    </x-list.items>
</x-list>
```

## Attributes

| Attribute | Type         | Default | Description                                   |
|-----------|--------------|---------|-----------------------------------------------|
| name      | string       | —       | Bold leading text. **Required** (non-empty)   |
| caption   | string\|null | null    | Inline secondary text rendered after the name |

## Slots

| Slot            | Description                                                                                               |
|-----------------|-----------------------------------------------------------------------------------------------------------|
| (default)       | Inline content rendered after the name (badge, status indicator, custom text). Coexists with `caption`    |
| `<x-slot:menu>` | Dropdown items shown when the user clicks the ellipsis trigger. When omitted, the trigger is not rendered |

## Behavior

The row registers itself with the parent `<x-list>` at Alpine init time via `register(name, caption)`. This populates the parent's `items[]` array used by `match()` for search filtering and by `hasResults` for the empty state.

When `<x-slot:menu>` is provided, the row renders an internal `<x-dropdown position="bottom-end">` with a borderless `ellipsis-vertical` trigger button. The menu content is the slot content (typically `<x-dropdown.items>` entries with `wire:click` actions).

## Validation

- `name` must be a non-empty string.

Failures throw `InvalidArgumentException` (wrapped by Blade as `ViewException`).

## Soft customization scope

The `<x-dropdown>` rendered for the menu uses scope `list.items.menu`. Customizations targeted at this scope do not affect standalone `<x-dropdown>` usages.

## Constraint

`<x-list.items>` is an internal child component — it expects the parent `<x-list>` Alpine scope to be present. Using it standalone will fail with Alpine errors (no `register()`, `match()` available). Always wrap inside `<x-list>`.
