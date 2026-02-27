# TallStackUI: Textarea

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A textarea component with optional manual resize, automatic height resizing, and a character counter that highlights when a maxlength limit is reached.

## Basic Usage

```blade
<x-textarea wire:model="description" label="Description" hint="Describe your item" />
```

```blade
<x-textarea wire:model="bio" label="Bio" resize />
```

```blade
<x-textarea wire:model="notes" label="Notes" resize-auto />
```

```blade
<x-textarea wire:model="comment" label="Comment" count maxlength="500" />
```

## Attributes

| Attribute   | Type                        | Default | Description                                                                    |
|-------------|-----------------------------|---------|--------------------------------------------------------------------------------|
| label       | string\|ComponentSlot\|null | null    | Label text displayed above the textarea                                        |
| hint        | string\|ComponentSlot\|null | null    | Hint text displayed below the textarea                                         |
| resize      | bool\|null                  | false   | Enables manual resize handle on the textarea                                   |
| resize-auto | bool\|null                  | false   | Enables automatic height adjustment as the user types                          |
| invalidate  | bool\|null                  | null    | Prevents displaying validation error messages for this textarea                |
| count       | bool\|null                  | false   | Shows a character counter below the textarea (pair with `maxlength` attribute) |

## Validation Constraints

- The `rows` attribute cannot be used together with `resize-auto` because rows have no effect when resizing is automatic.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->form('textarea')
    ->block('input.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name             | Purpose                                           |
|------------------------|---------------------------------------------------|
| input.wrapper          | Outer wrapper with ring and focus styles          |
| input.base             | Core textarea element styles                      |
| input.slot             | Slot text styles                                  |
| input.color.base       | Default ring and text colors                      |
| input.color.background | Background color for normal state                 |
| input.color.disabled   | Background color for disabled/readonly state      |
| error                  | Error state ring and text styles                  |
| count.base             | Character counter text positioning and style      |
| count.max              | Character counter style when maxlength is reached |
