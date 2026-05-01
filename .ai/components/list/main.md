# TallStackUI: List

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A card-shaped, action-oriented list component for browsing or managing a collection of items. Each row displays a name, an optional caption (or arbitrary inline content), and an optional ellipsis-vertical menu trigger that opens a per-row dropdown. Optional client-side search filters rows by name and caption. Renders an optional `<x-label>` above the box and `<x-hint>` below.

## Basic Usage

Slot-based composition (recommended for static or `@foreach`-driven lists with per-item Blade):

```blade
<x-list label="Tags" hint="Manage your tags here.">
    <x-list.items name="general" caption="1 server">
        <x-slot:menu>
            <x-dropdown.items text="Edit" wire:click="edit('general')" />
            <x-dropdown.items text="Delete" wire:click="delete('general')" />
        </x-slot:menu>
    </x-list.items>
    <x-list.items name="production" caption="2 servers">
        <x-slot:menu>
            <x-dropdown.items text="Edit" wire:click="edit('production')" />
        </x-slot:menu>
    </x-list.items>
</x-list>
```

Inside a `@foreach` (still slot mode):

```blade
<x-list label="Tags" searchable>
    @foreach ($tags as $tag)
        <x-list.items :name="$tag->name" :caption="$tag->servers_count.' server(s)'">
            <x-slot:menu>
                <x-dropdown.items text="Edit" wire:click="edit({{ $tag->id }})" />
                <x-dropdown.items text="Delete" wire:click="delete({{ $tag->id }})" />
            </x-slot:menu>
        </x-list.items>
    @endforeach
</x-list>
```

Data-driven mode using `:items` and `@interact('item_menu', $item)` for per-row menus (mirrors `<x-table>`):

```blade
<x-list :items="$tags" searchable height="60">
    @interact('item_menu', $item)
        <x-dropdown.items text="Edit" wire:click="edit({{ $item['id'] }})" />
        <x-dropdown.items text="Delete" wire:click="delete({{ $item['id'] }})" />
    @endinteract
</x-list>
```

Custom empty state:

```blade
<x-list label="Tags">
    <x-slot:empty>
        <div class="flex flex-col items-center gap-2 py-4">
            <p class="text-sm">No tags configured yet.</p>
            <x-button text="Create tag" wire:click="create" />
        </div>
    </x-slot:empty>
</x-list>
```

## Attributes

| Attribute          | Type                   | Default                            | Description                                                                                                |
|--------------------|------------------------|------------------------------------|------------------------------------------------------------------------------------------------------------|
| label              | string\|null           | null                               | Renders as `<x-label>` above the box                                                                       |
| hint               | string\|null           | null                               | Renders as `<x-hint>` below the box                                                                        |
| searchable         | bool                   | false                              | When true, renders a search input above the items (Alpine-filtered, client-side)                           |
| search-placeholder | string\|null           | i18n `ts-ui::messages.list.search` | Placeholder for the search input; falls back to translation key                                            |
| height             | string\|null           | null                               | Tailwind size token (`'40'`, `'60'`, `'80'`, `'96'`) → `max-h-{n} overflow-y-auto`. `null` = no max height |
| :items             | array\|Arrayable\|null | null                               | Data-driven mode. Iterated to render rows. When set, default slot children are ignored                     |

## Slots

| Slot                            | Description                                                                                                               |
|---------------------------------|---------------------------------------------------------------------------------------------------------------------------|
| (default)                       | `<x-list.items>` children (slot mode). Ignored when `:items` is set                                                       |
| `<x-slot:empty>`                | Replaces the default empty state (shown when no items registered or search filters all). Falls back to i18n if not passed |
| `@interact('item_menu', $item)` | (Data-driven only) Blade injected into the dropdown menu for each row. Sees `$item` (the current array entry) in scope    |

## Item shape (data-driven)

Each entry in `:items` (array, Collection, or any `Arrayable`) is read via `data_get`. Recognized keys:

