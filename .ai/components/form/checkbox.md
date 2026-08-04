# TallStackUI: Checkbox

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A checkbox input component with configurable sizes, label positioning, and color theming through the TallStackUI color system.

## Basic Usage

```blade
<x-checkbox wire:model="agree" label="I agree to the terms" />
```

```blade
<x-checkbox wire:model="subscribe" label="Subscribe to newsletter" color="green" />
```

```blade
<x-checkbox wire:model="active" label="Active" lg position="left" />
```

The label also accepts a slot, which is what you need when it carries markup. The
`left` attribute on the slot places it before the checkbox, the same as `position="left"`:

```blade
<x-checkbox wire:model="agree">
    <x-slot:label left>
        I agree to the <a href="/terms">terms</a>
    </x-slot:label>
</x-checkbox>
```

`start` on the same slot aligns the label to the top instead of centering it, which
matters when the text wraps over more than one line.

## Attributes

| Attribute  | Type                        | Default   | Description                                                                 |
|------------|-----------------------------|-----------|-----------------------------------------------------------------------------|
| label      | string\|ComponentSlot\|null | null      | Label text displayed next to the checkbox                                   |
| xs         | string\|null                | null      | Sets checkbox size to extra small when present                              |
| sm         | string\|null                | null      | Sets checkbox size to small when present                                    |
| md         | string\|null                | null      | Sets checkbox size to medium (default) when present                         |
| lg         | string\|null                | null      | Sets checkbox size to large when present                                    |
| position   | string\|null                | 'right'   | Label position relative to the checkbox: 'left' or 'right'                  |
| color      | string\|null                | 'primary' | Color theme for the checkbox (e.g., 'primary', 'secondary', 'red', 'green') |
| invalidate | bool\|null                  | null      | Prevents displaying validation error messages                               |

## Colors

This component supports the TallStackUI color system via the `color` attribute. Available colors include all Tailwind CSS color names. The color affects the checked state background of the checkbox.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('checkbox')
    ->block('input.class', 'your-tailwind-classes');
```

### Available Blocks

| Block Name     | Purpose                                                            |
|----------------|--------------------------------------------------------------------|
| input.class    | Core checkbox input styles (form-checkbox, border, rounding, ring) |
| input.sizes.xs | Extra small checkbox dimensions                                    |
| input.sizes.sm | Small checkbox dimensions                                          |
| input.sizes.md | Medium checkbox dimensions                                         |
| input.sizes.lg | Large checkbox dimensions                                          |
| error          | Error state border and text colors                                 |
