# TallStackUI: Switcher

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A two-state control that swaps its own content: a text, an icon or both for the `on` state, another for the `off` state, and an animated transition between them. It is a native checkbox under the hood, so it binds to a boolean through `wire:model` or `x-model` and submits in a plain form like any checkbox. Use it for play/pause, follow/following, mute/unmute, like/unlike and similar pairs. For a labelled on/off form field use [Toggle](form/toggle.md); for cycling through a list of values use [Swap](swap.md).

## Basic Usage

```blade
<x-switcher wire:model="following" on="Following" off="Follow" />
```

```blade
<x-switcher wire:model="muted" icon-on="speaker-x-mark" icon-off="speaker-wave" aria-label="Mute" />
```

```blade
<x-switcher wire:model.live="playing" on="Pause" off="Play" icon-on="pause" icon-off="play" />
```

```blade
<x-switcher wire:model="dark" icon-on="moon" icon-off="sun" rotate lg aria-label="Dark mode" />
```

The `on` and `off` also accept a slot, which is what you need when the content carries markup:

```blade
<x-switcher wire:model="liked" aria-label="Like">
    <x-slot:on>
        <x-icon name="heart" solid class="h-6 w-6 text-red-500" />
    </x-slot:on>
    <x-slot:off>
        <x-icon name="heart" outline class="h-6 w-6" />
    </x-slot:off>
</x-switcher>
```

## Attributes

| Attribute | Type               | Default | Description                                                            |
|-----------|--------------------|---------|------------------------------------------------------------------------|
| on        | string\|slot\|null | null    | Content shown while the switcher is on (checked)                       |
| off       | string\|slot\|null | null    | Content shown while the switcher is off (unchecked)                    |
| icon-on   | string\|null       | null    | Icon shown while on, next to the `on` content when both are given      |
| icon-off  | string\|null       | null    | Icon shown while off, next to the `off` content when both are given    |
| position  | string\|null       | 'left'  | Side of the icon relative to the text: 'left' or 'right'               |
| rotate    | bool               | false   | Rotates the content while it swaps                                     |
| flip      | bool               | false   | Flips the content on the vertical axis while it swaps                  |
| xs        | bool               | null    | Extra-small size                                                       |
| sm        | bool               | null    | Small size                                                             |
| md        | bool               | null    | Medium size (default)                                                  |
| lg        | bool               | null    | Large size                                                             |
| checked   | attribute          | —       | Starts on. Only needed outside Livewire, where there is no bound value |
| disabled  | attribute          | —       | Dims the control and locks it. The value is not submitted              |
| readonly  | attribute          | —       | Dims the control and locks it. The value is still submitted            |

Without `rotate` or `flip` the content fades. `globals()->flash()` removes the transition, turning the swap into an instant change.

Both states sit in the same grid cell, so the switcher is always as wide as the larger of the two and nothing around it moves when it swaps. The content aligns to the start of that cell, which keeps the icon in place while the text changes.

The sizes set the text size and the icon size together. The component sets no color: text and icons inherit the color of where the switcher sits, so `class="text-red-500"` is enough to paint it.

The `class` attribute lands on the wrapper. Every other attribute — `wire:model`, `x-model`, `name`, `value`, `id`, `checked`, `aria-*`, `x-on:*` — lands on the checkbox.

## Validation Constraints

- Each state needs something to show. A switcher whose `on` or `off` has no text, no slot and no icon throws.
- `rotate` and `flip` cannot be combined — the render throws.

## Slots

| Slot | Description                                               |
|------|-----------------------------------------------------------|
| on   | Content of the on state, in place of the `on` attribute   |
| off  | Content of the off state, in place of the `off` attribute |

`icon-on` and `icon-off` still render next to a slot. Sizes do not reach what is inside a slot beyond the inherited text size, so an icon written in the slot carries its own size classes.

## Alpine.js Events

| Event       | Description                                                                              |
|-------------|------------------------------------------------------------------------------------------|
| x-on:switch | Fires every time the user switches, with `{ value }` in `detail`: `true` on, `false` off |

```blade
<x-switcher on="Pause" off="Play" x-on:switch="console.log($event.detail.value)" />
```

The event fires on user interaction only. A value changed by the server or by the bound Alpine data swaps the content without firing it, and a `disabled` or `readonly` switcher never fires it.

## Livewire Integration

```blade
<x-switcher wire:model="playing" on="Pause" off="Play" />
<x-switcher wire:model.live="playing" on="Pause" off="Play" />
<x-switcher wire:model="playing" wire:change="toggled" on="Pause" off="Play" />
```

The property is a boolean. When it is already `true` on the first render the checkbox comes checked from the server, so the page opens on the `on` content without animating into it.

## Outside Livewire

```blade
<form method="POST" action="/settings">
    @csrf
    <x-switcher name="notify" value="yes" on="Enabled" off="Muted" checked />
</form>
```

It submits like a native checkbox: the `value` (or `on` when none is given) while on, and nothing at all while off. `checked` sets the initial state. With Alpine, `x-model` binds it to a boolean:

```blade
<div x-data="{ open: false }">
    <x-switcher x-model="open" on="Hide details" off="Show details" />
    <div x-show="open">...</div>
</div>
```

## Accessibility

The checkbox carries `role="switch"` and stays focusable: Tab reaches it, Space switches it, and the wrapper shows a focus outline for keyboard users. The content of the hidden state is removed from the accessibility tree, so the announced name is the visible text. A switcher made only of icons has no text to announce — give it an `aria-label`.

## Soft Customization

```php
TallStackUi::customize()
    ->switcher()
    ->block('content.transition', 'transition-all duration-500 ease-in-out');
```

### Available Blocks

| Block              | Purpose                                                                  |
|--------------------|--------------------------------------------------------------------------|
| wrapper.base       | The label wrapping everything: grid stacking, cursor and focus outline   |
| wrapper.flip       | Perspective added to the wrapper with `flip`                             |
| wrapper.locked     | Look applied while disabled or readonly                                  |
| input              | The visually hidden checkbox. Must keep `peer`, which the states rely on |
| content.base       | Layout shared by both states: same grid cell, icon and text side by side |
| content.reverse    | Applied with `position="right"` to put the icon after the text           |
| content.transition | Transition of both states. Removed under `globals()->flash()`            |
| content.sizes.*    | Text size per size (`xs`, `sm`, `md`, `lg`)                              |
| on.fade            | Hidden/visible classes of the on state for the default effect            |
| on.rotate          | The same for `rotate`                                                    |
| on.flip            | The same for `flip`                                                      |
| off.fade           | Hidden/visible classes of the off state for the default effect           |
| off.rotate         | The same for `rotate`                                                    |
| off.flip           | The same for `flip`                                                      |
| icon.sizes.*       | Icon size per size (`xs`, `sm`, `md`, `lg`)                              |

The `on.*` and `off.*` blocks drive the state through `peer-checked:` variants, with no JavaScript involved. To color one state only, append to its block:

```php
TallStackUi::customize('switcher', scope: 'like')
    ->block('on.fade')
    ->append('text-red-500');
```

```blade
<x-switcher scope="like" wire:model="liked" icon-on="heart" icon-off="heart" aria-label="Like" />
```
