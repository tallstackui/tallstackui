# TallStackUI: Calendar

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

An **inline** date picker — the same grid-based calendar you get from `<x-date>`, but rendered directly in the page with no input field and no floating popover. Useful for dashboards, filter panels, scheduling screens, and inline reports. Supports single, range, and multi-date selection, plus an optional `double` mode that displays two months side-by-side for easier range selection.

## Basic Usage

```blade
<x-calendar wire:model="selectedDate" />
```

With a label and hint:

```blade
<x-calendar
    label="Pick a date"
    hint="Format: YYYY-MM-DD"
    wire:model="selectedDate"
/>
```

Range selection:

```blade
<x-calendar range wire:model="dateRange" />
```

Range with dual-month view (both months navigate together):

```blade
<x-calendar range double wire:model="dateRange" />
```

Multiple non-contiguous dates:

```blade
<x-calendar multiple wire:model="selectedDates" />
```

Helper buttons (yesterday / today / tomorrow):

```blade
<x-calendar helpers wire:model="selectedDate" />
```

Month-and-year only picker:

```blade
<x-calendar month-year-only wire:model="selectedMonth" />
```

Constraints:

```blade
<x-calendar
    min-date="2026-01-01"
    max-date="2026-12-31"
    :disable="['2026-04-15', '2026-04-20']"
    weekdays
    wire:model="selectedDate"
/>
```

Lock the month/year header (only allow picking days within the displayed month):

```blade
<x-calendar lock-month-year wire:model="selectedDate" />
```

## Attributes

| Attribute       | Type                        | Default        | Description                                                                                                                                                                                                                                               |
|-----------------|-----------------------------|----------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| label           | ComponentSlot\|string\|null | null           | Label rendered above the calendar grid.                                                                                                                                                                                                                   |
| hint            | ComponentSlot\|string\|null | null           | Hint rendered below the calendar grid.                                                                                                                                                                                                                    |
| range           | bool\|null                  | false          | Enables range selection: first click sets the start date, second click sets the end date.                                                                                                                                                                 |
| multiple        | bool\|null                  | false          | Enables multi-date selection. Cannot be combined with `range`.                                                                                                                                                                                            |
| double          | bool\|null                  | false          | Renders two months side-by-side (primary + primary+1). Only valid with `range`. Below the `sm` breakpoint, the two panels stack vertically inside the same card with no divider, keeping the visual continuity of the desktop layout.                     |
| format          | string\|null                | `'YYYY-MM-DD'` | Display format for selected dates (Day.js tokens).                                                                                                                                                                                                        |
| min-date        | string\|Carbon\|null        | null           | Earliest selectable date.                                                                                                                                                                                                                                 |
| max-date        | string\|Carbon\|null        | null           | Latest selectable date.                                                                                                                                                                                                                                   |
| min-year        | int\|null                   | null           | Earliest selectable year in the year picker.                                                                                                                                                                                                              |
| max-year        | int\|null                   | null           | Latest selectable year in the year picker.                                                                                                                                                                                                                |
| helpers         | bool\|null                  | null           | When true, renders `yesterday` / `today` / `tomorrow` quick buttons below the grid.                                                                                                                                                                       |
| month-year-only | bool\|null                  | false          | Locks the view to month + year selection (no day grid). Output format switches to `YYYY-MM`.                                                                                                                                                              |
| lock-month-year | bool\|null                  | false          | When true, the month and year header buttons no longer open the floating month/year pickers. Day selection and prev/next month navigation continue to work. Use it when the displayed month/year is fixed and you only want users picking days within it. |
| disable         | array\|Collection           | `[]`           | List of disabled date strings or Carbon instances.                                                                                                                                                                                                        |
| start           | int\|string                 | `0`            | First day of the week (0 = Sunday, 6 = Saturday).                                                                                                                                                                                                         |
| only            | int\|string\|null           | null           | Restricts selection to a single weekday (0–6).                                                                                                                                                                                                            |
| weekdays        | bool\|null                  | false          | When true, weekends are disabled.                                                                                                                                                                                                                         |
| weekends        | bool\|null                  | false          | When true, weekdays are disabled.                                                                                                                                                                                                                         |

## Slots

Calendar has no named slots — `label` and `hint` are passed as string props (or `ComponentSlot` instances).

## Events

Dispatched on the Calendar's root `<div>` and bubble up:

| Event  | Detail                                               | Fired when                                                                                               |
|--------|------------------------------------------------------|----------------------------------------------------------------------------------------------------------|
| select | `{ type: 'single'\|'range'\|'multiple', date: ... }` | A date is selected (single), a full range is set (range), or the list is toggled (multiple).             |
| clear  | `{ type, date: <previous model> }`                   | Internal — dispatched when the model is cleared. No public clear button — use `$wire.set('date', null)`. |

Listen with Alpine or bind to a Livewire method:

```blade
<x-calendar
    wire:model="selectedDate"
    x-on:select="console.log('selected', $event.detail.date)"
/>
```

## Validation

At render time, Calendar raises `InvalidArgumentException` (wrapped as `ViewException`) when:

- `min-date` or `max-date` cannot be parsed as a valid date.
- `min-date > max-date`.
- `max-year < min-year`.
- `start > 6` or `only > 6`.
- `double` is set but `range` is not — `double` requires `range`.
- `range` and `multiple` are both set — they are mutually exclusive.
- `lock-month-year` and `month-year-only` are both set — `month-year-only` makes the picker the only interaction surface, so locking it would freeze the component.

