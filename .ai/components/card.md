# TallStackUI: Card

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A versatile card container with optional header, footer, image, color styling, minimize/expand toggle, close button, and Livewire loading indicator. Supports both solid and light color variants with accent (colored top border) or background variations.

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

```blade
{{-- The table draws its own spacing, so the body padding only doubles it. --}}
<x-card paddingless header="Users">
    <x-table :$headers :$rows />
    <x-slot:footer>
        <x-button text="Export" />
    </x-slot:footer>
</x-card>
```

```blade
{{-- Pushing a destructive action away from the primary one. --}}
<x-card header="Account">
    <p>Body content.</p>

    <x-slot:footer between>
        <x-button text="Delete" color="red" wire:click="delete" />
        <x-button text="Save" wire:click="save" />
    </x-slot:footer>
</x-card>
```

```blade
{{-- The footer draws its own layout, so the aligning wrapper only gets in the way. --}}
<x-card header="Plan">
    <p>Body content.</p>

    <x-slot:footer unwrapped>
        <div class="grid grid-cols-3 gap-2">
            <x-button text="Monthly" />
            <x-button text="Yearly" />
            <x-button text="Lifetime" />
        </div>
    </x-slot:footer>
</x-card>
```

## Attributes

| Attribute   | Type               | Default | Description                                                                                                                                                        |
|-------------|--------------------|---------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| color       | string\|null       | null    | Color theme for the header background                                                                                                                              |
| light       | bool               | null    | Uses the light color style variant                                                                                                                                 |
| accent      | bool               | null    | Uses a colored top border on the header instead of background fill                                                                                                 |
| shadowless  | bool               | null    | Removes the wrapper shadow                                                                                                                                         |
| bordered    | bool               | null    | Adds a border to the wrapper. Combine with `shadowless` for a flat look                                                                                            |
| minimize    | string\|null       | null    | Enables minimize/maximize toggle; set to 'mount' to start minimized                                                                                                |
| close       | bool               | null    | Shows a close button to hide the entire card                                                                                                                       |
| loading     | string\|bool\|null | null    | Livewire `wire:target` value that shows an indeterminate loading bar and a semi-transparent overlay that disables interaction                                      |
| delay       | string\|null       | null    | Livewire loading delay modifier (e.g., 'long', 'longest')                                                                                                          |
| image       | string\|null       | null    | URL for a card image                                                                                                                                               |
| position    | string\|null       | 'top'   | Image position: 'top' or 'bottom'                                                                                                                                  |
| round       | bool\|string       | false   | Border radius size of the card wrapper. Accepts `xs`, `sm`, `md`, `lg`, `xl`, or `2xl`. When omitted or set to `true`, keeps the component default (`rounded-lg`). |
| paddingless | bool\|null         | null    | When true, removes the padding of the body, leaving the default slot flush against the card edges. Header and footer keep their padding.                           |
| skeleton    | bool\|int\|null    | null    | Renders a structural placeholder instead of the content. A bare flag draws 3 body lines; an integer sets the count. See [Skeleton](#skeleton)                      |

## Slots

| Slot      | Description                                                                     |
|-----------|---------------------------------------------------------------------------------|
| (default) | Main card body content                                                          |
| header    | Card header area; accepts plain string or ComponentSlot for custom markup       |
| footer    | Card footer area; accepts plain string or ComponentSlot, end-aligned by default |

### Footer Slot Attributes

| Attribute | Description                                                        |
|-----------|--------------------------------------------------------------------|
| start     | Aligns the footer content to the start                             |
| center    | Centers the footer content                                         |
| end       | Aligns the footer content to the end (default when none is passed) |
| between   | Distributes the footer content with space between                  |
| unwrapped | Drops the aligning wrapper, keeping the footer border and padding  |

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

## Skeleton

Renders a placeholder shaped like the card, for the first paint before any data
exists. Meant for the `placeholder()` of a `#[Lazy]` Livewire component.

```blade
<x-card skeleton />                                {{-- 3 body lines --}}
<x-card skeleton="6" />                            {{-- 6 body lines --}}
<x-card skeleton="2" header="Users" round="xl">    {{-- header, radius honoured --}}
    <x-slot:footer>Saved</x-slot:footer>
</x-card>
```

The header bar, image block and footer bar are drawn only when the matching prop
or slot is present, and `round` / `paddingless` are honoured, so the placeholder
occupies the same box the real card will.

`skeleton` is not `loading`: `loading` dims content already on screen during a
Livewire refetch, `skeleton` stands in for content that does not exist yet.
Neither replaces the other. Note that `skeleton` defers nothing — Blade evaluates
slot content before the component renders.

Any integer below `1` throws.

## Validation Constraints

- The `image` and `color` attributes cannot be used together.
- When `round` is set to a string, it must be one of: `xs`, `sm`, `md`, `lg`, `xl`, `2xl`.
- The `footer` slot cannot combine two or more alignments.
- The `footer` slot cannot use `unwrapped` together with an alignment.

### Customizations Carry Over

The `skeleton.*` blocks are only the bars. Everything structural is resolved
from this component's **own, existing blocks**, because the skeleton view calls
the same `classes()` as the normal one — customization is resolved on the
component, not on the view. Whatever you already changed applies to the
placeholder too, so the box keeps matching the box it stands in for. Scopes
work the same, including when they target the placeholder alone.

Card reuses `wrapper.first`, `wrapper.second`, `border.radius.*`, `header.wrapper.base`, `header.wrapper.border`, `body`, `body.paddingless`, `footer.wrapper` and `image.wrapper`.

Blocks the placeholder does not render have nothing to act on there.
Customizing them is not an error; it simply has no effect while the skeleton
is on screen.

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
| shadowless              | Shadow reset applied when `shadowless` is set             |
| bordered                | Border classes applied when `bordered` is set             |
| header.wrapper.base     | Header flex layout and padding                            |
| header.wrapper.border   | Bottom border shown when card is expanded                 |
| header.wrapper.minimize | Border radius applied when card is minimized              |
| header.text.size        | Header text font size and weight                          |
| header.text.color       | Header text color                                         |
| body                    | Card body padding and text color                          |
| body.paddingless        | Padding reset applied when `paddingless` is set           |
| footer.wrapper          | Footer container with top border                          |
| footer.base             | Footer aligning wrapper (flex row)                        |
| footer.start            | Footer alignment applied by `start`                       |
| footer.center           | Footer alignment applied by `center`                      |
| footer.end              | Footer alignment applied by `end` (default)               |
| footer.between          | Footer alignment applied by `between`                     |
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
| skeleton.animation      | Pulse animation applied to the whole placeholder          |
| skeleton.bar            | Base look of every placeholder bar                        |
| skeleton.header         | Header bar dimensions                                     |
| skeleton.image          | Image block dimensions                                    |
| skeleton.body.wrapper   | Spacing between body lines                                |
| skeleton.body.line      | Body line dimensions                                      |
| skeleton.body.line-last | Last body line, shortened so the block reads as text      |
| skeleton.footer.wrapper | Footer alignment                                          |
| skeleton.footer.button  | Footer button placeholder dimensions                      |
| border.radius.xs        | Border radius applied when `round="xs"`                   |
| border.radius.sm        | Border radius applied when `round="sm"`                   |
| border.radius.md        | Border radius applied when `round="md"`                   |
| border.radius.lg        | Border radius applied when `round="lg"` (default)         |
| border.radius.xl        | Border radius applied when `round="xl"`                   |
| border.radius.2xl       | Border radius applied when `round="2xl"`                  |
