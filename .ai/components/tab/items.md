# TallStackUI: Tab Items

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A child component of `<x-tab>` that defines a single tab panel's content and optional left/right slot content displayed alongside the tab label in the navigation.

## Basic Usage

```blade
<x-tab selected="users">
    <x-tab.items tab="users" title="Users">
        <p>Users list goes here.</p>
    </x-tab.items>
    <x-tab.items tab="roles" title="Roles">
        <p>Roles list goes here.</p>
    </x-tab.items>
</x-tab>
```

With left and right decorations in the tab label:

```blade
<x-tab selected="inbox">
    <x-tab.items tab="inbox" title="Inbox">
        <x-slot:left>
            <x-icon icon="envelope" class="h-4 w-4" />
        </x-slot:left>
        <x-slot:right>
            <x-badge text="3" color="red" xs round />
        </x-slot:right>
        <p>Inbox content here.</p>
    </x-tab.items>
    <x-tab.items tab="sent" title="Sent">
        <x-slot:left>
            <x-icon icon="paper-airplane" class="h-4 w-4" />
        </x-slot:left>
        <p>Sent messages here.</p>
    </x-tab.items>
</x-tab>
```

## Attributes

| Attribute      | Type                        | Default | Description                                                                                             |
|----------------|-----------------------------|---------|---------------------------------------------------------------------------------------------------------|
| tab            | string\|null                | null    | Unique identifier for this tab (used to match `selected` on the parent)                                 |
| title          | string\|null                | null    | Display label in the tab navigation (falls back to `tab` value if not set)                              |
| href           | string\|null                | null    | URL that determines when this tab's content renders (compared to the page URL without the query string) |
| navigate       | bool\|null                  | null    | Use Livewire SPA navigation (`Livewire.navigate()`) when clicking this tab                              |
| navigate-hover | bool\|null                  | null    | Same as `navigate` but prefetches the URL on hover                                                      |
| left           | ComponentSlot\|string\|null | null    | Content rendered to the left of the tab title in the navigation                                         |
| right          | ComponentSlot\|string\|null | null    | Content rendered to the right of the tab title in the navigation                                        |

## Slots

| Slot      | Description                                                 |
|-----------|-------------------------------------------------------------|
| (default) | Content displayed when this tab is active                   |
| left      | Content rendered before the tab title in the tab navigation |
| right     | Content rendered after the tab title in the tab navigation  |

## Route-Based Rendering

When `href` is set, the tab's slot content only renders server-side if the page URL matches the `href` value. The comparison ignores the query string, so each tab needs its own path. On a Livewire update, the URL the page was rendered at is used, so the open panel survives re-renders of the component around the tabs. Tab headers always appear regardless of URL match. Clicking a tab with `href` navigates to that URL instead of switching client-side.

Blade evaluates the slot of every `<x-tab.items>` before the component decides whether to print it, so a Livewire component placed in an inactive tab is still mounted on the server and only its HTML is discarded. Mark heavy children `lazy` so that only the one inside the printed panel loads:

```blade
<x-tab.items tab="invoices" title="Invoices" :href="route('invoices.index')" navigate>
    <livewire:invoices.index lazy />
</x-tab.items>
```

With `navigate` (SPA navigation via Livewire):

```blade
<x-tab>
    <x-tab.items tab="users" title="Users" :href="route('users.index')" navigate>
        <livewire:users.index />
    </x-tab.items>
    <x-tab.items tab="invoices" title="Invoices" :href="route('invoices.index')" navigate>
        <livewire:invoices.index />
    </x-tab.items>
</x-tab>
```

With `navigate-hover` (prefetches on hover, then SPA navigation on click):

```blade
<x-tab>
    <x-tab.items tab="users" title="Users" :href="route('users.index')" navigate-hover>
        <livewire:users.index />
    </x-tab.items>
    <x-tab.items tab="invoices" title="Invoices" :href="route('invoices.index')" navigate-hover>
        <livewire:invoices.index />
    </x-tab.items>
</x-tab>
```

Without `navigate` or `navigate-hover` (plain `window.location.href` navigation):

```blade
<x-tab>
    <x-tab.items tab="users" title="Users" :href="route('users.index')">
        <livewire:users.index />
    </x-tab.items>
</x-tab>
```
