# TallStackUI Component Documentation

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.
>
> **Stack:** PHP 8.1+, Laravel 10/11/12/13, Livewire 4, Tailwind CSS 4, Alpine.js 3

## Component Index

### Display

- [Alert](components/alert.md)
- [Avatar](components/avatar.md)
- [Avatar Group](components/avatar/group.md)
- [Back to Top](components/back-to-top.md)
- [Badge](components/badge.md)
- [Banner](components/banner.md)
- [Boolean](components/boolean.md)
- [Breadcrumbs](components/breadcrumbs.md)
- [Calendar](components/calendar.md)
- [Card](components/card.md)
- [Carousel](components/carousel.md)
- [Chart](components/chart.md)
- [Clipboard](components/clipboard.md)
- [Editor](components/editor.md)
- [Environment](components/environment.md)
- [Errors](components/errors.md)
- [Gallery](components/gallery.md)
- [Icon](components/icon.md)
- [Kbd](components/kbd.md)
- [Key-Value](components/key-value.md) *(Livewire only)*
- [Link](components/link.md)
- [List](components/list/main.md)
- [List Items](components/list/items.md)
- [QrCode](components/qr-code.md)
- [Stats](components/stats.md)
- [Table](components/table.md)
- [Timeline](components/timeline/main.md)
- [Timeline Items](components/timeline/items.md)
- [Tooltip](components/tooltip.md)

### Buttons

- [Button](components/button/normal.md)
- [Button Circle](components/button/circle.md)
- [Button Group](components/button/group.md)

### Form

- [Autocomplete](components/form/autocomplete.md)
- [Checkbox](components/form/checkbox.md)
- [Checkbox Group](components/form/checkbox/group.md)
- [Color Picker](components/form/color.md)
- [Currency](components/form/currency.md)
- [Date Picker](components/form/date.md)
- [Error](components/form/error.md)
- [Hint](components/form/hint.md)
- [Input](components/form/input.md)
- [Input Select](components/form/input-select.md)
- [Label](components/form/label.md)
- [Number](components/form/number.md)
- [Password](components/form/password.md)
- [Pin](components/form/pin.md)
- [Radio](components/form/radio.md)
- [Radio Group](components/form/radio/group.md)
- [Range](components/form/range.md)
- [Select Native](components/form/select/native.md)
- [Select Styled](components/form/select/styled.md)
- [Swap](components/swap.md)
- [Tag](components/form/tag.md)
- [Textarea](components/form/textarea.md)
- [Time Picker](components/form/time.md)
- [Toggle](components/form/toggle.md)
- [Upload](components/form/upload.md) *(Livewire only)*
- [Upload Async](components/form/upload/async.md)

### Overlay & Interaction

- [Command Palette](components/command-palette.md)
- [Dialog](components/dialog.md)
- [Dropdown](components/dropdown/main.md)
- [Dropdown Items](components/dropdown/items.md)
- [Dropdown Submenu](components/dropdown/submenu.md)
- [Loading](components/loading.md) *(Livewire only)*
- [Modal](components/modal.md)
- [Slide](components/slide.md)
- [Toast](components/toast.md)

### Navigation & Layout

- [Accordion](components/accordion/main.md)
- [Accordion Items](components/accordion/items.md)
- [Dial](components/dial/main.md)
- [Dial Items](components/dial/items.md)
- [Layout](components/layout/main.md)
- [Layout Header](components/layout/header.md)
- [Sidebar](components/layout/sidebar/main.md)
- [Sidebar Item](components/layout/sidebar/item.md)
- [Sidebar Separator](components/layout/sidebar/separator.md)
- [Step](components/step/main.md)
- [Step Items](components/step/items.md)
- [Tab](components/tab/main.md)
- [Tab Items](components/tab/items.md)

### Progress & Feedback

- [Progress Bar](components/progress/bar.md)
- [Progress Circle](components/progress/circle.md)
- [Rating](components/rating.md)
- [Reaction](components/reaction.md) *(Livewire only)*
- [Signature](components/signature.md) *(Livewire only)*
- [Spinner](components/spinner.md)

### Theme

- [Theme Switch](components/theme-switch.md)

### Internal

- [Floating](components/floating.md) *(internal)*
- [Wrapper Input](components/wrapper/input.md) *(internal)*
- [Wrapper Radio](components/wrapper/radio.md) *(internal)*

## Binding to a nested property

`wire:model` accepts a nested path, which is what a Livewire Form object and any nested
array look like. Only the head of the path has to be a real property on the component:

```blade
<x-key-value wire:model="form.metadata" />
<x-upload wire:model="form.files" multiple delete />
<x-date wire:model="filters.period" range />
```

## Outside Livewire

