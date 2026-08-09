# TallStackUI: Checkbox Group

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A group of checkbox options rendered from an array, in one of four presentations:
stacked rows, cards, panels or an inline segmented control. The selected state is
driven entirely by CSS, so no Alpine component is involved.

## Basic Usage

```blade
<x-checkbox.group wire:model="features" label="Features" :options="[
    ['label' => 'Newsletter', 'value' => 'newsletter', 'description' => 'Weekly digest'],
    ['label' => 'Alerts', 'value' => 'alerts', 'description' => 'Real time notifications'],
    ['label' => 'Reports', 'value' => 'reports', 'description' => 'Monthly summary'],
]" />
```

```blade
<x-checkbox.group wire:model="features" card :columns="2" color="green" :options="$features" />
```

The bound property must be an array:

```php
public array $features = [];
```

Out of the Livewire context, use an array `name` and an array `value`:

```blade
<x-checkbox.group name="features[]" :value="['newsletter', 'reports']" :options="$features" />
```

Every input shares the same `name`. When it is not given, it falls back to the
`id`, then to the bound property, always suffixed with `[]` so a plain form
submission collects the options as an array.

## Attributes

| Attribute  | Type              | Default     | Description                                                                  |
|------------|-------------------|-------------|------------------------------------------------------------------------------|
| id         | string\|null      | null        | Base id for the generated inputs, suffixed with the option index             |
| label      | string\|null      | null        | Group label, rendered as the fieldset legend                                 |
| hint       | string\|null      | null        | Hint text displayed below the group                                          |
| list       | string\|null      | null        | Presentation flag: stacked rows; the default when none is given              |
| card       | string\|null      | null        | Presentation flag: independent cards in a grid                               |
| panel      | string\|null      | null        | Presentation flag: cards with a check icon, control hidden                   |
| inline     | string\|null      | null        | Presentation flag: horizontal segmented control                              |
| color      | string\|null      | 'primary'   | Color theme of the selected option                                           |
| select     | string\|null      | from config | Remaps the option keys, e.g. `label:name\|value:id`. Inline overrides config |
| columns    | int\|null         | 3           | Grid columns for 'card' and 'panel', between 1 and 4                         |
| position   | string\|null      | 'left'      | Control side on 'list' and 'card': 'left' or 'right'                         |
| required   | bool\|null        | false       | Adds the asterisk to the legend                                              |
| invalidate | bool\|null        | null        | Prevents displaying validation error messages                                |
| xs         | string\|null      | null        | Sets the control size to extra small when present                            |
| sm         | string\|null      | null        | Sets the control size to small when present                                  |
| md         | string\|null      | null        | Sets the control size to medium (default) when present                       |
| lg         | string\|null      | null        | Sets the control size to large when present                                  |
| options    | Collection\|array | []          | The selectable options                                                       |

`required` only marks the legend here. The native `required` attribute is never
set on the inputs, since the browser would then demand every box be checked.

## Variants

Each presentation is a flag, not a value. Passing none renders `list`, and when
more than one is given the first of `card`, `panel`, `inline` wins.

```blade
<x-checkbox.group panel :options="$options" />
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
<x-checkbox.group wire:model="features" select="label:name|value:id|description:note" :options="$features" />
```

## Configuration

Configuration via `config/tallstackui.php` under `components.checkbox.group`:

| Key    | Default | Description                                                     |
|--------|---------|-----------------------------------------------------------------|
| select | null    | Default option key mapping, same syntax as the inline attribute |

```php
'checkbox.group' => [
    TallStackUi\Components\Form\Checkbox\Group\Component::class,
    [
        'select' => 'label:name|value:id',
    ],
],
```

The inline attribute always wins, and with neither every key falls back to the
key of the same name.

## Customizing an Option

`@interact('option', $option)` replaces the body of every item. The `<label>`, the
`<input>` and the selected-state classes stay owned by the component, and the
closure receives the option with its original keys still available:

```blade
<x-checkbox.group wire:model="features" card :options="$features">
    @interact('option', $option)
        <div class="flex items-center justify-between">
            <span class="font-medium">{{ $option['label'] }}</span>
            <x-badge :text="$option['tag']" color="green" sm />
        </div>
    @endinteract
</x-checkbox.group>
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
    ->form('checkbox.group')
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
| control.base          | Core input styles shared with the standalone checkbox   |
| control.shape         | The classes that make the control square                |
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
