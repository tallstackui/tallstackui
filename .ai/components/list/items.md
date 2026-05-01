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

When `<x-slot:menu>` is provided, the row renders a self-contained dropdown menu (NOT `<x-dropdown>`) with a borderless `ellipsis-vertical` trigger and a narrow (`w-44`) floating panel pinned at `z-40` so Dialog/Modal/Slide/Toast overlays (all `z-50`) always render above it. The menu auto-closes when a `<x-dropdown.items>` entry is selected (via the `select` event) or when the user clicks outside.

## Validation

- `name` must be a non-empty string.

Failures throw `InvalidArgumentException` (wrapped by Blade as `ViewException`).

## Performance

Each row applies `content-visibility: auto` + `contain-intrinsic-size: auto 2.5rem`. The browser skips layout/paint for rows scrolled off-screen, keeping scroll smooth even with hundreds of items. The intrinsic-size hint reserves ~40px per row so the scrollbar doesn't jump as rows are activated. See the parent [`<x-list>` docs](main.md) for guidance on lists larger than ~500 items.

## Soft customization

The menu trigger and floating panel are exposed as customization blocks under the `list.items` namespace:

| Block           | Default                                                                                                                                        |
|-----------------|------------------------------------------------------------------------------------------------------------------------------------------------|
| `wrapper`       | Row layout + `content-visibility:auto` + `contain-intrinsic-size:auto 2.5rem`                                                                  |
| `menu.wrapper`  | `shrink-0`                                                                                                                                     |
| `menu.trigger`  | Borderless icon button styling                                                                                                                 |
| `menu.icon`     | `size-5`                                                                                                                                       |
| `menu.floating` | `absolute z-40 w-44` + border + `bg-white` / `dark:bg-dark-700` + `shadow-md` + `rounded-md` (the entire wrapper class for the floating panel) |

Override via `TallStackUi::customize()->list('items')->block('menu.floating', '...')` for richer customization.

The internal `<x-floating>` is invoked with `scope="list.items.menu"` so any future Floating customization blocks can be targeted at this scope without affecting standalone `<x-floating>` usages. (The floating's `wrapper` class is currently overridden via the `menu.floating` block above and not via the floating's own customization.)

## Constraint

`<x-list.items>` is an internal child component — it expects the parent `<x-list>` Alpine scope to be present. Using it standalone will fail with Alpine errors (no `register()`, `match()` available). Always wrap inside `<x-list>`.
