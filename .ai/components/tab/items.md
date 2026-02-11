# TallStackUI: Tab Items

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 40+ Blade components for building modern web interfaces.

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

| Attribute | Type               | Default | Description                                                                |
|-----------|--------------------|---------|----------------------------------------------------------------------------|
| tab       | string\|null       | null    | Unique identifier for this tab (used to match `selected` on the parent)    |
| title     | string\|null       | null    | Display label in the tab navigation (falls back to `tab` value if not set) |
| left      | slot\|string\|null | null    | Content rendered to the left of the tab title in the navigation            |
| right     | slot\|string\|null | null    | Content rendered to the right of the tab title in the navigation           |

## Slots

| Slot      | Description                                                 |
|-----------|-------------------------------------------------------------|
| (default) | Content displayed when this tab is active                   |
| left      | Content rendered before the tab title in the tab navigation |
| right     | Content rendered after the tab title in the tab navigation  |