## Dual-month (`double`) Mode

The `double` prop turns on a two-panel layout designed for range selection. Both calendars share the same `month`/`year` state — navigating with the primary's prev/next arrows advances both. The secondary calendar always shows `primary_month + 1` (rolling into the next year when primary is December).

```blade
<x-calendar range double wire:model="dateRange" />
```

Selection flow:

1. Click a day in either calendar → sets the range start.
2. Click a later day in either calendar → sets the range end.
3. Both calendars highlight the selected range and intermediate days.

**Mobile layout:** below the `sm` breakpoint, the dual grid collapses from two columns to one — the primary stays on top, the secondary stacks directly below it inside the same `wrapper.body` card. There is no divider, border, or visible gap between them, mirroring the connected look of the desktop layout. Both panels remain fully functional and synchronized.

## Livewire Integration

Calendar binds to a Livewire property via `wire:model` (or `wire:model.live` for real-time sync):

```blade
{{-- Single date --}}
<x-calendar wire:model.live="selectedDate" />

{{-- Range: property must be an array --}}
<x-calendar range wire:model.live="dateRange" />

{{-- Multiple: property must be an array --}}
<x-calendar multiple wire:model.live="selectedDates" />
```

Outside Livewire, Calendar renders a hidden `<input name="...">` that participates in form submissions, carrying the serialized model value.

## Soft Customization

```php
TallStackUi::customize()
    ->calendar()
    ->block('wrapper.body', 'relative p-3 rounded-xl bg-gray-50 shadow-lg');
```

### Scoped

```php
TallStackUi::customize('calendar', scope: 'filter')
    ->block('wrapper.body', 'p-2 rounded-md bg-gray-100 shadow-none');
```

```blade
<x-calendar scope="filter" wire:model="filterDate" />
```

### Available Blocks

| Block Name                  | Purpose                                                                                                                                                                                                    |
|-----------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| wrapper.outer               | Outer container (flex column, gap between label / body / hint).                                                                                                                                            |
| wrapper.body                | Calendar body card — padding, background, rounded corners, shadow.                                                                                                                                         |
| wrapper.single              | Fixed width applied to each panel (`w-[17rem]`).                                                                                                                                                           |
| wrapper.dual                | Dual-panel grid used with `double`. Stacks vertically (`grid-cols-1 gap-y-2`) below the `sm` breakpoint and switches to two columns with horizontal gap (`sm:grid-cols-2 sm:gap-x-4 sm:gap-y-0`) above it. |
| wrapper.helpers             | Helpers row (yesterday/today/tomorrow buttons).                                                                                                                                                            |
| floating.default            | Base classes for the month/year picker popover (inherits `<x-floating>` defaults).                                                                                                                         |
| floating.class              | Additional class applied to the popover (`p-3 w-[17rem]`).                                                                                                                                                 |
| box.picker.button           | Month/year header row.                                                                                                                                                                                     |
| box.picker.wrapper.second   | Month/year picker flex wrap inside the floating popover.                                                                                                                                                   |
| box.picker.wrapper.third    | Month/year picker top bar inside the floating popover.                                                                                                                                                     |
| box.picker.label            | Month/year picker label button.                                                                                                                                                                            |
| box.picker.range            | Month/year grid cell.                                                                                                                                                                                      |
| box.picker.separator        | Dash between year range labels.                                                                                                                                                                            |
| box.picker.today            | "Today" shortcut button inside the picker.                                                                                                                                                                 |
| box.picker.navigate-wrapper | Wrapper around the year-picker prev/next chevrons (forces side-by-side layout).                                                                                                                            |
| label.days                  | Weekday header labels (Sun, Mon, Tue…).                                                                                                                                                                    |
| label.month                 | Current-month label in header.                                                                                                                                                                             |
| label.year                  | Current-year label in header.                                                                                                                                                                              |
| label.locked                | Extra classes applied to month/year header buttons when `lock-month-year` is set (`pointer-events-none opacity-60` by default).                                                                            |
| button.blank                | Blank day cell (before month start).                                                                                                                                                                       |
| button.day                  | Day button.                                                                                                                                                                                                |
| button.select               | Day cell default state.                                                                                                                                                                                    |
| button.today                | Day cell for today.                                                                                                                                                                                        |
| button.selected             | Day cell for selected dates.                                                                                                                                                                               |
| button.helpers              | Yesterday/today/tomorrow helper button (rendered with `helpers` prop).                                                                                                                                     |
| button.navigate             | Prev/next month/year arrow button.                                                                                                                                                                         |
| icon.navigate               | Navigation arrow icon.                                                                                                                                                                                     |
| range                       | Background tint for days between the range start and end.                                                                                                                                                  |

## Notes

- **Inherits Date's visual language** — same color palette, same day/month/year pickers, same helper buttons. Calendar re-uses the `ts-ui::messages.date.*` translations.
- **No `invalidate` prop** — validation failure UX lives with `<x-date>` (inside a form input). Calendar is inline and does not participate in form-error flows directly.
- **No suffix icons** (clear, open/close) — there is no input to attach them to. Use `$wire->set('selectedDate', null)` to clear programmatically.
- **Runtime** — `CalendarRuntime` mirrors `DateRuntime` 1:1 (project convention is copy over inheritance between runtimes). Keeping them independent avoids accidental coupling if Date's runtime changes.
- **JavaScript bundle** — Calendar's Alpine factory is bundled inside `tallstackui-date.js` (shared with `<x-date>` and `<x-time>`) because they all depend on `dayjs`.
