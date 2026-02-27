# TallStackUI: Modal

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A modal overlay component for displaying content in a centered or top-aligned dialog with optional title, footer, blur backdrop, scrollable body, and configurable sizes. Can be controlled via `wire:model`, JavaScript API, or Livewire events.

## Basic Usage

```blade
<x-modal id="my-modal" title="Edit Profile">
    <p>Modal body content goes here.</p>

    <x-slot:footer>
        <x-button text="Save" />
    </x-slot:footer>
</x-modal>

<x-button text="Open Modal" x-on:click="$tsui.open.modal('my-modal')" />
```

```blade
<x-modal id="confirm-modal" title="Confirm Action" center persistent blur="md" size="sm">
    <p>Are you sure you want to continue?</p>

    <x-slot:footer>
        <x-button text="Cancel" x-on:click="$tsui.close.modal('confirm-modal')" />
        <x-button text="Confirm" color="red" wire:click="confirm" />
    </x-slot:footer>
</x-modal>
```

Using `wire:model` for Livewire-controlled state:

```blade
<x-modal wire="showModal" title="Livewire Modal">
    <p>Controlled by a Livewire property.</p>
</x-modal>

<x-button text="Toggle" wire:click="$toggle('showModal')" />
```

## Attributes

| Attribute  | Type               | Default                    | Description                                                                                                  |
|------------|--------------------|----------------------------|--------------------------------------------------------------------------------------------------------------|
| id         | string\|null       | 'modal'                    | Unique identifier used for targeting with JS API and events                                                  |
| zIndex     | string\|null       | null (from config: 'z-50') | CSS z-index class                                                                                            |
| wire       | string\|bool\|null | null                       | Livewire entangle property name (string) or boolean to use default 'modal'                                   |
| title      | string\|null       | null                       | Title text displayed in the modal header with a close button                                                 |
| footer     | string\|null       | null                       | Footer slot content                                                                                          |
| blur       | bool\|string\|null | null (from config: false)  | Backdrop blur effect (false, sm, md, lg, xl, or true for sm)                                                 |
| persistent | bool\|null         | null (from config: false)  | When true, prevents closing via outside click or Escape key                                                  |
| size       | string\|null       | null (from config: '2xl')  | Modal width (sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, full)                                             |
| entangle   | string\|null       | 'modal'                    | Livewire property name for entangle binding                                                                  |
| center     | bool\|null         | null (from config: false)  | When true, vertically centers the modal on all viewport sizes with full border radius and padding (v2-style) |
| overflow   | bool\|null         | null (from config: false)  | When true, avoids hiding body overflow                                                                       |
| scrollable | bool\|null         | null (from config: false)  | When true, fixes title and footer while body scrolls                                                         |

## Slots

| Slot      | Description                                                    |
|-----------|----------------------------------------------------------------|
| (default) | Main body content of the modal                                 |
| title     | Title text or custom markup in the modal header (as attribute) |
| footer    | Content rendered in the modal footer area                      |

## Validation Constraints

- The `wire` property cannot be an empty string.
- The `size` must be one of: sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, full.
- The `zIndex` must start with `z-` prefix.

## JavaScript Control

```js
$tsui.open.modal('my-modal')
$tsui.close.modal('my-modal')
```

## Configuration

In `config/tallstackui.php` under `components.modal`:

| Option     | Type          | Default | Description                                             |
|------------|---------------|---------|---------------------------------------------------------|
| z-index    | string        | 'z-50'  | Default z-index class                                   |
| overflow   | bool          | false   | When true, avoids hiding body overflow                  |
| blur       | false\|string | false   | Backdrop blur effect (false, sm, md, lg, xl)            |
| persistent | bool          | false   | When true, prevents closing by clicking outside         |
| size       | string        | '2xl'   | Default modal width                                     |
| center     | bool          | false   | When true, vertically centers the modal                 |
| scrollable | bool          | false   | When true, fixes title/footer while body scrolls        |
| scrollbar  | string\|null  | 'thin'  | Scrollbar style for scrollable mode (null, thin, thick) |

## Wireable Mode (Livewire Property Binding)

Use `wire` to bind modal visibility to a Livewire boolean property:

```blade
<!-- Livewire boolean property: $modal -->
<x-modal title="TallStackUi" wire>
    TallStackUi
</x-modal>

<x-button wire:click="$toggle('modal')">
    Open
</x-button>
```

Custom property name:

```blade
<!-- Livewire boolean property: $tallstackui -->
<x-modal title="TallStackUi" wire="tallstackui">
    TallStackUi
</x-modal>

<x-button wire:click="$toggle('tallstackui')">
    Open
</x-button>
```

## Alpine.js Events

```blade
<x-modal title="TallStackUi"
         x-on:open="alert('Opened!')"
         x-on:close="alert('Closed!')">
    TallStackUi
</x-modal>
```

## Focus Helper

Auto-focus an input when the modal opens using `$tsui.focus()`:

```blade
<x-modal id="modal-id" x-on:open="$tsui.focus('email')">
    <form>
        <x-input label="Email" id="email" hint="Insert your best email address" />
    </form>
</x-modal>
```

Optional delay in milliseconds: `$tsui.focus('email', 1000)`

You can also target elements by `data-focus` attribute instead of `id`:

```blade
<x-input label="Email" data-focus="email" />
```

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->modal()
    ->block('wrapper.first', 'your-tailwind-classes');
```

### Available Blocks

| Block Name         | Purpose                                       |
|--------------------|-----------------------------------------------|
| wrapper.first      | Fixed overlay backdrop                        |
| wrapper.second     | Fixed full-screen scroll container            |
| wrapper.third      | Centering flex container with size constraint |
| wrapper.fourth     | Modal card with rounded corners and shadow    |
| wrapper.scrollable | Max-height constraint for scrollable mode     |
| positions.top      | Alignment classes for top-positioned modal    |
| positions.center   | Alignment classes for centered modal          |
| blur.sm            | Small backdrop blur effect                    |
| blur.md            | Medium backdrop blur effect                   |
| blur.lg            | Large backdrop blur effect                    |
| blur.xl            | Extra-large backdrop blur effect              |
| title.wrapper      | Title bar container with border               |
| title.text         | Title heading styles                          |
| title.close        | Close button icon styles                      |
| body               | Body content area styles                      |
| body.scrollable    | Scrollable body overflow styles               |
| footer             | Footer container styles                       |
| footer.scrollable  | Sticky footer styles for scrollable mode      |