The library was built for Livewire, but the form components also work on a plain Blade
page posting to a controller. Give the component a `name` instead of a `wire:model` and
it renders a hidden input carrying the value, so the server receives it like any other
field. `value` seeds the initial state.

```blade
<form method="POST" action="/products">
    @csrf
    <x-currency name="price" symbol currency />
    <x-date name="published_at" />
    <x-time name="starts_at" />
    <x-color name="brand" />
    <x-pin name="code" :length="4" />
    <x-tag name="tags" />
    <x-select.styled name="status" :options="$options" select="label:label|value:value" />
    <x-autocomplete name="city" :items="$cities" />
    <x-calendar name="scheduled_at" />
</form>
```

What arrives on the server depends on the component: a single value goes as is, and a
multi-value selection is JSON encoded. Each component's page states its own shape, and
`Currency` additionally offers three formats through `mutate` and `decimal`.

Components that carry no value — Modal, Slide, Toast and friends — are unaffected either
way. Livewire's script still has to be on the page, since that is where Alpine comes
from.

## Readonly and disabled

Every form component accepts both, and they mean the same thing except for one
detail: `disabled` does not submit the value, `readonly` does. The visual is
identical, and neither can be interacted with.

```blade
<x-input wire:model="name" readonly />
<x-toggle wire:model="active" label="Active" disabled />
<x-select.native :options="$options" readonly />
<x-date wire:model="period" range disabled />
```

The browser only honours `readonly` on text inputs and textareas. On a checkbox,
radio, range or select it does nothing at all, so the library emulates it: the
control keeps its enabled state — which is what makes it submit — and the pointer
and keyboard are swallowed instead. Buttons that live inside a component (the
clear on `Date`, the tag remove on `Tag`, the palette on `Color`) are disabled
outright, since a button carries no value.

Supported by `Input`, `Textarea`, `Password`, `Number`, `Currency`, `Color`,
`Date`, `Time`, `Tag`, `Pin`, `Range`, `Checkbox`, `Radio`, `Toggle`, `Upload`,
`Upload/Async`, `Autocomplete`, `Select/Native`, `Select/Styled`, `Input/Select`,
`Swap` and the `Checkbox`/`Radio` groups.

`Input/Select` passes its own lock down to the component in the `left`/`right`
slot, so the compound control locks as one. A lock declared on the slot's own
component still wins:

```blade
{{-- both halves locked --}}
<x-input.select wire:model="phone" readonly>
    <x-slot:left>
        <x-select.native wire:model="code" :options="$codes" side="left" />
    </x-slot:left>
</x-input.select>

{{-- text locked, select still usable --}}
<x-input.select wire:model="phone" readonly>
    <x-slot:left>
        <x-select.native wire:model="code" :options="$codes" side="left" :readonly="false" />
    </x-slot:left>
</x-input.select>
```

Components paint the state through a `locked` soft customization block, applied by
the template rather than through Tailwind's `disabled:` variant — that variant
cannot match an element that is only `readonly`.

## Global Configuration

Top-level keys in `config/tallstackui.php`, applying across components rather
than to a single one. Per-component options live under `components.<name>` and
are documented on each component's page.

| Key                    | Type   | Default | Description                                                                            |
|------------------------|--------|---------|----------------------------------------------------------------------------------------|
| `prefix`               | string | null    | Prefixes every component, so `ts-` gives `<x-ts-alert />`                              |
| `invalidate_global`    | bool   | false   | Suppresses validation errors on every form component (`TALLSTACKUI_INVALIDATE_GLOBAL`) |
| `floating_scroll_lock` | bool   | false   | Locks the page scroll while any floating-based popup is open                           |

`debug.environments` is a comma-separated list via `TALLSTACKUI_DEBUG_ENVIRONMENTS`
(default `local,sandbox,staging`).

`floating_scroll_lock` covers Dropdown and its Submenu, Autocomplete, Color,
Date, Password, Select Styled, Time, Upload, Calendar and the List Items menu —
every component built on [Floating](components/floating.md), where the reference
counting and the interaction with modals are described.

### How the published file is merged

`php artisan vendor:publish --tag=tallstackui.config` writes `config/tallstackui.php`,
which is merged over the package defaults. Keys the file does not mention keep their
default, so a file written against an older release does not lose options added since.

Lists of scalars are the exception: they are taken as published rather than merged
entry by entry, which is what lets a published list be shorter than the default.

```php
// package default: [10, 25, 50, 100]
'quantity' => [15, 30],   // the table offers exactly 15 and 30
```

The same applies to `editor.toolbar`, `editor.sanitization.allowed_tags`,
`editor.upload.mimes` and `debug.environments`: publishing a shorter list narrows
what is allowed instead of adding to it.

## Skeleton

