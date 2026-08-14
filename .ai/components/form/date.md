# TallStackUI: Date

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A date picker component with a floating calendar panel, month/year pickers, range selection, multiple date selection, configurable date format, min/max date and year constraints, disabled dates, day-of-week filtering, helper buttons (yesterday/today/tomorrow), and month-year-only mode.

## Basic Usage

```blade
<x-date wire:model="date" label="Date" />
```

```blade
<x-date wire:model="dates" label="Date Range" range />
```

```blade
<x-date wire:model="dates" label="Multiple Dates" multiple />
```

```blade
<x-date wire:model="date"
        label="Appointment"
        format="DD/MM/YYYY"
        min-date="2024-01-01"
        max-date="2025-12-31"
        helpers />
```

```blade
<x-date wire:model="date"
        label="Weekdays Only"
        weekdays
        :disable="['2025-12-25', '2025-01-01']" />
```

## Attributes

| Attribute       | Type                        | Default      | Description                                                                       |
|-----------------|-----------------------------|--------------|-----------------------------------------------------------------------------------|
| label           | string\|ComponentSlot\|null | null         | Label text displayed above the input                                              |
| hint            | string\|ComponentSlot\|null | null         | Hint text displayed below the input                                               |
| invalidate      | bool\|null                  | null         | Prevents displaying validation error messages                                     |
| range           | bool\|null                  | false        | Enables date range selection (start and end dates)                                |
| multiple        | bool\|null                  | false        | Enables multiple individual date selection                                        |
| format          | string\|null                | 'YYYY-MM-DD' | Display format for the selected date (see Format Tokens)                          |
| min-date        | string\|Carbon\|null        | null         | Minimum selectable date                                                           |
| max-date        | string\|Carbon\|null        | null         | Maximum selectable date                                                           |
| min-year        | int\|null                   | null         | Minimum selectable year in the year picker                                        |
| max-year        | int\|null                   | null         | Maximum selectable year in the year picker                                        |
| helpers         | bool\|null                  | null         | Shows yesterday, today, and tomorrow quick-select buttons                         |
| month-year-only | bool\|null                  | false        | Restricts the picker to month and year selection only                             |
| disable         | array\|Collection           | []           | Array of date strings or Carbon instances to disable                              |
| start           | int\|string                 | 0            | First day of the week (0 = Sunday, 1 = Monday, ..., 6 = Saturday)                 |
| only            | int\|string\|null           | null         | Restricts selection to a specific day of the week (0-6)                           |
| weekdays        | bool\|null                  | false        | Restricts selection to weekdays only (Monday-Friday)                              |
| weekends        | bool\|null                  | false        | Restricts selection to weekends only (Saturday-Sunday)                            |
| disabled        | bool                        | false        | Locks the input, the calendar and the clear button. The value is not submitted.   |
| readonly        | bool                        | false        | Locks the input, the calendar and the clear button. The value is still submitted. |

## Alpine.js Events

| Event       | Description                                |
|-------------|--------------------------------------------|
| x-on:select | Triggered when a date is selected          |
| x-on:clear  | Triggered when the clear button is clicked |

## Validation Constraints

- The `min-date` must be a valid date string or Carbon instance.
- The `max-date` must be a valid date string or Carbon instance.
- The `min-date` must be less than or equal to `max-date` when both are set.
- The `min-year` must be less than or equal to `max-year` when both are set.
- The `start` attribute must not be greater than 6.
- The `only` attribute must not be greater than 6.

## Event Payload Details

```blade
<x-date x-on:select="alert(`Selected Date: ${$event.detail.date}`)"
        x-on:clear="alert('Cleaned!')" />
```

### Backend Format Note

The component uses `Y-m-d` format internally. If your date is in a different format, convert it server-side:

```php
$date = '20/02/2024';
$date = now()->createFromFormat('d/m/Y', $date)->format('Y-m-d');
// Result: '2024-02-20'
```

