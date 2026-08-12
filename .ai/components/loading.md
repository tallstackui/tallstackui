# TallStackUI: Loading

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

> **Requires Livewire:** This component must be used within a Livewire component.

A full-screen loading overlay that displays automatically during Livewire requests, with optional spinner, custom text, blur effect, and opacity. Uses `wire:loading` under the hood to show/hide based on Livewire processing state.

## Basic Usage

```blade
<x-loading />
```

```blade
<x-loading indicator="spinner.bars" />
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

## Indicator

The default overlay is the original SVG. `indicator` swaps it for a
[Spinner](spinner.md). The inline prop wins over the `loading.indicator`
config default:

```blade
<x-loading />
<x-loading indicator="spinner.bars" />
<x-loading indicator="spinner" />
```

| Value            | Result                                                |
|------------------|-------------------------------------------------------|
| `null`           | Original SVG (the shipped default)                    |
| `spinner`        | `<x-spinner>` using that component's own type default |
| `spinner.{type}` | `<x-spinner>` pinned to that variant                  |

An unknown prefix or type throws. `shimmer` and `caret` still require their
own text, and Loading's `text`/slot replace the indicator entirely, so those
two throw:

```blade
<x-loading indicator="spinner.shimmer" />   {{-- throws --}}
```

The overlay renders the real `<x-spinner>` component — prefix-aware, so a
customized Spinner is the one that shows up.

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
| indicator | string\|null | null (from config: null)   | Overlay indicator: `null` keeps the SVG, `spinner` or `spinner.{type}` renders a Spinner        |

## Body overflow

By default the page scroll is locked while the request is in flight and released once the
response is morphed in, so the page cannot be scrolled behind the overlay. `overflow`
opts out of that. The lock is also released when a request fails or is cancelled, which
never reaches the morph.

## Slots

| Slot      | Description                                                       |
|-----------|-------------------------------------------------------------------|
| (default) | Custom content displayed in place of the default spinner and text |

## Validation Constraints

- The `zIndex` (from config `z-index`) must start with `z-` prefix.
- The `indicator` must be `null`, `spinner`, or `spinner.{type}` where type is one of the Spinner variants. `shimmer` and `caret` throw because they animate their own text.

## Configuration

In `config/tallstackui.php` under `components.loading`:

| Option    | Type         | Default | Description                                                                                 |
|-----------|--------------|---------|---------------------------------------------------------------------------------------------|
| z-index   | string       | 'z-50'  | Default z-index class                                                                       |
| overflow  | bool         | false   | When true, avoids hiding body overflow                                                      |
| blur      | bool         | false   | Enables background blur effect                                                              |
| opacity   | bool         | true    | Enables background opacity effect                                                           |
| indicator | string\|null | null    | Default overlay indicator. `null` keeps the SVG; `spinner` or `spinner.{type}` uses Spinner |

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
