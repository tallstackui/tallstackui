# TallStackUI: Step

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 65+ Blade components for building modern web interfaces.

A multi-step wizard component with three visual variations: simple bar indicators, numbered circles with dividers, and bordered panels. The navigation bar (`helpers`) ships in three looks — `default`, `minimal` and `compact` — and accepts fully custom previous/next buttons through slots. Supports Livewire property binding and step-change events.

## Basic Usage

Simple steps with navigation helpers:

```blade
<x-step selected="1" helpers navigate navigate-previous>
    <x-step.items step="1" title="Account" description="Create your account">
        <p>Step 1 content goes here.</p>
    </x-step.items>
    <x-step.items step="2" title="Profile" description="Set up your profile">
        <p>Step 2 content goes here.</p>
    </x-step.items>
    <x-step.items step="3" title="Review" description="Review and submit">
        <p>Step 3 content goes here.</p>
    </x-step.items>
</x-step>
```

Circle variation with Livewire binding:

```blade
<x-step wire:model="currentStep" circles helpers>
    <x-step.items step="1" title="Details">
        <p>Enter your details.</p>
    </x-step.items>
    <x-step.items step="2" title="Confirm">
        <p>Confirm your information.</p>
    </x-step.items>
</x-step>
```

Panel variation with finish button:

```blade
<x-step selected="1" panels helpers navigate x-on:finish="alert('Done!')">
    <x-slot:finish>
        <x-button text="Submit" color="green" />
    </x-slot:finish>
    <x-step.items step="1" title="Step One">
        <p>First step content.</p>
    </x-step.items>
    <x-step.items step="2" title="Step Two">
        <p>Second step content.</p>
    </x-step.items>
</x-step>
```

## Attributes