## Format Tokens

The `format` attribute is visual only and accepts the tokens below. They are the Day.js core tokens, but the formatter is the package's own (`js/helpers/date.js`), so tokens that Day.js only exposes through plugins — `Do`, `Q`, `X`, `x`, `k`, `w`, `gggg` — are not available and never were.

| Token    | Output                                                   |
|----------|----------------------------------------------------------|
| `YY`     | `26`                                                     |
| `YYYY`   | `2026`                                                   |
| `M`      | `1`-`12`                                                 |
| `MM`     | `01`-`12`                                                |
| `MMM`    | `Jan` (first three characters of the translated month)   |
| `MMMM`   | `January` (translated)                                   |
| `D`      | `1`-`31`                                                 |
| `DD`     | `01`-`31`                                                |
| `d`      | `0`-`6`                                                  |
| `dd`     | `Su` (first two characters of the translated weekday)    |
| `ddd`    | `Sun` (first three characters of the translated weekday) |
| `dddd`   | `Sunday` (translated)                                    |
| `H`      | `0`-`23`                                                 |
| `HH`     | `00`-`23`                                                |
| `h`      | `1`-`12`                                                 |
| `hh`     | `01`-`12`                                                |
| `m`      | `0`-`59`                                                 |
| `mm`     | `00`-`59`                                                |
| `s`      | `0`-`59`                                                 |
| `ss`     | `00`-`59`                                                |
| `SSS`    | `000`-`999`                                              |
| `a`      | `am` / `pm`                                              |
| `A`      | `AM` / `PM`                                              |
| `Z`      | `+03:00`                                                 |
| `ZZ`     | `+0300`                                                  |
| `[text]` | `text`, escaped from parsing                             |

```blade
<x-date format="DD/MM/YYYY" />
<x-date format="MMMM, DD, YYYY" />
<x-date format="DD [of] MMMM [of] YYYY" />
```

Month and weekday names come from the `calendar` translations in `lang/{locale}/messages.php`, so `MMMM` and `dddd` follow the application locale. The picker only ever holds a date, so the time tokens always render zeros.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('date')
    ->block('button.day', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                | Purpose                                                                                             |
|---------------------------|-----------------------------------------------------------------------------------------------------|
| wrapper.helpers           | Helper buttons scrollable container                                                                 |
| floating.default          | Base floating panel positioning                                                                     |
| floating.class            | Floating panel width and padding                                                                    |
| box.picker.expanded       | Minimum panel height while a picker is open (`min-h-[17rem]`), keeping room under `month-year-only` |
| box.picker.button         | Month/year picker button styles                                                                     |
| box.picker.wrapper.first  | Picker overlay background and positioning                                                           |
| box.picker.wrapper.second | Picker content flex wrapper                                                                         |
| box.picker.wrapper.third  | Picker header row layout                                                                            |
| box.picker.label          | Picker label button styles                                                                          |
| box.picker.range          | Month/year range item styles                                                                        |
| box.picker.separator      | Year range separator dash                                                                           |
| label.days                | Day-of-week header text styles                                                                      |
| label.month               | Month label font styles                                                                             |
| label.year                | Year label font styles                                                                              |
| button.blank              | Empty calendar cell padding                                                                         |
| button.day                | Day button size, rounding, and transitions                                                          |
| button.select             | Unselected day hover styles                                                                         |
| button.today              | Today highlight text styles                                                                         |
| button.selected           | Selected day background and text styles                                                             |
| button.helpers            | Helper button (yesterday/today/tomorrow) styles                                                     |
| button.navigate           | Navigation arrow button styles                                                                      |
| icon.wrapper              | Suffix icon flex container                                                                          |
| icon.size                 | Calendar and clear icon dimensions                                                                  |
| icon.clear                | Clear icon hover color                                                                              |
| icon.navigate             | Navigation arrow icon color and size                                                                |
| range                     | Range selection between-date background                                                             |
