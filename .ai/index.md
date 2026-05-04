# TallStackUI Component Documentation

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.
>
> **Stack:** PHP 8.1+, Laravel 10/11/12/13, Livewire 3.5+, Tailwind CSS 4, Alpine.js 3

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
- [Clipboard](components/clipboard.md)
- [Environment](components/environment.md)
- [Errors](components/errors.md)
- [Icon](components/icon.md)
- [Kbd](components/kbd.md)
- [Key-Value](components/key-value.md) *(Livewire only)*
- [Link](components/link.md)
- [List](components/list/main.md)
- [List Items](components/list/items.md)
- [Stats](components/stats.md)
- [Table](components/table.md) *(Livewire only)*
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
- [Range](components/form/range.md)
- [Select Native](components/form/select/native.md)
- [Select Styled](components/form/select/styled.md)
- [Tag](components/form/tag.md)
- [Textarea](components/form/textarea.md)
- [Time Picker](components/form/time.md)
- [Toggle](components/form/toggle.md)
- [Upload](components/form/upload.md) *(Livewire only)*

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

### Theme

- [Theme Switch](components/theme-switch.md)

### Internal

- [Floating](components/floating.md) *(internal)*
- [Wrapper Input](components/wrapper/input.md) *(internal)*
- [Wrapper Radio](components/wrapper/radio.md) *(internal)*

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
```

### Customization Methods

- `block(name, classes)` - Set classes for a block
- `append(classes)` - Add classes to end
- `prepend(classes)` - Add classes to beginning
- `replace(from, to)` - Replace class patterns
- `remove(class)` - Remove classes

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

// Programmatic interactions
$tsui.interaction('dialog').success('Title', 'Description').send()
$tsui.interaction('toast').warning('Title').send()
```
