# Theme Switch: Block Property

Adds a `block` property to the Theme Switch segmented variation for full-width layout.

## Usage

```blade
<x-theme-switch block />
```

Useful inside constrained containers like dropdown header slots where the component should expand to fill available width.

## What It Does

When `block` is set on the segmented variation:

1. **Full width** - Applies `w-full` to the segmented wrapper
2. **Equal button sizing** - Each button gets `flex flex-1 items-center justify-center` to distribute space evenly with centered icons
3. **No focus ring** - Applies `focus:outline-hidden` to prevent browser focus outlines on click

## Constraints

- `block` only works with the segmented variation (default). Using `block` with `simple` throws a validation exception.

## Files Changed

- `src/Components/ThemeSwitch/Component.php` - Added `block` property and validation
- `src/resources/views/components/theme-switch/main.blade.php` - Passes `$block` to variation
- `src/resources/views/components/theme-switch/variations/segmented.blade.php` - Conditional `w-full` and `flex flex-1 items-center justify-center` on buttons
