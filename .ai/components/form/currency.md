# TallStackUI: Currency

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

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

## Locale & Formatting Details

### How `decimals` and `precision` Work

```blade
<x-currency decimals="2" precision="4" />
```

Internally uses JavaScript `Intl.NumberFormat`:
- `decimals` → `minimumFractionDigits`
- `precision` → `maximumFractionDigits`

### Locale Examples

The component supports any BCP 47 locale tag (the same set accepted by `Intl.NumberFormat`). Pair `locale` with `decimals`/`precision` to match how each currency is written in everyday use.

#### Zero-decimal currencies

Currencies whose fractional unit is not used in everyday transactions (Indonesian Rupiah, Japanese Yen, Korean Won, Vietnamese Đồng). Set both `decimals` and `precision` to `0`:

```blade
{{-- Indonesian Rupiah — bundled translation supplies symbol "Rp" + currency "IDR" --}}
<x-currency wire:model="amount" locale="id-ID" :decimals="0" :precision="0" symbol />
{{-- Renders: Rp 5.000.000 --}}

{{-- Vietnamese Đồng — bundled translation supplies symbol "₫" + currency "VND" --}}
<x-currency wire:model="amount" locale="vi-VN" :decimals="0" :precision="0" currency />
{{-- Renders: 5.000.000 VND --}}

{{-- Japanese Yen — pass a literal symbol since the library does not ship a "ja" translation --}}
<x-currency wire:model="amount" locale="ja-JP" :decimals="0" :precision="0" symbol="¥" />
{{-- Renders: ¥ 5,000,000 --}}

{{-- Korean Won --}}
<x-currency wire:model="amount" locale="ko-KR" :decimals="0" :precision="0" symbol="₩" />
{{-- Renders: ₩ 5,000,000 --}}
```

#### Two-decimal currencies (default)

```blade
{{-- Brazilian Real — bundled translation supplies symbol "R$" + currency "BRL" --}}
<x-currency wire:model="amount" locale="pt-BR" symbol />
{{-- Renders: R$ 1.234,56 --}}

{{-- US Dollar — bundled translation supplies symbol "$" + currency "USD" --}}
<x-currency wire:model="amount" locale="en-US" symbol />
{{-- Renders: $ 1,234.56 --}}

{{-- Euro — bundled "de" translation supplies symbol "€" + currency "EUR" --}}
<x-currency wire:model="amount" locale="de-DE" symbol />
{{-- Renders: € 1.234,56 --}}
```

> Bundled translation files cover `ar`, `de`, `en`, `es`, `fr`, `id`, `it`, `km`, `ms`, `nl`, `pl`, `pt`, `pt_BR`, `tr`, and `vi`. For locales outside this list, pass `symbol="..."` and/or `currency="..."` as literal strings or publish your own `ts-ui::messages.currency` overrides.

### Mutate Mode

When `mutate` is enabled, the raw numeric value (without formatting) is sent to the server instead of the formatted string:

```blade
<x-currency mutate />
```

### Custom Symbols

Override the default currency/symbol display:

```blade
<x-currency symbol="$$" />
<x-currency currency="$$" />
```

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

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
