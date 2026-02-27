# TallStackUI: Loading

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

> **Requires Livewire:** This component must be used within a Livewire component.

A full-screen loading overlay that displays automatically during Livewire requests, with optional spinner, custom text, blur effect, and opacity. Uses `wire:loading` under the hood to show/hide based on Livewire processing state.

## Basic Usage

```blade
<x-loading />
```

```blade
<x-loading loading="save, delete" text="Processing your request..." />
```

```blade
<x-loading loading="uploadFile" delay="longest" :blur="true">
    <div class="flex flex-col items-center gap-2">
        <span class="animate-spin h-8 w-8 border-4 border-primary-500 border-t-transparent rounded-full"></span>
        <span>Uploading...</span>
    </div>
</x-loading>
```

## Attributes

| Attribute | Type         | Default                    | Description                                                                                     |
|-----------|--------------|----------------------------|-------------------------------------------------------------------------------------------------|
| zIndex    | string\|null | null (from config: 'z-50') | CSS z-index class                                                                               |
| text      | string\|null | null                       | Text displayed in place of the default spinner                                                  |
| loading   | string\|null | null                       | Comma-separated Livewire method names to scope the loading indicator to (maps to `wire:target`) |
| delay     | string\|null | null                       | Delay modifier for `wire:loading` (e.g., 'short', 'long', 'longest')                            |
| blur      | bool\|null   | null (from config: false)  | Enables backdrop blur effect                                                                    |
| opacity   | bool\|null   | true (from config: true)   | Enables background opacity effect                                                               |
| overflow  | bool\|null   | null (from config: false)  | When true, avoids hiding body overflow                                                          |

## Slots

| Slot      | Description                                                       |
|-----------|-------------------------------------------------------------------|
| (default) | Custom content displayed in place of the default spinner and text |

## Validation Constraints

- The `zIndex` (from config `z-index`) must start with `z-` prefix.

## Configuration

In `config/tallstackui.php` under `components.loading`:

| Option   | Type   | Default | Description                            |
|----------|--------|---------|----------------------------------------|
| z-index  | string | 'z-50'  | Default z-index class                  |
| overflow | bool   | false   | When true, avoids hiding body overflow |
| blur     | bool   | false   | Enables background blur effect         |
| opacity  | bool   | true    | Enables background opacity effect      |

## Livewire Integration Details

### Simplified Syntax

Instead of raw `wire:loading` directives, use the component's `loading` and `delay` attributes:

```blade
<!-- Instead of: <x-loading wire:loading.delay.longest wire:target="save" /> -->
<x-loading delay="longest" loading="save" />
```

### Basic Usage

Place the component in your Livewire view. It automatically displays during any Livewire request:

```blade
<div>
    <x-loading />

    <form wire:submit="save">
        <input type="text" wire:model="title">
        <button type="submit">Save</button>
    </form>
</div>
```

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->loading()
    ->block('wrapper.first', 'your-tailwind-classes');
```

### Available Blocks

| Block Name     | Purpose                                                    |
|----------------|------------------------------------------------------------|
| wrapper.first  | Fixed full-screen background overlay                       |
| wrapper.second | Centering flex container                                   |
| opacity        | Background opacity class (applied when opacity is enabled) |
| blur           | Backdrop blur class (applied when blur is enabled)         |
| spinner        | Default loading spinner icon styles                        |
| text           | Text content styles when using custom text                 |
