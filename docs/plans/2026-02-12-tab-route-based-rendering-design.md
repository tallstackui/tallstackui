# Tab Route-Based Rendering

## Problem

Tab content is rendered simultaneously on page load. For tabs containing heavy Livewire components, this causes unnecessary server-side rendering and resource consumption when only one tab is visible at a time.

## Solution

Add a `when` prop to `<x-tab.items>` that associates a tab with a URL. Only the tab whose `when` matches the current URL renders its slot content. Clicking other tabs navigates to their URL, with optional `wire:navigate` support.

## API

```html
<!-- With wire:navigate (SPA navigation) -->
<x-tab>
    <x-tab.items tab="users" title="Users" :when="route('users.index')" navigate>
        <livewire:users.index />
    </x-tab.items>
    <x-tab.items tab="invoices" title="Invoices" :when="route('invoices.index')" navigate>
        <livewire:invoices.index />
    </x-tab.items>
</x-tab>

<!-- With wire:navigate.hover (prefetch on hover + SPA navigation) -->
<x-tab>
    <x-tab.items tab="users" title="Users" :when="route('users.index')" navigate-hover>
        <livewire:users.index />
    </x-tab.items>
</x-tab>

<!-- Plain navigation (window.location.href) -->
<x-tab>
    <x-tab.items tab="users" title="Users" :when="route('users.index')">
        <livewire:users.index />
    </x-tab.items>
</x-tab>
```

## New Props on `<x-tab.items>`

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `when` | `?string` | `null` | URL that determines when content renders |
| `navigate` | `?bool` | `null` | Use `Livewire.navigate()` on click |
| `navigateHover` | `?bool` | `null` | Use `Livewire.navigate()` on click + prefetch on hover |

## Behavior

### Server-side (PHP)

- `TabItemsRuntime` compares `request()->url()` with `when` (normalized, trailing slash stripped)
- Match: slot renders, tab auto-selects via `x-init`
- No match: slot content NOT rendered (Livewire components not instantiated)
- The `<div>` wrapper always renders for Alpine `x-init` tab registration
- No `when`: works exactly as before (slot always renders)

### Client-side (Alpine)

- Tab headers always appear (all tabs registered in Alpine's `tabs` array)
- Click tab with `when` + `navigate`: `Livewire.navigate(url)`
- Click tab with `when` + `navigateHover`: `Livewire.navigate(url)` + `<link rel="prefetch">` on mouseenter
- Click tab with `when` only: `window.location.href = url`
- Click tab without `when`: current behavior (client-side switch)
- Mobile select dropdown: navigates when `when` is present
- Keypress Enter: same navigation behavior as click

### Auto-selection

The matching item auto-selects itself via `x-init`:
```javascript
x-init="tabs.push({...}); selected = @js($tab)"
```
This runs only when `$shouldRender && $when`, ensuring the URL-matched tab is selected on page load.

## Files Modified

1. `src/Components/Tab/Items/Component.php` - 3 new props: `when`, `navigate`, `navigateHover`
2. `src/Support/Runtime/Components/TabItemsRuntime.php` - URL matching, pass `shouldRender`/`when`/`navigate`/`navigateHover`
3. `src/resources/views/components/tab/items.blade.php` - Conditional slot, navigation data in Alpine, auto-select
4. `src/resources/views/components/tab/main.blade.php` - Navigation in click/keypress/select handlers, hover prefetch
5. `src/Components/Tab/FeatureTest.php` - New feature tests
6. `src/Components/Tab/BrowserTest.php` - New browser tests
7. `.ai/components/tab/` - Documentation

## Backward Compatibility

Fully backward compatible. Tabs without `when` work identically to before.
