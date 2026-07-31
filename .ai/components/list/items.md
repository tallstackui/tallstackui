# TallStackUI: List Items

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A row inside a `<x-list>`. Renders a bold `name`, an optional `caption` (plain text or arbitrary markup through its slot form), optional inline default-slot content, an optional `action` slot holding raw controls on the right, and an optional ellipsis-vertical menu trigger that opens a dropdown with the consumer's menu items. **Must be used inside `<x-list>`** — relies on the parent's Alpine scope for search filtering.

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

Caption carrying markup instead of plain text (slot form):

```blade
<x-list>
    <x-list.items name="general">
        <x-slot:caption>
            <x-badge text="1 server" color="green" sm />
        </x-slot:caption>
    </x-list.items>
    <x-list.items name="production">
        <x-slot:caption>
            <x-badge text="12 servers" color="red" sm />
        </x-slot:caption>
    </x-list.items>
</x-list>
```

Row with a raw control on the right (no dropdown chrome):

```blade
<x-list>
    <x-list.items name="general" caption="1 server">
        <x-slot:action>
            <x-button sm wire:click="deploy('general')">Deploy</x-button>
        </x-slot:action>
    </x-list.items>
</x-list>
```

Action and menu side by side — the action sits to the left of the ellipsis trigger:

```blade
<x-list>
    <x-list.items name="general" caption="1 server">
        <x-slot:action>
            <x-button sm wire:click="deploy('general')">Deploy</x-button>
        </x-slot:action>
        <x-slot:menu>
            <x-dropdown.items text="Edit" wire:click="edit('general')" />
            <x-dropdown.items text="Delete" wire:click="delete('general')" />
        </x-slot:menu>
    </x-list.items>
</x-list>
```

## Attributes

| Attribute | Type         | Default | Description                                                                                           |
|-----------|--------------|---------|-------------------------------------------------------------------------------------------------------|
| name      | string       | —       | Bold leading text. **Required** (non-empty)                                                           |
| caption   | string\|null | null    | Inline secondary text rendered after the name. HTML-escaped — use `<x-slot:caption>` for markup       |
| xs        | bool         | false   | Sets the menu **size** token to `xs` (`px-2 py-1 text-xs`)                                            |
| sm        | bool         | false   | Sets the menu **size** token to `sm` (`px-3 py-1.5 text-sm`). Same as the default when no flag is set |
| md        | bool         | false   | Sets the menu **size** token to `md` (`px-4 py-2 text-sm`)                                            |
| lg        | bool         | false   | Sets the menu **size** token to `lg` (`px-5 py-2.5 text-base`)                                        |
| width     | string\|null | `xxs`   | Floating panel **width** token: `xxs`, `xs`, `sm`, `md`, `lg`, `xl`, `2xl`                            |
| lazy      | bool\|null   | null    | **Internal.** Renders the row as an Alpine template for `<x-list lazy>`. Not meant to be set by hand  |

## Slots

| Slot               | Description                                                                                                                              |
|--------------------|------------------------------------------------------------------------------------------------------------------------------------------|
| (default)          | Inline content rendered after the name (badge, status indicator, custom text). Coexists with `caption`                                   |
| `<x-slot:caption>` | Renders arbitrary markup in the caption position. Takes precedence over the `caption` attribute and is **not** escaped                   |
| `<x-slot:action>`  | Raw content on the right side of the row — buttons, toggles, links. Rendered without the dropdown trigger. Coexists with `<x-slot:menu>` |
| `<x-slot:menu>`    | Dropdown items shown when the user clicks the ellipsis trigger. When omitted, the trigger is not rendered                                |

When `<x-slot:action>` and/or `<x-slot:menu>` are present, both are grouped inside a shared right-side wrapper (`content.aside`) so the row keeps a single `justify-between` split between the name/caption block and the controls. Neither slot renders the wrapper when both are absent.

## Behavior

The row registers itself with the parent `<x-list>` at Alpine init time via `register(name, caption)`. This populates the parent's `items[]` array used by `match()` for search filtering and by `hasResults` for the empty state.

When the caption comes from `<x-slot:caption>`, the value handed to `register()`/`match()` is a **plain-text projection** of the slot (tags stripped, whitespace collapsed), so search keeps matching the caption's visible text. A `<x-slot:caption><x-badge text="12 servers" /></x-slot:caption>` row still matches the term `servers`.

The row also carries `data-list-on`, an attribute bound to the same search predicate. Alpine removes it while the row is filtered out, and the parent's `items.wrapper` block keys its inter-row dividers on it (`[&>[data-list-on]~[data-list-on]]:border-t`). This is what keeps the first *visible* row free of a top border when the rows above it are hidden by a search — a DOM-position selector cannot do that, since `display: none` siblings still count for `+`/`:not(:first-child)`.

When `<x-slot:menu>` is provided, the row renders a self-contained dropdown menu (NOT `<x-dropdown>`) with a borderless `ellipsis-vertical` trigger and a floating panel pinned at `z-40` so Dialog/Modal/Slide/Toast overlays (all `z-50`) always render above it. The menu auto-closes when a `<x-dropdown.items>` entry is selected (via the `select` event) or when the user clicks outside.

