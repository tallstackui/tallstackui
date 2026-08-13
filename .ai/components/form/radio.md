# TallStackUI: Radio

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A radio button input component with configurable sizes, label positioning, and color theming through the TallStackUI color system.

## Basic Usage

```blade
<x-radio wire:model="plan" label="Basic Plan" value="basic" />
```

```blade
<x-radio wire:model="plan" label="Pro Plan" value="pro" color="green" />
```

```blade
<x-radio wire:model="option" label="Option A" value="a" lg position="left" />
```

The label also accepts a slot, which is what you need when it carries markup. The
`left` attribute on the slot places it before the radio, the same as `position="left"`:

```blade
<x-radio wire:model="plan" value="pro">
    <x-slot:label left>
        Pro <span class="text-xs text-gray-500">$29/mo</span>
    </x-slot:label>
</x-radio>
```

`start` on the same slot aligns the label to the top instead of centering it, which
matters when the text wraps over more than one line.

## Ids and the validation message

Options of a group share the bound property, so the value joins the generated id to keep
them apart: `plan-basic`, `plan-pro`, `plan-team`. Without that every label's `for` would
resolve to the first input and clicking any label would select the first option. An
explicit `id` is always used as given.

The validation message is printed once per property, by the first option that renders
it, rather than repeated under every option. `<x-radio.group>` centralises it on the
`<fieldset>` instead.

## Attributes

| Attribute  | Type                        | Default   | Description                                                                     |
|------------|-----------------------------|-----------|---------------------------------------------------------------------------------|
| label      | string\|ComponentSlot\|null | null      | Label text displayed next to the radio button                                   |
| xs         | string\|null                | null      | Sets radio size to extra small when present                                     |
| sm         | string\|null                | null      | Sets radio size to small when present                                           |
| md         | string\|null                | null      | Sets radio size to medium (default) when present                                |
| lg         | string\|null                | null      | Sets radio size to large when present                                           |
| position   | string\|null                | 'right'   | Label position relative to the radio button: 'left' or 'right'                  |
| color      | string\|null                | 'primary' | Color theme for the radio button (e.g., 'primary', 'secondary', 'red', 'green') |
| invalidate | bool\|null                  | null      | Prevents displaying validation error messages                                   |
| disabled   | bool                        | false     | Locks the radio. The value is not submitted.                                    |
| readonly   | bool                        | false     | Locks the radio. The value is still submitted.                                  |

## Colors

This component supports the TallStackUI color system via the `color` attribute. Available colors include all Tailwind CSS color names. The color affects the selected state fill of the radio button.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('radio')
    ->block('input.class', 'your-tailwind-classes');
```

### Available Blocks

| Block Name     | Purpose                                                      |
|----------------|--------------------------------------------------------------|
| input.class    | Core radio input styles (form-radio, border, rounding, ring) |
| input.sizes.xs | Extra small radio dimensions                                 |
| input.sizes.sm | Small radio dimensions                                       |
| input.sizes.md | Medium radio dimensions                                      |
| input.sizes.lg | Large radio dimensions                                       |
| error          | Error state border and text colors                           |
| locked         | Cursor applied while disabled or readonly                    |
