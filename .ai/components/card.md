# TallStackUI: Card

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A versatile card container with optional header, footer, image, color styling, minimize/expand toggle, close button, and Livewire loading indicator. Supports both solid and light color variants with bordered or background variations.

## Basic Usage

```blade
<x-card>
    This is a simple card body.
</x-card>
```

```blade
<x-card header="User Profile">
    <p>Name: John Doe</p>
    <x-slot:footer>
        <x-button text="Save" />
    </x-slot:footer>
</x-card>
```

```blade
<x-card image="https://example.com/cover.jpg"
        position="top"
        header="Featured Article"
        minimize="true"
        close>
    Article content goes here.
</x-card>
```

```blade
{{-- Default radius (rounded-lg). --}}
<x-card>
    Default
</x-card>

{{-- Bare flag preserves the default radius. --}}
<x-card round>
    Default (explicit flag)
</x-card>

{{-- Named sizes from xs to 2xl. --}}
<x-card round="xs">Tiny corners</x-card>
<x-card round="2xl">Generous corners</x-card>
```

## Attributes

| Attribute | Type               | Default | Description                                                                                                                                                        |
|-----------|--------------------|---------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| color     | string\|null       | null    | Color theme for the header background                                                                                                                              |
| light     | bool               | null    | Uses the light color style variant                                                                                                                                 |
| bordered  | bool               | null    | Uses a bordered variation instead of background fill                                                                                                               |
| minimize  | string\|null       | null    | Enables minimize/maximize toggle; set to 'mount' to start minimized                                                                                                |
| close     | bool               | null    | Shows a close button to hide the entire card                                                                                                                       |
| loading   | string\|bool\|null | null    | Livewire `wire:target` value that shows an indeterminate loading bar and a semi-transparent overlay that disables interaction                                      |
| delay     | string\|null       | null    | Livewire loading delay modifier (e.g., 'long', 'longest')                                                                                                          |
| image     | string\|null       | null    | URL for a card image                                                                                                                                               |
| position  | string\|null       | 'top'   | Image position: 'top' or 'bottom'                                                                                                                                  |
| round     | bool\|string       | false   | Border radius size of the card wrapper. Accepts `xs`, `sm`, `md`, `lg`, `xl`, or `2xl`. When omitted or set to `true`, keeps the component default (`rounded-lg`). |

## Slots

| Slot      | Description                                                                               |
|-----------|-------------------------------------------------------------------------------------------|
| (default) | Main card body content                                                                    |
| header    | Card header area; accepts plain string or ComponentSlot for custom markup                 |
| footer    | Card footer area; accepts plain string (right-aligned) or ComponentSlot for custom layout |

## Events

The Card component dispatches Alpine.js `CustomEvent`s when its state changes. Listen using `x-on:` directives.

| Event      | Fired When                    |
|------------|-------------------------------|
| `minimize` | Card is minimized (collapsed) |
| `maximize` | Card is maximized (expanded)  |
| `close`    | Card is closed (hidden)       |

```blade
<x-card header="Products" minimize="mount"
        x-on:maximize="$wire.loadProducts()"
        x-on:minimize="console.log('minimized')"
        x-on:close="console.log('closed')">
    ...
</x-card>
```

## Validation Constraints

- The `image` and `color` attributes cannot be used together.
- When `round` is set to a string, it must be one of: `xs`, `sm`, `md`, `lg`, `xl`, `2xl`.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->card()
    ->block('body', 'your-tailwind-classes');
```

### Available Blocks

| Block Name              | Purpose                                                   |
|-------------------------|-----------------------------------------------------------|
| wrapper.first           | Outer flex container                                      |
| wrapper.second          | Inner card container with background and shadow           |
| header.wrapper.base     | Header flex layout and padding                            |
| header.wrapper.border   | Bottom border shown when card is expanded                 |
| header.wrapper.minimize | Border radius applied when card is minimized              |
| header.text.size        | Header text font size and weight                          |
| header.text.color       | Header text color                                         |
| body                    | Card body padding and text color                          |
| footer.wrapper          | Footer container with top border                          |
| footer.text             | Footer content alignment (flex, end-aligned)              |
| button.minimize         | Minimize button icon dimensions                           |
| button.maximize         | Maximize button icon dimensions                           |
| button.close            | Close button icon dimensions                              |
| image.wrapper           | Image container flex layout                               |
| image.rounded.top       | Top image border radius                                   |
| image.rounded.bottom    | Bottom image border radius                                |
| image.size              | Image width                                               |
| loading.wrapper         | Loading bar outer container                               |
| loading.bar             | Loading bar animation element                             |
| loading.overlay         | Semi-transparent overlay covering the card during loading |
| border.radius.xs        | Border radius applied when `round="xs"`                   |
| border.radius.sm        | Border radius applied when `round="sm"`                   |
| border.radius.md        | Border radius applied when `round="md"`                   |
| border.radius.lg        | Border radius applied when `round="lg"` (default)         |
| border.radius.xl        | Border radius applied when `round="xl"`                   |
| border.radius.2xl       | Border radius applied when `round="2xl"`                  |
