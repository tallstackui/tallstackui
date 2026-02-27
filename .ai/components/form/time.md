# TallStackUI: Time

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A time picker component with a floating panel featuring range sliders for hours and minutes, 12-hour (AM/PM) or 24-hour format support, configurable min/max and step values for hours and minutes, a "current time" helper button, and an optional footer slot.

## Basic Usage

```blade
<x-time wire:model="time" label="Time" />
```

```blade
<x-time wire:model="time" label="Time" format="24" />
```

```blade
<x-time wire:model="time"
        label="Meeting Time"
        format="24"
        :min-hour="9"
        :max-hour="17"
        helper />
```

```blade
<x-time wire:model="time"
        label="Interval"
        step-hour="2"
        step-minute="15" />
```

## Attributes

| Attribute   | Type                        | Default | Description                                                           |
|-------------|-----------------------------|---------|-----------------------------------------------------------------------|
| label       | string\|ComponentSlot\|null | null    | Label text displayed above the input                                  |
| hint        | string\|ComponentSlot\|null | null    | Hint text displayed below the input                                   |
| invalidate  | bool\|null                  | null    | Prevents displaying validation error messages                         |
| helper      | bool\|null                  | null    | Shows a "Current Time" button that sets the value to the current time |
| min-hour    | int\|null                   | null    | Minimum selectable hour (0-23)                                        |
| max-hour    | int\|null                   | null    | Maximum selectable hour (0-23)                                        |
| min-minute  | int\|null                   | null    | Minimum selectable minute (0-59)                                      |
| max-minute  | int\|null                   | null    | Maximum selectable minute (0-59)                                      |
| format      | string\|null                | '12'    | Time format: '12' for 12-hour with AM/PM, '24' for 24-hour            |
| step-hour   | string\|null                | '1'     | Step interval for the hour slider                                     |
| step-minute | string\|null                | '1'     | Step interval for the minute slider                                   |

## Slots

| Slot   | Description                                                             |
|--------|-------------------------------------------------------------------------|
| footer | Custom content rendered at the bottom of the time picker floating panel |

## Alpine.js Events

| Event         | Description                                                     |
|---------------|-----------------------------------------------------------------|
| x-on:hour     | Triggered when the hour value changes                           |
| x-on:minute   | Triggered when the minute value changes                         |
| x-on:interval | Triggered when the AM/PM interval changes (12-hour format only) |
| x-on:current  | Triggered when the "Current Time" helper button is clicked      |

## Validation Constraints

- The `min-hour` must be between 0 and 23.
- The `max-hour` must be between 0 and 23.
- The `min-hour` must be less than or equal to `max-hour` when both are set.
- The `min-minute` must be between 0 and 59.
- The `max-minute` must be between 0 and 59.
- The `min-minute` must be less than or equal to `max-minute` when both are set.

## Event Payload Details

```blade
<x-time x-on:hour="alert(`Hour Selected: ${$event.detail.hour}`)"
        x-on:minute="alert(`Minute Selected: ${$event.detail.minute}`)"
        x-on:interval="alert(`Interval Changed: ${$event.detail.interval}`)" />
```

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('time')
    ->block('time', 'your-tailwind-classes');
```

### Available Blocks

| Block Name               | Purpose                                   |
|--------------------------|-------------------------------------------|
| wrapper                  | Time display flex container               |
| icon.size                | Clock and clear icon dimensions           |
| icon.clear               | Clear icon hover color                    |
| icon.wrapper             | Suffix icon flex container                |
| floating.default         | Base floating panel positioning           |
| floating.class           | Floating panel width and padding          |
| time                     | Hour/minute display text styles           |
| separator                | Colon separator between hours and minutes |
| interval.wrapper         | AM/PM indicator container                 |
| interval.text            | AM/PM text styles                         |
| interval.buttons.wrapper | AM/PM button group container              |
| interval.buttons.am      | AM button styles                          |
| interval.buttons.pm      | PM button styles                          |
| range.base               | Range slider track styles                 |
| range.thumb              | Range slider thumb styles                 |
| range.light              | Light background highlight on hover       |
| range.dark               | Dark mode background highlight on hover   |
| helper.wrapper           | Helper section flex container             |
| helper.button            | "Current Time" button width style         |
