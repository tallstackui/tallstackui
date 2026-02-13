# Select Styled: Grouped Options Fix

Fixes for grouped options in the Select Styled component (Issue #1037).

## Problem

When using grouped options with pre-selected values via `wire:model`, the component rendered empty instead of showing the selected items. Additionally, search filtering and keyboard navigation did not work correctly within grouped options.

## What Was Fixed

### 1. Default Value Hydration (Issue #1037)

The `hydrate()` method searched for selected values in the top-level group objects instead of looking inside their nested items. A new `_flatItems()` helper flattens grouped options into individual items for value lookup.

**Before:** `hydrate()` tried to match `model=4` against group objects whose `value` is an array of items — never found a match.

**After:** `hydrate()` uses `_flatItems()` to search within the nested items, correctly finding `{label: 'São Paulo', value: 4}`.

### 2. Search Filtering Within Groups

The search filter only checked group header labels ("Brazil", "United States"), not the labels of items within groups. Now it filters items inside each group and hides groups with no matching items.

### 3. Keyboard Navigation

Arrow key navigation used the group count (2) as the maximum index instead of the total item count (e.g., 6). Now uses `_flatItems()` for the correct count, allowing keyboard navigation to reach all items across all groups.

### 4. Pre-normalization of Nested Items

`preNormalize()` now recursively normalizes labels of nested group items, enabling fast accent-insensitive search within groups.

### 5. Selection Check Consistency

The grouped items template now uses `selected(item)` (value-based comparison) instead of `selects.includes(item)` (reference equality) for more robust selection state tracking.

## Files Changed

- `src/Components/Form/Select/Styled/alpine.js` — Added `_flatItems()`, fixed `hydrate()`, `preNormalize()`, `navigate()`, and search filter in `available` getter
- `src/resources/views/components/form/select/styled.blade.php` — Changed selection check to use `selected(item)` in grouped template
- `src/Components/Form/Select/Styled/FeatureTest.php` — Added grouped options rendering test
- `src/Components/Form/Select/Styled/SelectStyledCommonBrowserTest.php` — Added 5 browser tests for grouped options

## Test Coverage

| Test | What It Validates |
|------|-------------------|
| `can_hydrate_grouped_options_with_default_values` | Multiple selection with pre-set values displays correctly |
| `can_hydrate_grouped_single_option_with_default_value` | Single selection with pre-set value displays correctly |
| `can_select_grouped_options` | Clicking a grouped item updates the model |
| `can_search_within_grouped_options` | Search filters items within groups correctly |
| `can_navigate_grouped_options_with_keyboard` | Arrow keys navigate through all items across groups |
| `can render with grouped options` | Feature test for grouped template rendering |