| Attribute         | Type            | Default | Description                                                                                                      |
|-------------------|-----------------|---------|------------------------------------------------------------------------------------------------------------------|
| selected          | int\|null       | null    | Initially selected step number (or use `wire:model` for Livewire binding)                                        |
| panels            | bool            | false   | Uses the bordered panels variation                                                                               |
| circles           | bool            | false   | Uses the numbered circles variation                                                                              |
| simple            | bool            | false   | Uses the simple bar indicators variation (default)                                                               |
| helpers           | bool\|string    | false   | Shows the navigation bar. `true` renders the `default` variant; a string picks `minimal`, `compact` or a custom view path. See [Helper Variants](#helper-variants) |
| navigate          | bool            | false   | Allows forward navigation by clicking step indicators                                                            |
| navigate-previous | bool            | false   | Shows a "Previous" button in the helpers area                                                                    |
| variation         | string\|null    | null    | Visual variation type (automatically set from panels/circles/simple)                                             |
| skeleton          | bool\|int\|null | null    | Renders a structural placeholder instead of the steps. A bare flag draws 3 indicators; an integer sets the count |

## Slots

| Slot      | Description                                                                        |
|-----------|------------------------------------------------------------------------------------|
| (default) | `<x-step.items>` children defining each step's content                             |
| previous  | Replaces the built-in previous button (requires `helpers`). See [Custom Navigation Buttons](#custom-navigation-buttons) |
| next      | Replaces the built-in next button (requires `helpers`). See [Custom Navigation Buttons](#custom-navigation-buttons) |
| finish    | Custom finish button content shown when on the last step (requires `helpers`)      |

## Events

| Event       | Detail           | Description                                                                              |
|-------------|------------------|-------------------------------------------------------------------------------------------|
| x-on:change | `{step: number}` | Fired when the active step changes via the built-in buttons or the `next()`/`previous()` Alpine methods |
| x-on:finish | `{step: number}` | Fired when the finish button is clicked (string `finish` attribute only)                 |

## Helper Variants

`helpers` selects the navigation bar look the same way the Table's `paginator` does:

```blade
<x-step selected="1" helpers>              {{-- default: bordered buttons --}}
<x-step selected="1" helpers="minimal">    {{-- borderless ghost buttons --}}
<x-step selected="1" helpers="compact">    {{-- grouped shell with a position indicator --}}
<x-step selected="1" helpers="app.steps.custom"> {{-- your own view --}}
```

- **default** — individual bordered buttons with label + chevron, hidden at the
  edges (`x-show`).
- **minimal** — same layout, borderless text buttons.
- **compact** — a single shell anchored right with icon-only buttons and a
  `current/total` indicator. Buttons are disabled at the edges instead of
  hidden, so the shell never changes width. The finish button/slot renders to
  the left of the shell.

A string containing `.` or `::` is treated as a view path, letting an
application ship its own bar. Anything else must be one of the bundled
variants, or the component throws.

The variant used by a bare `helpers` flag comes from
`config('tallstackui.components.step.helpers')` (`default` out of the box), so
an application can switch every wizard at once.

## Custom Navigation Buttons

The `previous` and `next` slots fully replace the built-in buttons. The
component keeps only the visibility wrapper (previous hides on the first step,
next hides on the last — in `compact` slot content stays always visible); the
click behavior is yours. Call the `next()` / `previous()` methods available in
the Alpine scope to navigate — they also dispatch the `change` event — or
mutate `selected` directly to skip the event:

```blade
<x-step selected="1" helpers>
    <x-step.items step="1" title="Account">...</x-step.items>
    <x-step.items step="2" title="Review">...</x-step.items>
    <x-slot:previous>
        <x-button color="secondary" outline icon="arrow-left" x-on:click="previous()">Back</x-button>
    </x-slot:previous>
    <x-slot:next>
        <x-button icon="arrow-right" position="right" x-on:click="next()">Continue</x-button>
    </x-slot:next>
</x-step>
```

Guarding navigation is one expression away: `x-on:click="if (valid()) next()"`.

## Wireable Mode (Livewire Property Binding)

Bind the current step to a Livewire string property:

```blade
<!-- Livewire string property: $step - initial value: "1" -->
<x-step wire:model="step" helpers navigate-previous>
    <x-step.items step="1" title="Starting" description="Step One">
        Step one...
    </x-step.items>
    <x-step.items step="2" title="Advancing" description="Step Two">
        Step two...
    </x-step.items>
    <x-step.items step="3" title="Finishing" description="Step Three">
        Step three...
    </x-step.items>
</x-step>
```

Use `wire:model.live` for real-time server sync on every step change.

## Alpine.js Event Payloads

```blade
<x-step selected="1" helpers
    x-on:change="alert(`Changed: ${$event.detail.step}`)"
    x-on:finish="alert(`Finished: ${$event.detail.step}`)">
    <x-step.items step="1" title="Starting" description="Step One">
        Step one...
    </x-step.items>
    <x-step.items step="2" title="Advancing" description="Step Two">
        Step two...
    </x-step.items>
    <x-step.items step="3" title="Finishing" description="Step Three" completed>
        Step three...
    </x-step.items>
</x-step>
```

## Skeleton

Renders a placeholder shaped like the wizard, for the first paint before the
steps exist. Meant for the `placeholder()` of a `#[Lazy]` Livewire component.

```blade
<x-step skeleton />                             {{-- 3 indicators, simple --}}
<x-step skeleton="4" circles helpers />
<x-step skeleton="3" panels />
```

The indicator strip is drawn in the current variation — `simple`, `circles` or
`panels` — followed by a content block. Helper buttons appear when `helpers` is
set, and the previous button when `navigate-previous` is set.

The real strip is built by Alpine from the registered `<x-step.items>`; in
skeleton mode there are no children, so the count comes from the prop instead.

Any integer below `1` throws.

### Customizations Carry Over

The `skeleton.*` blocks are only the bars. Everything structural is resolved
from this component's **own, existing blocks**, because the skeleton view calls
the same `classes()` as the normal one — customization is resolved on the
component, not on the view. Whatever you already changed applies to the
placeholder too, so the box keeps matching the box it stands in for. Scopes
work the same, including when they target the placeholder alone.

Step reuses `wrapper.{variation}`, `panels-shape`, `circles.*`, `simple.*`, `panels.*`, `content` and `helpers.wrapper`.

Blocks the placeholder does not render have nothing to act on there.
Customizing them is not an error; it simply has no effect while the skeleton
is on screen.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->step()
    ->block('wrapper.simple', 'your-tailwind-classes');
```

### Available Blocks

| Block Name                  | Purpose                              |
|-----------------------------|--------------------------------------|
| panels-shape                | Panels outer frame, border and clip  |
| wrapper.panels              | Panels variation scroll container    |
| wrapper.simple              | Simple variation list container      |
| wrapper.circles             | Circles variation list container     |
| circles.li                  | Circle variation list item           |
| circles.wrapper             | Circle item flex/alignment wrapper   |
| circles.check               | Completed check icon size and color  |
| circles.circle.wrapper      | Circle badge container               |
| circles.circle.inactive     | Inactive circle border and text      |
| circles.circle.current      | Current step circle border and text  |
| circles.circle.border       | Completed circle border              |
| circles.circle.active       | Completed circle background          |
| circles.highlighter.wrapper | Small dot indicator container        |
| circles.highlighter.current | Current step dot color               |
| circles.highlighter.active  | Completed step dot color             |
| circles.divider.wrapper     | Connecting line between circles      |
| circles.divider.inactive    | Inactive divider color               |
| circles.divider.active      | Completed divider color              |
| circles.text.wrapper        | Step text container                  |
| circles.text.title          | Step title text styling              |
| circles.text.description    | Step description text styling        |
| simple.li                   | Simple variation list item           |
| simple.bar.wrapper          | Simple bar indicator wrapper         |
| simple.bar.inactive         | Inactive bar border                  |
| simple.bar.current          | Current step bar border              |
| simple.bar.active           | Completed bar border                 |
| simple.text.title.wrapper   | Simple title text wrapper            |
| simple.text.title.inactive  | Inactive title color                 |
| simple.text.title.current   | Current title color                  |
| simple.text.title.active    | Completed title color                |
| simple.text.description     | Simple description text styling      |
| panels.li                   | Panel variation list item            |
| panels.wrapper              | Panel group flex wrapper             |
| panels.check                | Completed check icon size            |
| panels.item                 | Panel item padding and font          |
| panels.circle.wrapper       | Panel circle badge container         |
| panels.circle.inactive      | Inactive panel circle border         |
| panels.circle.current       | Current panel circle background      |
| panels.circle.active        | Completed panel circle background    |
| panels.divider.wrapper      | Panel arrow divider container        |
| panels.divider.svg          | Panel arrow SVG color                |
| panels.text.number.active   | Active step number text color        |
| panels.text.number.inactive | Inactive step number text color      |
| panels.text.title.wrapper   | Panel title text wrapper             |
| panels.text.title.inactive  | Inactive panel title color           |
| panels.text.title.active    | Completed panel title color          |
| panels.text.description     | Panel description text styling       |
| content                     | Step content area margin             |
| helpers.wrapper             | Skeleton helper row flex container   |
| skeleton.animation          | Pulse animation on the placeholder   |
| skeleton.bar                | Base look of every placeholder bar   |
| skeleton.circle             | Circle placeholder (circles)         |
| skeleton.panel-circle       | Circle placeholder (panels)          |
| skeleton.simple-bar         | Top bar placeholder (simple)         |
| skeleton.title              | Step title bar dimensions            |
| skeleton.description        | Step description bar dimensions      |
| skeleton.content            | Content block placeholder dimensions |
| skeleton.helper             | Helper button placeholder dimensions |
