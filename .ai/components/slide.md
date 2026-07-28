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

Removing the body padding. The result is flush horizontally but not vertically:
the vertical inset lives on the outer panel, shared with the header and the
footer, so removing it would move all three. Vertical bleed is a soft
customization of `wrapper.fifth`:

```blade
<x-slide id="activity" title="Activity" paddingless>
    <ul class="divide-y divide-gray-200">
        <li class="px-4 py-3">First entry</li>
        <li class="px-4 py-3">Second entry</li>
    </ul>
</x-slide>
```

Distributing the footer actions instead of pushing them all to the end:

```blade
<x-slide id="record" title="Record">
    <p>Body content.</p>

    <x-slot:footer between>
        <x-button text="Delete" color="red" wire:click="delete" />
        <x-button text="Save" wire:click="save" />
    </x-slot:footer>
</x-slide>
```

Taking over the footer layout with `unwrapped`. The border and the padding of
the footer area stay; only the aligning wrapper is dropped:

```blade
<x-slide id="wizard" title="Wizard">
    <p>Body content.</p>

    <x-slot:footer unwrapped>
        <div class="grid grid-cols-2 gap-2">
            <x-button text="Back" />
            <x-button text="Next" />
        </div>
    </x-slot:footer>
</x-slide>
```

## Attributes

| Attribute   | Type                        | Default                    | Description                                                                                                                                             |
|-------------|-----------------------------|----------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------|
| id          | string\|null                | 'slide'                    | Unique identifier used for targeting with JS API and events                                                                                             |
| zIndex      | string\|null                | null (from config: 'z-50') | CSS z-index class                                                                                                                                       |
| wire        | string\|bool\|null          | null                       | Livewire entangle property name (string) or boolean to use default 'slide'                                                                              |
| title       | ComponentSlot\|string\|null | null                       | Title text or slot displayed in the slide header                                                                                                        |
| footer      | ComponentSlot\|string\|null | null                       | Footer content or slot                                                                                                                                  |
| blur        | bool\|string\|null          | null (from config: false)  | Backdrop blur effect (false, sm, md, lg, xl, or true for sm)                                                                                            |
| persistent  | bool\|null                  | null (from config: false)  | When true, prevents closing via outside click or Escape key                                                                                             |
| size        | string\|null                | null (from config: 'lg')   | Panel size (sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, full)                                                                                         |
| entangle    | string\|null                | 'slide'                    | Livewire property name for entangle binding                                                                                                             |
| center      | bool\|null                  | null                       | Vertical centering (not commonly used with slides)                                                                                                      |
| overflow    | bool\|null                  | null (from config: false)  | When true, avoids hiding body overflow                                                                                                                  |
| left        | bool\|null                  | null                       | When true, slide enters from the left side                                                                                                              |
| top         | bool\|null                  | null                       | When true, slide enters from the top                                                                                                                    |
| bottom      | bool\|null                  | null                       | When true, slide enters from the bottom                                                                                                                 |
| paddingless | bool\|null                  | null                       | When true, removes the padding of the body. Flush horizontally only: the vertical inset lives on the outer panel, shared with the header and the footer |

## Slots

| Slot      | Description                                                                   |
|-----------|-------------------------------------------------------------------------------|
| (default) | Main body content of the slide panel                                          |
| title     | Title content (supports ComponentSlot with custom attributes)                 |
| footer    | Footer content; accepts plain string or ComponentSlot, end-aligned by default |

### Footer Slot Attributes

| Attribute | Description                                                        |
|-----------|--------------------------------------------------------------------|
| start     | Aligns the footer content to the start                             |
| center    | Centers the footer content                                         |
| end       | Aligns the footer content to the end (default when none is passed) |
| between   | Distributes the footer content with space between                  |
| unwrapped | Drops the aligning wrapper, keeping the footer border and padding  |

Any other attribute on the slot — `class`, `x-on:*`, `dusk` — is merged into the
footer container.

## Validation Constraints

- The `wire` property cannot be an empty string.
- The `footer` slot cannot combine two or more alignments.
- The `footer` slot cannot use `unwrapped` together with an alignment.
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

| Block Name       | Purpose                                         |
|------------------|-------------------------------------------------|
| wrapper.first    | Fixed overlay backdrop                          |
| wrapper.second   | Fixed full-screen overflow container            |
| wrapper.third    | Absolute overflow wrapper                       |
| wrapper.fourth   | Pointer-events container with positioning       |
| wrapper.fifth    | Panel flex column with background and shadow    |
| blur.sm          | Small backdrop blur effect                      |
| blur.md          | Medium backdrop blur effect                     |
| blur.lg          | Large backdrop blur effect                      |
| blur.xl          | Extra-large backdrop blur effect                |
| title.text       | Title heading styles                            |
| title.close      | Close button icon styles                        |
| body             | Scrollable body content area styles             |
| body.paddingless | Padding reset applied when `paddingless` is set |
| footer.wrapper   | Footer container with border and padding        |
| footer.base      | Footer aligning wrapper (flex row)              |
| footer.start     | Footer alignment applied by `start`             |
| footer.center    | Footer alignment applied by `center`            |
| footer.end       | Footer alignment applied by `end` (default)     |
| footer.between   | Footer alignment applied by `between`           |
| header           | Header padding container                        |