| Key       | Type         | Required | Description                                                           |
|-----------|--------------|----------|-----------------------------------------------------------------------|
| `name`    | string       | yes      | Bold leading text                                                     |
| `caption` | string\|null | no       | Inline secondary text                                                 |
| ...       | mixed        | no       | Additional keys are accessible inside `@interact('item_menu', $item)` |

## Search behavior

When `searchable` is true, the component initializes a small Alpine store (`tallstackui_list`) inside the box. Each row registers itself on init via `register(name, caption)`. The search input binds to a debounced (`150ms`) `search` term. Each row applies `x-show="match(name, caption)"`, hiding rows whose `name` and `caption` don't include the term (case-insensitive). The empty state binds to `x-show="!hasResults"`, which is true whenever zero registered items match the current term (or when no items are registered at all).

Filtering is **purely client-side** — the search input is **not** wired to Livewire by default. To filter server-side, the consumer can wrap `<x-list>` inside their Livewire component and re-pass a filtered `:items` collection on each search change.

## Validation

The component validates at render time:

- `height` must be one of `'40'`, `'60'`, `'80'`, `'96'` (or `null`).
- Each item in `:items` must include a non-empty `name` (string).
- Each `<x-list.items>` must include a non-empty `name` attribute.

Failures throw `InvalidArgumentException` (wrapped by Blade as `ViewException`).

## Composition with `<x-label>` and `<x-hint>`

When `label` is set, the component renders `<x-label>` internally with `scope="list.label"`. When `hint` is set, renders `<x-hint>` with `scope="list.hint"`. Customizations targeted to those scoped instances do not affect standalone `<x-label>`/`<x-hint>` usages.

## Soft customization scopes

| Scope             | Target                                                                                                                                                                                          |
|-------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `list.label`      | The internal `<x-label>` rendered when `label` is set                                                                                                                                           |
| `list.hint`       | The internal `<x-hint>` rendered when `hint` is set                                                                                                                                             |
| `list.items.menu` | The internal `<x-floating>` rendered for each row's menu (NB: the floating panel class is overridden via the `menu.floating` block on `<x-list.items>` and not by floating's own customization) |

The per-row menu is rendered via an internal floating dropdown (NOT `<x-dropdown>`); customize its blocks via `<x-list.items>` directly (`menu.trigger`, `menu.icon`, `menu.floating`, etc. — see [list/items.md](items.md)).

See [`.ai/soft-customization-internal-scopes.md`](../../soft-customization-internal-scopes.md) for the canonical list of all internal scopes.

## Performance

The component is designed for **small to medium lists (~500 items)**. Two optimizations are baked in:

- **`content-visibility: auto`** on every `<x-list.items>` row — the browser skips layout and paint for rows scrolled off-screen. With `contain-intrinsic-size: auto 2.5rem` reserving the row's intrinsic height, scrolling stays smooth even with hundreds of items. Native browser feature; no JS overhead.
- **Debounced search input** (`150ms`) so reactive filtering doesn't fire on every keystroke.

For lists exceeding ~500 items, **filter server-side** via Livewire instead of relying on Alpine's client-side filter:

```blade
{{-- in your Livewire view --}}
<x-list :items="$this->filteredItems" searchable />
```

```php
class MyComponent extends Component
{
    public string $search = '';

    #[Computed]
    public function filteredItems(): Collection
    {
        return Tag::query()
            ->when(filled($this->search), fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->limit(100)
            ->get();
    }
}
```

```blade
{{-- bind the search input to a Livewire property; the component re-renders on every search change --}}
<x-list searchable wire:model.live.debounce.300ms="search" :items="$this->filteredItems" />
```

The slot mode `<x-list><x-list.items>...</x-list>` cannot be virtualized because each row is server-rendered Blade. For thousands of dynamically-loaded items, a paginated `<x-table>` is the appropriate component — `<x-list>` is intentionally lighter and aimed at curated/short lists.
