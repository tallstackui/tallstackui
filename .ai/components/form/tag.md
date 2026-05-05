# TallStackUI: Tag

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A tag input component that allows users to add multiple tag values by pressing Enter, with individual tag removal, a bulk erase button, optional prefix character filtering, and configurable tag limits. The value is stored as an array.

## Basic Usage

```blade
<x-tag wire:model="tags" label="Tags" />
```

```blade
<x-tag wire:model="tags" label="Tags" :limit="5" />
```

```blade
<x-tag wire:model="tags" label="Hashtags" prefix="#" />
```

## Attributes

| Attribute  | Type                        | Default | Description                                                                                                                              |
|------------|-----------------------------|---------|------------------------------------------------------------------------------------------------------------------------------------------|
| label      | string\|ComponentSlot\|null | null    | Label text displayed above the input                                                                                                     |
| hint       | string\|ComponentSlot\|null | null    | Hint text displayed below the input                                                                                                      |
| prefix     | string\|null                | null    | A single character prefix automatically prepended to each tag                                                                            |
| limit      | int\|null                   | null    | Maximum number of tags allowed                                                                                                           |
| lazy       | int\|null                   | null    | Minimum length the typed content must reach before a tag is accepted on Enter or comma. The `prefix` character does not count toward it. |
| invalidate | bool\|null                  | null    | Prevents displaying validation error messages                                                                                            |

## Alpine.js Events

| Event       | Description                                     |
|-------------|-------------------------------------------------|
| x-on:remove | Triggered when an individual tag is removed     |
| x-on:erase  | Triggered when the bulk erase button is clicked |

## Validation Constraints

- The `prefix` must be a single character.
- The `lazy` must be greater than zero.

## Event Payload Details

```blade
<x-tag x-on:add="alert(`Introduced: ${$event.detail.tag}`)"
       x-on:remove="alert(`Removed: ${$event.detail.tag}`)"
       x-on:erase="alert(`Erased: ${$event.detail.tags}`)" />
```

Note: for the `erase` event the key is `$event.detail.tags` (plural), not `$event.detail.tag`.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('tag')
    ->block('wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name             | Purpose                                        |
|------------------------|------------------------------------------------|
| wrapper                | Flex container for tags and input              |
| label.base             | Individual tag badge styles                    |
| label.icon             | Tag remove icon size and color                 |
| input.base             | Inner text input styles                        |
| input.color.base       | Default ring and text colors                   |
| input.color.background | Background color                               |
| input.color.disabled   | Disabled/readonly background color             |
| input.slot             | Slot text styles (prefix/suffix area)          |
| input.wrapper          | Outer input wrapper with ring and focus styles |
| button.wrapper         | Erase button positioning                       |
| button.icon            | Erase button icon size and hover color         |
| error                  | Error state ring and text styles               |
