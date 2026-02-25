# Card Component Events Design

**Date:** 2026-02-25
**Source:** [GitHub Discussion #1184](https://github.com/tallstackui/tallstackui/discussions/1184)

## Problem

The Card component with `minimize="mount"` (or `minimize` toggle) provides no way
to observe when the card is maximized, minimized, or closed. Users cannot trigger
actions (e.g., fetching data) in response to these state changes.

## Solution

Add 3 CustomEvent dispatches via Alpine.js `$watch` — following the existing
Modal/Slide pattern.

### Events

| Event      | Fired When                    |
|------------|-------------------------------|
| `minimize` | Card is minimized (collapsed) |
| `maximize` | Card is maximized (expanded)  |
| `close`    | Card is closed (hidden)       |

### Implementation

**Alpine.js** (`src/Components/Card/alpine.js`): Add `init()` with `$watch` on
`minimize` and `show` state properties.

**Blade template**: No changes required — `x-on:` attributes pass through
naturally via `$attributes`.

**PHP Component**: No changes required.

### Usage

```blade
<x-card header="Products" minimize="mount"
        x-on:maximize="$wire.loadProducts()"
        x-on:minimize="console.log('minimized')"
        x-on:close="console.log('closed')">
    ...
</x-card>
```

### Pattern Reference

Modal (`src/Components/Modal/alpine.js`):
```javascript
this.$watch('show', (value) => {
  this.$el.dispatchEvent(new CustomEvent(value ? 'open' : 'close'));
});
```

### Files to Change

1. `src/Components/Card/alpine.js` — add `init()` with watchers and event dispatch
2. `dist/` — rebuild JS assets (`npm run build`)
3. `src/Components/Card/BrowserTest.php` — add browser tests for events
4. `.ai/components/card.md` — update documentation
