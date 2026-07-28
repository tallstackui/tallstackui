# TallStackUI: Radio Group

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A group of radio options rendered from an array, in one of four presentations:
stacked rows, cards, panels or an inline segmented control. The selected state is
driven entirely by CSS, so no Alpine component is involved.

## Basic Usage

```blade
<x-radio.group wire:model="plan" label="Plan" :options="[
    ['label' => 'Startup', 'value' => 'startup', 'description' => 'Up to 5 job postings', 'aside' => '$29 / mo'],
    ['label' => 'Business', 'value' => 'business', 'description' => 'Up to 25 job postings', 'aside' => '$99 / mo'],
    ['label' => 'Enterprise', 'value' => 'enterprise', 'description' => 'Unlimited', 'aside' => '$249 / mo'],
]" />
```

```blade
<x-radio.group wire:model="plan" card :columns="3" color="green" :options="$plans" />
```

```blade
<x-radio.group wire:model="period" inline :options="[
    ['label' => 'Monthly', 'value' => 'monthly'],
    ['label' => 'Yearly', 'value' => 'yearly'],
]" />
```

Out of the Livewire context, use `name` and `value`:

```blade
<x-radio.group name="plan" value="business" :options="$plans" />
```

Every input shares the same `name`. When it is not given, it falls back to the
`id`, then to the bound property, so the options stay mutually exclusive and
`required` is validated as a single group.

## Attributes

| Attribute  | Type              | Default   | Description                                                      |
|------------|-------------------|-----------|------------------------------------------------------------------|
| id         | string\|null      | null      | Base id for the generated inputs, suffixed with the option index |
| label      | string\|null      | null      | Group label, rendered as the fieldset legend                     |
| hint       | string\|null      | null      | Hint text displayed below the group                              |
| list       | string\|null      | null      | Presentation flag: stacked rows; the default when none is given  |
| card       | string\|null      | null      | Presentation flag: independent cards in a grid                   |
| panel      | string\|null      | null      | Presentation flag: cards with a check icon, control hidden       |
| inline     | string\|null      | null      | Presentation flag: horizontal segmented control                  |
| color      | string\|null      | 'primary' | Color theme of the selected option                               |
| select     | string\|null      | null      | Remaps the option keys, e.g. `label:name\|value:id`              |
| columns    | int\|null         | 3         | Grid columns for 'card' and 'panel', between 1 and 4             |
| position   | string\|null      | 'left'    | Control side on 'list' and 'card': 'left' or 'right'             |
| required   | bool\|null        | false     | Adds the asterisk to the legend and `required` to the inputs     |
| invalidate | bool\|null        | null      | Prevents displaying validation error messages                    |
| xs         | string\|null      | null      | Sets the control size to extra small when present                |
| sm         | string\|null      | null      | Sets the control size to small when present                      |
| md         | string\|null      | null      | Sets the control size to medium (default) when present           |
| lg         | string\|null      | null      | Sets the control size to large when present                      |
| options    | Collection\|array | []        | The selectable options                                           |

## Variants

Each presentation is a flag, not a value. Passing none renders `list`, and when
more than one is given the first of `card`, `panel`, `inline` wins.

```blade
<x-radio.group panel :options="$options" />
```

| Variant  | Layout                                                         | Control   |
|----------|----------------------------------------------------------------|-----------|
| `list`   | Stacked rows sharing borders, rounded at the ends of the stack | Visible   |
| `card`   | Independent cards in a responsive grid                         | Visible   |
| `panel`  | Cards with a check icon marking the selection                  | `sr-only` |
| `inline` | Horizontal segmented control with a solid fill                 | `sr-only` |

The `sr-only` control on `panel` and `inline` stays in the tab order and toggles
with Space.

## Option Keys

| Key           | Type   | Required | Ignored by |
|---------------|--------|----------|------------|
| `label`       | string | yes      | —          |
| `value`       | scalar | yes      | —          |
| `description` | string | no       | `inline`   |
| `aside`       | string | no       | `inline`   |
| `icon`        | string | no       | —          |
| `image`       | string | no       | `inline`   |
| `badge`       | string | no       | `inline`   |
| `disabled`    | bool   | no       | —          |

`image` takes precedence over `icon` when both are present.

The `select` attribute remaps the source keys with the same syntax used by
`<x-select.styled>`, and any part left out falls back to the key of the same name:

```blade
<x-radio.group wire:model="plan" select="label:name|value:id|description:note" :options="$plans" />
```

## Customizing an Option

`@interact('option', $option)` replaces the body of every item. The `<label>`, the
`<input>` and the selected-state classes stay owned by the component, and the
closure receives the option with its original keys still available:

```blade
<x-radio.group wire:model="plan" card :options="$plans">
    @interact('option', $option)
        <div class="flex items-center justify-between">
            <span class="font-medium">{{ $option['label'] }}</span>
            <x-badge :text="$option['tag']" color="green" sm />
        </div>
    @endinteract
</x-radio.group>
```

## Colors

This component supports the TallStackUI color system via the `color` attribute.
The color drives the border, background and text of the selected option, the fill
of the selected segment on the `inline` variant, and the control itself.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('radio.group')
    ->block('item.card', 'your-tailwind-classes');
```

### Available Blocks

| Block Name            | Purpose                                                 |
|-----------------------|---------------------------------------------------------|
| wrapper.base          | The fieldset that wraps the whole group                 |
| wrapper.legend        | The legend that carries the group label                 |
| wrapper.legend-error  | Legend colors when the bound property has an error      |
| wrapper.asterisk      | The required asterisk next to the legend                |
| container.list        | The container of the list variant                       |
| container.card        | The container of the card variant                       |
| container.panel       | The container of the panel variant                      |
| container.inline      | The container of the inline variant                     |
| columns.1             | Grid with one column                                    |
| columns.2             | Grid with two columns                                   |
| columns.3             | Grid with three columns                                 |
| columns.4             | Grid with four columns                                  |
| item.base             | Classes shared by every option, in every variant        |
| item.list             | Option classes of the list variant                      |
| item.card             | Option classes of the card variant                      |
| item.panel            | Option classes of the panel variant                     |
| item.inline           | Option classes of the inline variant                    |
| item.disabled         | Option classes when the option is disabled              |
| item.error            | Option classes when the bound property has an error     |
| control.base          | Core input styles shared with the standalone radio      |
| control.shape         | The classes that make the control round                 |
| control.hidden        | Applied to the control on the panel and inline variants |
| control.sizes.xs      | Extra small control dimensions                          |
| control.sizes.sm      | Small control dimensions                                |
| control.sizes.md      | Medium control dimensions                               |
| control.sizes.lg      | Large control dimensions                                |
| control.spacing.left  | Spacing when the control sits on the left               |
| control.spacing.right | Spacing when the control sits on the right              |
| check                 | The check icon of the panel variant                     |
| content.wrapper       | The wrapper of the label and description                |
| content.header        | The row holding the label and the badge                 |
| content.label         | The option label                                        |
| content.inline        | Label and icon colors on the inline variant             |
| content.description   | The option description                                  |
| content.aside         | The option aside text                                   |
| content.icon          | The option icon                                         |
| content.image         | The option image                                        |