Seven components accept a `skeleton` prop that renders a structural placeholder
shaped like the component itself, for the first paint before any data exists:
[Card](components/card.md#skeleton), [Stats](components/stats.md#skeleton),
[Table](components/table.md#skeleton), [List](components/list/main.md#skeleton),
[Step](components/step/main.md#skeleton), [Chart](components/chart.md#skeleton)
and [QR Code](components/qr-code.md#skeleton).

```blade
<x-card skeleton />                          {{-- bare flag: 3 body lines --}}
<x-table :$headers skeleton="8" paginate />  {{-- integer: 8 rows --}}
```

| Component | Unit                    | Default |
|-----------|-------------------------|---------|
| Card      | body lines              | 3       |
| Table     | rows                    | 5       |
| List      | items                   | 4       |
| Step      | step indicators         | 3       |
| Chart     | data points (or slices) | 6       |
| Stats     | — (flag only)           | n/a     |
| QR Code   | — (flag only)           | n/a     |

Everything else is derived from props the component already has. The prop is
`bool|int|null` everywhere. Any integer below `1` throws. `Stats` throws on any
integer, and `QR Code` ignores it — both draw a fixed shape with nothing to count.

What is already known stays legible; only the unknown becomes a bar — the table
keeps its real header labels, for instance.

It belongs in the `placeholder()` of a `#[Lazy]` Livewire component, but works
anywhere, including plain Blade with no Livewire at all.

**It does not replace `loading`.** `loading` (Card and Table only) dims content
already on screen during a Livewire round trip; `skeleton` stands in for content
that does not exist yet. And it defers nothing: Blade evaluates slot content
before the component renders, so deferral is Livewire's job.

**Existing soft customizations carry over.** The skeleton view calls the same
`classes()` as the normal one, so every structural block a component already has
— wrappers, radius, padding, header and footer chrome — applies to the
placeholder too, scopes included. Only the bars come from the `skeleton.*`
blocks. Blocks the placeholder does not render simply have no target there.

```php
TallStackUi::customize()->card()->block('wrapper.second', 'rounded-3xl bg-white');
```

```blade
<x-card skeleton />   {{-- rounded-3xl, exactly like the real card --}}
```

## Soft Customization

All components support runtime customization of their Tailwind CSS classes:

```php
// In AppServiceProvider::boot()
TallStackUi::customize()
    ->alert()
    ->block('wrapper', 'your-tailwind-classes');

// Scoped customization
TallStackUi::customize('alert', scope: 'hero')->block('wrapper', 'your-tailwind-classes');
// Then: <x-alert scope="hero" />

// Extending a scope that already exists, including the ones the package ships
TallStackUi::customize()
    ->extend(scope: 'card-shadowless')
    ->card()
    ->block('wrapper.second')
    ->append('ring-1 ring-gray-100');
```

### Customization Methods

- `block(name, classes)` - Set classes for a block
- `append(classes)` - Add classes to end
- `prepend(classes)` - Add classes to beginning
- `replace(from, to)` - Replace a substring, so `replace('gray-', 'zinc-')` swaps a palette
- `remove(class)` - Remove whole classes, matched by token; accepts a list or a space-separated string
- `scope(name)` - Define a scope, replacing it if the name is already taken
- `extend(scope: name)` - Reuse a scope already defined for that component; throws when it does not exist

Customizations of the same block stack: two chains, or two service providers,
each add on top of what the other did rather than overwriting it.

### Scopes layer over the global customization

A scope only overrides the blocks it names. Every other block keeps whatever the
global customization did to it, so a scoped instance is the global look plus the
scope's changes, not a reset:

```php
TallStackUi::customize()->alert()->block('wrapper')->append('brand-shadow');
TallStackUi::customize('alert', scope: 'flat')->block('text.title')->append('text-xl');
```

`<x-alert scope="flat" />` renders with both `brand-shadow` and `text-xl`. This applies
to the scopes the package ships as well, such as `<x-card scope="card-shadowless">`.

Block names containing a dot (`body.paddingless`, `wrapper.second`) are keys, not paths.
A scope can set `body` and `body.paddingless` in the same call without one replacing the
other.

## Global JavaScript API

```javascript
// Modal control
$tsui.open.modal('name')
$tsui.close.modal('name')

// Slide control
$tsui.open.slide('name')
$tsui.close.slide('name')

// Select control
$tsui.open.select('name')
$tsui.close.select('name')

// Command Palette
$tsui.open.commandPalette()
$tsui.close.commandPalette()

// Focus element
$tsui.focus('element-id')

// Programmatic clipboard copy
// Resolves to a boolean (success) and dispatches the `ts-ui:copy`
// event on `window` with `{ detail: { text } }`.
const copied = await $tsui.copy('text to copy')

// Programmatic interactions
$tsui.interaction('dialog').success('Title', 'Description').send()
$tsui.interaction('toast').warning('Title').send()
```
