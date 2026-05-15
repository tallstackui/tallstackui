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

| Attribute  | Type                        | Default | Description                                                                                                         |
|------------|-----------------------------|---------|---------------------------------------------------------------------------------------------------------------------|
| label      | string\|ComponentSlot\|null | null    | Label text displayed above the input                                                                                |
| hint       | string\|ComponentSlot\|null | null    | Hint text displayed below the input                                                                                 |
| clearable  | bool\|null                  | null    | Shows a clear button when the input has a value                                                                     |
| invalidate | bool\|null                  | null    | Prevents displaying validation error messages for this input                                                        |
| locale     | string\|null                | 'en-US' | Locale for number formatting (e.g., 'en-US', 'pt-BR', 'de-DE')                                                      |
| decimals   | int\|null                   | 2       | Number of decimal places displayed                                                                                  |
| precision  | int\|null                   | 4       | Maximum digit precision for internal value storage                                                                  |
| symbol     | bool\|string\|null          | null    | Shows the locale currency symbol prefix (true for locale default, or a custom string)                               |
| currency   | bool\|string\|null          | null    | Shows the locale currency code suffix (true for locale default, or a custom string)                                 |
| mutate     | bool\|null                  | null    | When true, sends the formatted string exactly as displayed (e.g. `"2,000.00"`) to the Livewire property             |
| decimal    | bool\|null                  | null    | When true, sends the parsed decimal string (e.g. `"2000.00"`) — group separator stripped, decimal normalized to `.` |

## Validation Constraints

- The `precision` must be greater than or equal to `decimals`.
- The `mutate` and `decimal` props cannot be used together.

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

### Sync Modes

The Currency component offers three modes of sending the value to the Livewire
property — pick the one that matches how you persist the value on the server.

#### Default — digits-only ("cents")

Without `mutate` or `decimal`, the component sends a digits-only string. With
`decimals=2`, typing `1000` displays `10.00` but the property receives `"1000"`.
Useful when monetary values are stored as integer cents in the database.

| Typed digits | Display (en-US) | Display (pt-BR) | Sent to Livewire |
|--------------|-----------------|-----------------|------------------|
| `1000`       | `10.00`         | `10,00`         | `"1000"`         |
| `200000`     | `2,000.00`      | `2.000,00`      | `"200000"`       |
| `150055`     | `1,500.55`      | `1.500,55`      | `"150055"`       |

#### Mutate — formatted display string

```blade
<x-currency mutate wire:model="price" />
```

With `mutate`, the component sends the formatted string **exactly as it appears
in the input** — group separator and decimal separator included. Use when you
want to persist the user-facing representation verbatim (e.g. a free-text
display label).

| Typed digits | Display (en-US) | Display (pt-BR) | Sent to Livewire            |
|--------------|-----------------|-----------------|-----------------------------|
| `1000`       | `10.00`         | `10,00`         | `"10.00"` / `"10,00"`       |
| `200000`     | `2,000.00`      | `2.000,00`      | `"2,000.00"` / `"2.000,00"` |
| `150055`     | `1,500.55`      | `1.500,55`      | `"1,500.55"` / `"1.500,55"` |

#### Decimal — parsed decimal string

```blade
<x-currency decimal wire:model="price" />
```

With `decimal`, the component strips the locale's group separator and
normalizes the decimal separator to `.`, so the resulting string is directly
castable via `(float)` / `(int)` or by Eloquent `decimal:2` / `float` casts —
regardless of locale.

| Typed digits | Display (en-US) | Display (pt-BR) | Sent to Livewire |
|--------------|-----------------|-----------------|------------------|
| `1000`       | `10.00`         | `10,00`         | `"10.00"`        |
| `200000`     | `2,000.00`      | `2.000,00`      | `"2000.00"`      |
| `150055`     | `1,500.55`      | `1.500,55`      | `"1500.55"`      |

> `mutate` and `decimal` are mutually exclusive. Setting both raises a
> validation exception at render time.

#### Global defaults

If most components in your application need the same mode, set it once in
`config/ts-ui.php` to avoid repeating the prop on every usage:

```php
'currency' => [
    Components\Form\Currency\Component::class,
    [
        'mutate' => false,
        'decimal' => true,
    ],
],
```

Per-instance props always override the global default, so individual usages can
still opt out (`<x-currency :decimal="false" wire:model="price" />`).

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
