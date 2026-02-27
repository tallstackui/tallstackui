# TallStackUI: Slide

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A slide-over panel component that animates in from the edge of the screen (right, left, top, or bottom) with optional title, footer, blur backdrop, and configurable sizes. Useful for secondary navigation, detail views, or forms without leaving the current page.

## Basic Usage

```blade
<x-slide id="settings-slide" title="Settings">
    <p>Slide panel content goes here.</p>

    <x-slot:footer>
        <x-button text="Save" />
    </x-slot:footer>
</x-slide>

<x-button text="Open Settings" x-on:click="$tsui.open.slide('settings-slide')" />
```

```blade
<x-slide id="left-panel" title="Navigation" left size="sm">
    <nav>
        <a href="/dashboard">Dashboard</a>
        <a href="/profile">Profile</a>
    </nav>
</x-slide>
```

```blade
<x-slide id="bottom-drawer" title="Details" bottom size="md" blur="md" persistent>
    <p>Bottom slide-over content.</p>
</x-slide>
```

Using `wire:model` for Livewire-controlled state:

```blade
<x-slide wire="showSlide" title="Livewire Slide">
    <p>Controlled by a Livewire property.</p>
</x-slide>

<x-button text="Toggle" wire:click="$toggle('showSlide')" />
```

## Attributes

| Attribute  | Type                        | Default                    | Description                                                                |
|------------|-----------------------------|----------------------------|----------------------------------------------------------------------------|
| id         | string\|null                | 'slide'                    | Unique identifier used for targeting with JS API and events                |
| zIndex     | string\|null                | null (from config: 'z-50') | CSS z-index class                                                          |
| wire       | string\|bool\|null          | null                       | Livewire entangle property name (string) or boolean to use default 'slide' |
| title      | ComponentSlot\|string\|null | null                       | Title text or slot displayed in the slide header                           |
| footer     | ComponentSlot\|string\|null | null                       | Footer content or slot                                                     |
| blur       | bool\|string\|null          | null (from config: false)  | Backdrop blur effect (false, sm, md, lg, xl, or true for sm)               |
| persistent | bool\|null                  | null (from config: false)  | When true, prevents closing via outside click or Escape key                |
| size       | string\|null                | null (from config: 'lg')   | Panel size (sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, full)            |
| entangle   | string\|null                | 'slide'                    | Livewire property name for entangle binding                                |
| center     | bool\|null                  | null                       | Vertical centering (not commonly used with slides)                         |
| overflow   | bool\|null                  | null (from config: false)  | When true, avoids hiding body overflow                                     |
| left       | bool\|null                  | null                       | When true, slide enters from the left side                                 |
| top        | bool\|null                  | null                       | When true, slide enters from the top                                       |
| bottom     | bool\|null                  | null                       | When true, slide enters from the bottom                                    |

## Slots

| Slot      | Description                                                                             |
|-----------|-----------------------------------------------------------------------------------------|
| (default) | Main body content of the slide panel                                                    |
| title     | Title content (supports ComponentSlot with custom attributes)                           |
| footer    | Footer content (supports ComponentSlot with `start` and `end` attributes for alignment) |

## Validation Constraints

- The `wire` property cannot be an empty string.
- The `size` must be one of: sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, full.
- The `zIndex` must start with `z-` prefix.
- The position must be one of: right, left, top, bottom.

## JavaScript Control

```js
$tsui.open.slide('settings-slide')
$tsui.close.slide('settings-slide')
```

## Configuration

In `config/tallstackui.php` under `components.slide`:

| Option     | Type          | Default | Description                                     |
|------------|---------------|---------|-------------------------------------------------|
| z-index    | string        | 'z-50'  | Default z-index class                           |
| overflow   | bool          | false   | When true, avoids hiding body overflow          |
| blur       | false\|string | false   | Backdrop blur effect (false, sm, md, lg, xl)    |
| persistent | bool          | false   | When true, prevents closing by clicking outside |
| size       | string        | 'lg'    | Default panel size                              |
| position   | string        | 'right' | Default position (right, left, top, bottom)     |

## Wireable Mode (Livewire Property Binding)

Use `wire` to bind slide visibility to a Livewire boolean property:

```blade
<!-- Livewire boolean property: $slide -->
<x-slide title="TallStackUi" wire>
    TallStackUi
</x-slide>

<x-button wire:click="$toggle('slide')">
    Open
</x-button>
```

Custom property name:

```blade
<!-- Livewire boolean property: $tallstackui -->
<x-slide title="TallStackUi" wire="tallstackui">
    TallStackUi
</x-slide>

<x-button wire:click="$toggle('tallstackui')">
    Open
</x-button>
```

## Alpine.js Events

```blade
<x-slide title="TallStackUi"
         x-on:open="alert('Opened!')"
         x-on:close="alert('Closed!')">
    TallStackUi
</x-slide>
```

## Focus Helper

Auto-focus an input when the slide opens:

```blade
<x-slide id="slide-id" x-on:open="$tsui.focus('email')">
    <form>
        <x-input label="Email" id="email" hint="Insert your best email address" />
    </form>
</x-slide>
```

Optional delay: `$tsui.focus('email', 1000)`. Also supports `data-focus` attribute targeting.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->slide()
    ->block('wrapper.first', 'your-tailwind-classes');
```

### Available Blocks

| Block Name     | Purpose                                      |
|----------------|----------------------------------------------|
| wrapper.first  | Fixed overlay backdrop                       |
| wrapper.second | Fixed full-screen overflow container         |
| wrapper.third  | Absolute overflow wrapper                    |
| wrapper.fourth | Pointer-events container with positioning    |
| wrapper.fifth  | Panel flex column with background and shadow |
| blur.sm        | Small backdrop blur effect                   |
| blur.md        | Medium backdrop blur effect                  |
| blur.lg        | Large backdrop blur effect                   |
| blur.xl        | Extra-large backdrop blur effect             |
| title.text     | Title heading styles                         |
| title.close    | Close button icon styles                     |
| body           | Scrollable body content area styles          |
| footer         | Footer container with border styles          |
| header         | Header padding container                     |
