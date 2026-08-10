# TallStackUI: Button Circle

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

A circular button component for icon-only or single-character actions. Supports solid, light, outline, and flat styles with optional loading states.

## Basic Usage

```blade
<x-button.circle icon="plus" />
```

```blade
<x-button.circle text="A" color="red" outline />
```

```blade
<x-button.circle icon="trash" color="red" light loading="delete" />
```

```blade
<x-button.circle icon="arrow-right" href="/next" lg />
```

Choosing the loading indicator. `spinner` renders one of the nine visual
[Spinner](../spinner.md) variants as the `wire:loading` indicator — `ring`,
`throbber`, `gradient`, `ping`, `dots`, `pulse`, `typing`, `bars` or `wave`.
Omitted, it falls back to `config('tallstackui.components.button.spinner')`
(the key is shared with `<x-button>`, `null` by default) and finally to the
default effect. The textual variants (`shimmer`, `caret`, `terminal`,
`thinking`) animate their own text and throw inside a button:

```blade
<x-button.circle icon="trash" loading="delete" spinner="bars" />
<x-button.circle icon="trash" spinner="shimmer" />   {{-- throws --}}
```

## Attributes

| Attribute | Type         | Default   | Description                                                                                                                         |
|-----------|--------------|-----------|-------------------------------------------------------------------------------------------------------------------------------------|
| text      | string\|null | null      | Single character or short text displayed inside the circle                                                                          |
| icon      | string\|null | null      | Heroicon name displayed inside the circle (takes precedence over text)                                                              |
| color     | string\|null | 'primary' | Color theme (e.g., primary, red, green, yellow)                                                                                     |
| href      | string\|null | null      | When set, renders as an anchor tag instead of a button                                                                              |
| loading   | string\|null | null      | Livewire action name to show a loading spinner during execution                                                                     |
| delay     | string\|null | null      | Delay duration for the loading indicator (e.g., 'longest')                                                                          |
| spinner   | string\|null | null      | Loading spinner variant: ring, throbber, gradient, ping, dots, pulse, typing, bars, wave. Falls back to the `button.spinner` config |
| xs        | string\|null | null      | Extra-small size                                                                                                                    |
| sm        | string\|null | null      | Small size                                                                                                                          |
| md        | string\|null | null      | Medium size (default)                                                                                                               |
| lg        | string\|null | null      | Large size                                                                                                                          |
| solid     | bool         | null      | Uses the solid color style variant (default when no style set)                                                                      |
| outline   | bool         | null      | Uses the outline color style variant                                                                                                |
| light     | bool         | false     | Uses the light color style variant                                                                                                  |
| flat      | bool         | false     | Uses the flat color style variant (no border)                                                                                       |
| submit    | bool         | false     | Sets button type to 'submit' for form submission                                                                                    |
| unfocus   | bool         | false     | No focus on mouse click (no ring/color); keyboard focus kept                                                                        |

## Slots

| Slot      | Description                                                     |
|-----------|-----------------------------------------------------------------|
| (default) | Custom content, used when neither `icon` nor `text` is provided |

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

```php
TallStackUi::customize()
    ->button('circle')
    ->block('wrapper.base', 'your-tailwind-classes');
```

### Available Blocks

| Block Name       | Purpose                                                                   |
|------------------|---------------------------------------------------------------------------|
| wrapper.base     | Base circle button styles (inline-flex, rounded-full, border, transition) |
| wrapper.sizes.xs | Extra-small circle dimensions                                             |
| wrapper.sizes.sm | Small circle dimensions                                                   |
| wrapper.sizes.md | Medium circle dimensions                                                  |
| wrapper.sizes.lg | Large circle dimensions                                                   |
| icon.sizes.xs    | Extra-small icon dimensions                                               |
| icon.sizes.sm    | Small icon dimensions                                                     |
| icon.sizes.md    | Medium icon dimensions                                                    |
| icon.sizes.lg    | Large icon dimensions                                                     |
| text.sizes.xs    | Extra-small text size                                                     |
| text.sizes.sm    | Small text size                                                           |
| text.sizes.md    | Medium text size                                                          |
| text.sizes.lg    | Large text size                                                           |

The loading indicator mirrors the Spinner's blocks under a `spinner.` prefix,
sized to the circle's icon box and isolated from the Spinner component's own
customization:

| Block Name                           | Purpose                                                      |
|--------------------------------------|--------------------------------------------------------------|
| spinner.delays.{0..4}                | Shared stagger applied by index to every multi-child variant |
| spinner.ring.base                    | Spinning border                                              |
| spinner.ring.sizes.{xs,sm,md,lg}     | Ring diameter and border width                               |
| spinner.throbber.base                | Rotating SVG                                                 |
| spinner.throbber.segment             | Each of the twelve segments                                  |
| spinner.throbber.sizes.{xs,sm,md,lg} | SVG size                                                     |
| spinner.gradient.base                | Rotating SVG                                                 |
| spinner.gradient.track               | Dimmed circle behind the arc                                 |
| spinner.gradient.head                | Highlighted arc                                              |
| spinner.gradient.sizes.{xs,sm,md,lg} | SVG size                                                     |
| spinner.ping.wrapper                 | Positioning context                                          |
| spinner.ping.echo                    | Expanding echo ring                                          |
| spinner.ping.core                    | Static inner ring                                            |
| spinner.ping.sizes.wrapper.*         | Ring diameter                                                |
| spinner.ping.sizes.border.*          | Ring border width                                            |
| spinner.dots.wrapper                 | Dot row                                                      |
| spinner.dots.dot                     | Each dot                                                     |
| spinner.dots.sizes.wrapper.*         | Gap between dots                                             |
| spinner.dots.sizes.dot.*             | Dot diameter                                                 |
| spinner.pulse.dot                    | The single dot                                               |
| spinner.pulse.sizes.{xs,sm,md,lg}    | Dot diameter                                                 |
| spinner.typing.wrapper               | Dot row                                                      |
| spinner.typing.dot                   | Each dot                                                     |
| spinner.typing.sizes.wrapper.*       | Gap between dots                                             |
| spinner.typing.sizes.dot.*           | Dot diameter                                                 |
| spinner.bars.wrapper                 | Bar row                                                      |
| spinner.bars.bar                     | Each bar                                                     |
| spinner.bars.sizes.wrapper.*         | Gap between bars                                             |
| spinner.bars.sizes.bar.*             | Bar height and width                                         |
| spinner.wave.wrapper                 | Bar row                                                      |
| spinner.wave.bar                     | Each bar                                                     |
| spinner.wave.sizes.wrapper.*         | Gap between bars                                             |
| spinner.wave.sizes.bar.*             | Bar height and width                                         |