### Size and width

The floating panel emits `data-tsui-dropdown-size` and `data-tsui-dropdown-width` so any `<x-dropdown.items>` and `<x-dropdown.submenu>` placed inside the slot resolve their padding, font-size, and icon-size against the same contract used by a standalone `<x-dropdown>` (selectors like `[[data-tsui-dropdown-size='md']_&]:px-4`).

**Size and width are independent.** When no flag is passed the resolved size is `sm` (suited to the compact rhythm of list rows) and the width is `xxs` (`w-32`) so the floating panel stays narrow next to the row. Changing one does not change the other — opt in to wider panels or larger menu items explicitly:

```blade
{{-- Larger menu items, panel stays narrow --}}
<x-list.items name="alpha" md>
    <x-slot:menu>
        <x-dropdown.items text="Edit" />
    </x-slot:menu>
</x-list.items>

{{-- Wider panel, items stay at the default sm size --}}
<x-list.items name="bravo" width="2xl">
    <x-slot:menu>
        <x-dropdown.items text="Edit description" />
    </x-slot:menu>
</x-list.items>

{{-- Independently tuned --}}
<x-list.items name="charlie" lg width="md">
    <x-slot:menu>
        <x-dropdown.items text="Edit" />
    </x-slot:menu>
</x-list.items>
```

| Width token | Panel width |
|-------------|-------------|
| `xxs`       | `w-32`      |
| `xs`        | `w-40`      |
| `sm`        | `w-48`      |
| `md`        | `w-56`      |
| `lg`        | `w-64`      |
| `xl`        | `w-72`      |
| `2xl`       | `w-80`      |

The size flags (`xs`, `sm`, `md`, `lg`) are mutually exclusive — the first truthy flag in the order `xs → md → lg` wins, otherwise the size resolves to `sm`. Setting `sm` explicitly is allowed for readability but produces the same result as omitting every flag. The `width` prop is validated against the seven tokens above; anything else throws `InvalidArgumentException`.

## Lazy template row

When the parent runs in [lazy mode](main.md#lazy-mode) it renders `<x-list.items lazy />` once, inside its `x-for` template. That row switches to `list/items-lazy.blade.php`: the name and caption become `x-text` bindings against the `item` of the loop, `data-list-name` becomes an `x-bind`, and `register()`/`match()` are dropped since the parent filters the array instead of the DOM.

It resolves `customization()` from this same component, so overrides of `wrapper`, `name` and `caption` reach the lazy rows unchanged. The slots do not — `caption`, `action`, `menu` and the default slot have no per-row Blade to render in this mode, which is why the parent rejects `lazy` together with any `@interact('item_*')` hook.

`lazy` also lifts the `name` requirement, since the name only exists on the client in that mode.

## Validation

- `name` must be a non-empty string, unless `lazy` is set.
- `width` must be one of `xxs`, `xs`, `sm`, `md`, `lg`, `xl`, `2xl`.

Failures throw `InvalidArgumentException` (wrapped by Blade as `ViewException`).

## Performance

Each row applies `content-visibility: auto` + `contain-intrinsic-size: auto 2.5rem`. The browser skips layout/paint for rows scrolled off-screen, keeping scroll smooth even with hundreds of items. The intrinsic-size hint reserves ~40px per row so the scrollbar doesn't jump as rows are activated. That addresses paint cost only — the row is still a Blade component per item. When the render cost itself is the problem, the parent's [lazy mode](main.md#lazy-mode) renders the rows from a JSON array instead.

## Soft customization

The menu trigger and floating panel are exposed as customization blocks under the `list.items` namespace:

| Block                 | Default                                                                                                         |
|-----------------------|-----------------------------------------------------------------------------------------------------------------|
| `wrapper`             | Row layout + `content-visibility:auto` + `contain-intrinsic-size:auto 2.5rem`                                   |
| `content.aside`       | `flex shrink-0 items-center gap-x-2` — right-side group holding the `action` slot and the menu trigger          |
| `menu.wrapper`        | `shrink-0`                                                                                                      |
| `menu.trigger`        | Borderless icon button styling                                                                                  |
| `menu.icon`           | `size-5`                                                                                                        |
| `menu.floating`       | `absolute z-40` + border + `bg-white` / `dark:bg-dark-700` + `rounded-md` (base wrapper for the floating panel) |
| `menu.widths.{token}` | `data-[tsui-dropdown-width='{token}']:w-{n}` conditional class per width token (`xxs`, `xs`, `sm`, `md`, …)     |

Override via `TallStackUi::customize()->list('items')->block('menu.floating', '...')` for richer customization, or override individual `menu.widths.*` blocks to retune a specific token.

The internal `<x-floating>` is invoked with `scope="list.items.menu"` so any future Floating customization blocks can be targeted at this scope without affecting standalone `<x-floating>` usages. (The floating's `wrapper` class is currently overridden via the `menu.floating` block above and not via the floating's own customization.)

## Constraint

`<x-list.items>` is an internal child component — it expects the parent `<x-list>` Alpine scope to be present. Using it standalone will fail with Alpine errors (no `register()`, `match()` available). Always wrap inside `<x-list>`.
