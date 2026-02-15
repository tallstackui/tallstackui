# TallStackUI: Currency

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 40+ Blade components for building modern web interfaces.

A currency input component that formats numeric values with locale-aware decimal separators, configurable precision, optional currency/symbol display, and a clearable button. Built on top of the Input component with Alpine.js-powered real-time formatting.

## Basic Usage

```blade
<x-currency wire:model="price" label="Price" />
```

```blade
<x-currency wire:model="amount" label="Amount" locale="pt-BR" symbol currency />
```

```blade
<x-currency wire:model="total"
            label="Total"
            :decimals="2"
            :precision="4"
            clearable />
```

## Attributes

| Attribute  | Type                        | Default | Description                                                                           |
|------------|-----------------------------|---------|---------------------------------------------------------------------------------------|
| label      | string\|ComponentSlot\|null | null    | Label text displayed above the input                                                  |
| hint       | string\|ComponentSlot\|null | null    | Hint text displayed below the input                                                   |
| clearable  | bool\|null                  | null    | Shows a clear button when the input has a value                                       |
| invalidate | bool\|null                  | null    | Prevents displaying validation error messages for this input                          |
| locale     | string\|null                | 'en-US' | Locale for number formatting (e.g., 'en-US', 'pt-BR', 'de-DE')                        |
| decimals   | int\|null                   | 2       | Number of decimal places displayed                                                    |
| precision  | int\|null                   | 4       | Maximum digit precision for internal value storage                                    |
| symbol     | bool\|string\|null          | null    | Shows the locale currency symbol prefix (true for locale default, or a custom string) |
| currency   | bool\|string\|null          | null    | Shows the locale currency code suffix (true for locale default, or a custom string)   |
| mutate     | bool\|null                  | null    | When true, sends the raw numeric value (without formatting) to the Livewire property  |

## Validation Constraints

- The `precision` must be greater than or equal to `decimals`.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Personalization

```php
TallStackUi::customize()
    ->form('currency')
    ->block('clearable.size', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                         | Purpose                                       |
|------------------------------------|-----------------------------------------------|
| clearable.wrapper                  | Clearable button container positioning        |
| clearable.padding.with-currency    | Right padding when currency suffix is visible |
| clearable.padding.without-currency | Right padding when no currency suffix         |
| clearable.size                     | Clearable icon dimensions                     |
| clearable.color                    | Clearable icon hover color                    |
